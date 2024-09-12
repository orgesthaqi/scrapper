<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-6 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';">
                            <title>Close</title>
                            <path d="M14.348 5.652a.5.5 0 10-.707-.707L10 8.586 6.354 4.945a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.414l3.646 3.646a.5.5 0 00.707-.707L10.707 9.293l3.646-3.646z" />
                        </svg>
                    </span>
                </div>
            @endif

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Account
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Followers
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Export
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($followers as $account)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="https://www.instagram.com/{{ $account->instagram_account }}" class="flex items-center text-blue-600 hover:underline" target="_blank">
                                        {{ $account->instagram_account }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $account->total  }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('followers.export', ['account' => $account->instagram_account]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Export
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('followers.show', ['account' => $account->instagram_account]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View All
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
