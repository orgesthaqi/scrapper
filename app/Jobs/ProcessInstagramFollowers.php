<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use App\Models\User;
use App\Models\Token;
use App\Models\InstagramFollowers;
use Illuminate\Support\Facades\Mail;

class ProcessInstagramFollowers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    protected $instagram_account;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @param string $instagram_account
     * @return void
     */
    public function __construct($instagram_account, $userId)
    {
        $this->instagram_account = $instagram_account;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $apiUrl = 'https://instagram-scraper-api2.p.rapidapi.com/v1/followers';
        $apiHost = 'instagram-scraper-api2.p.rapidapi.com';

        $paginationToken = null;
        $user = User::find($this->userId);

        if ($user->tokens_available <= 0) {
            // Send an email to the user to buy more tokens or wait for the next cycle to start
            Mail::send('emails.token_warning', ['user' => $user, 'key' => $apiKey], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('You are out of tokens');
            });
            return;
        }

        try {
            do {
                // Get the next token
                $apiKey = $this->getNextToken();

                // Prepare request parameters
                $queryParams = [
                    'username_or_id_or_url' => $this->instagram_account,
                ];

                if ($paginationToken) {
                    $queryParams['pagination_token'] = $paginationToken;
                }

                // Send the request
                $response = $client->request('GET', $apiUrl, [
                    'headers' => [
                        'x-rapidapi-host' => $apiHost,
                        'x-rapidapi-key' => $apiKey,
                    ],
                    'query' => $queryParams,
                ]);

                // Parse the response
                $data = json_decode($response->getBody()->getContents(), true);

                // Process the data and save to the database
                foreach ($data['data']['items'] as $item) {
                    $full_name = $this->filterFullName($item['full_name']);
                    $user_data = $this->getGenderByLastLetter($full_name);

                    InstagramFollowers::create([
                        'full_name' => $full_name,
                        'first_name' => $user_data['first_name'],
                        'last_name' => $user_data['last_name'],
                        'gender' => $user_data['gender'],
                        'id' => $item['id'],
                        'is_private' => $item['is_private'],
                        'is_verified' => $item['is_verified'],
                        'profile_pic_url' => $item['profile_pic_url'],
                        'username' => $item['username'],
                        'instagram_account' => $this->instagram_account,
                        'account_id' => $this->userId,
                    ]);
                }

                // Decrease the user's available tokens by 1
                $user->decrement('tokens_available', 1);

                // Update the pagination token for the next request
                $paginationToken = $data['pagination_token'] ?? null;

            } while ($paginationToken && $user->tokens_available > 0);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Extract the error message from the response
            $response = $e->getResponse();
            $responseBody = $response ? $response->getBody()->getContents() : 'No response body';
            $errorData = json_decode($responseBody, true);

            // Send an email to the user with the error details
            Mail::send('emails.error_notification', [
                'user' => $user,
                'error' => $errorData['message'] ?? 'Unknown error occurred',
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Error Occurred During Instagram Data Fetch');
            });
        } catch (\Exception $e) {
            // Handle other exceptions
            Mail::send('emails.error_notification', [
                'user' => $user,
                'error' => $e->getMessage(),
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Error Occurred During Instagram Data Fetch');
            });
        }
    }

    protected function filterFullName($fullName)
    {
        return preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $fullName);
    }

    protected function getGenderByLastLetter($fullName) {
        $femalePatterns = ['a', 'e'];

        // Trim the input to remove any leading or trailing whitespace
        $fullName = trim($fullName);

        // Check if the full name is empty
        if (empty($fullName)) {
            return [
                'first_name' => '',
                'last_name' => '',
                'gender' => 'unknown'
            ]; // Cannot determine gender from an empty input
        }

        // Extract parts of the name
        $names = explode(' ', $fullName);

        // Handle cases with no spaces (single name)
        if (count($names) === 1) {
            $firstName = '';
            $lastName = $names[0];
        } else {
            $firstName = implode(' ', array_slice($names, 0, -1)); // All parts except the last one
            $lastName = end($names); // Last part of the name
        }

        // Check if the last part of the name is empty
        if (empty($lastName)) {
            return [
                'first_name' => $firstName,
                'last_name' => '',
                'gender' => 'unknown'
            ]; // Cannot determine gender from an empty last name
        }

        // Get the last letter and convert to lowercase
        $lastLetter = strtolower(substr($firstName, -1));

        // Determine gender based on last letter
        $gender = in_array($lastLetter, $femalePatterns) ? 'female' : 'male';

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $gender
        ];
    }

    public function getNextToken() {
        // Find the last used token
        $lastUsedToken = Token::where('used', true)
                                ->orderBy('id', 'desc')
                                ->first();

        // If there is a last used token, find the next one
        if ($lastUsedToken) {
            $nextToken = Token::where('id', '>', $lastUsedToken->id)
                ->orderBy('id', 'asc')
                ->first();

            // If no token is found, reset all tokens and get the first one
            if (!$nextToken) {
                Token::query()->update(['used' => false]);
                $nextToken = Token::orderBy('id', 'asc')->first();
            }
        } else {
            // If no token has been used, get the first one
            $nextToken = Token::orderBy('id', 'asc')->first();
        }

        // Mark the token as used and increment the count
        if ($nextToken) {
            $nextToken->used = true;
            $nextToken->save();
        }

        // Return the token or null if none is available
        return $nextToken ? $nextToken->token : null;
    }
}
