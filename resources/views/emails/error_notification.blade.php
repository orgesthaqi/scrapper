<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Occurred During Instagram Data Fetch</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Error Occurred During Instagram Data Fetch</h1>

        <p class="mb-4">Dear {{ $user->name }},</p>

        <p class="mb-4">We encountered an error while fetching data from Instagram for your account <strong>{{ $user->instagram_account }}</strong>.</p>

        <p class="font-semibold mb-2">Error Details:</p>
        <pre class="bg-red-100 border border-red-300 text-red-700 p-4 rounded mb-4">
            {{ $error }}
        </pre>

        <p class="mb-4">Please try again later. If the issue persists, feel free to contact our support team for further assistance.</p>
    </div>
</body>
</html>
