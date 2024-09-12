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
                            <th scope="col" class="px-4 py-3">
                                Full Name
                            </th>
                            <th scope="col" class="px-4 py-3">
                                First Name
                            </th>
                            <th scope="col" class="px-4 py-3">
                                Last Name
                            </th>
                            <th scope="col" class="px-4 py-3">
                                Gender
                            </th>
                            <th scope="col" class="px-4 py-3">
                                Username
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($followers as $account)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-4 py-4">
                                    {{ $account->full_name }}
                                </td>
                                <td class="px-4 py-4">
                                    {{ $account->first_name }}
                                </td>
                                <td class="px-4 py-4">
                                    {{ $account->last_name }}
                                </td>
                                <td class="px-4 py-4">
                                    {{ $account->gender  }}
                                </td>
                                <td class="px-4 py-4">
                                    {{ $account->username  }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
