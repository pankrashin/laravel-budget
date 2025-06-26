<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Displaying Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('accounts.store') }}">
                        @csrf

                        <!-- Account Name -->
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Account Name</label>
                            <input id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="name" value="{{ old('name') }}" required autofocus />
                        </div>

                        <!-- Currency -->
                        <div class="mt-4">
                            <label for="currency" class="block font-medium text-sm text-gray-700">Currency (3-letter code)</label>
                            <input id="currency" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="currency" value="{{ old('currency') }}" required placeholder="e.g., USD, EUR, JPY" maxlength="3" />
                            <p class="mt-2 text-sm text-gray-500">
                                Use the official 3-letter ISO currency code.
                            </p>
                        </div>

                        <!-- Initial Balance -->
                        <div class="mt-4">
                            <label for="initial_balance" class="block font-medium text-sm text-gray-700">Initial Balance (Optional)</label>
                            <input id="initial_balance" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" step="0.01" name="initial_balance" value="{{ old('initial_balance', '0.00') }}" placeholder="0.00" />
                            <p class="mt-2 text-sm text-gray-500">
                                Set an initial balance for this account. Leave as 0.00 if starting with an empty account.
                            </p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Cancel
                            </a>

                            <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150" style="margin-left: 12px">
                                Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>