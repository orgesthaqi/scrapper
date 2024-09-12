<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\InstagramFollowers;
use App\Jobs\ProcessInstagramFollowers;

class FollowersController extends Controller
{
    public function index()
    {
        return view('followers.index');
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        $instagramAccount = $request->username;

        ProcessInstagramFollowers::dispatch($instagramAccount, $userId);

        return redirect()->route('dashboard')->with('status', 'Processing started. Check back later for the results.');
    }

    public function show($account)
    {
        $followers = InstagramFollowers::where('instagram_account', $account)->get();

        return view('followers.show', compact('followers'));
    }

    public function export(Request $request, $account)
    {
        $followers = InstagramFollowers::where('instagram_account', $account)->get();

        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne(['Username']);

        foreach ($followers as $follower) {
            $csv->insertOne([$follower->username]);
        }

        $csv->output('followers.csv');
    }
}
