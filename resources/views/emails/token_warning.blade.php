<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Warning</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="max-w-lg mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
        <h1 class="text-xl font-bold text-red-600 mb-4">You're Out of Tokens</h1>
        <p class="mb-4">
            Hi {{ $user->name }},
        </p>
        <p class="mb-4">
            We noticed that you have run out of tokens. To continue using our services, please purchase more tokens or wait for the next cycle to start.
        </p>
        <p class="mb-6">
            You can visit your account to manage your tokens and view your usage.
        </p>
        <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
            Purchase Tokens
        </a>
        <p class="mt-6 text-sm text-gray-600">
            Thank you for being a valued user of our service.
        </p>
    </div>
</body>
</html>
