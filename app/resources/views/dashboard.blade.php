<x-app-layout>
    {{-- The x-slot header remains the same --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="hidden sm:flex items-center space-x-2">
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-gray-70 uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Add Transaction
                </a>
                 <a href="{{ route('accounts.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Add Account
                </a>
            </div>
        </div>
    </x-slot>

    {{-- The main page content remains the same --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- All the HTML for stats, accounts, etc. goes here. This part is fine. --}}
            <!-- Top Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Net Worth Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 lg:col-span-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center space-x-2">
                                <div class="bg-indigo-100 p-2 rounded-full"><svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></div>
                                <h3 class="text-lg font-medium text-gray-600">Net Worth</h3>
                            </div>
                            <p class="text-4xl font-bold mt-2 text-gray-800">{{ number_format($netWorth, 2) }}</p>
                        </div>
                        <form action="{{ route('dashboard') }}" method="GET">
                            <select name="currency" onchange="this.form.submit()" class="text-sm font-semibold border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach ($supportedCurrencies as $currency)
                                    <option value="{{ $currency }}" {{ $displayCurrency == $currency ? 'selected' : '' }}>{{ $currency }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <!-- Total Accounts Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                    <div class="flex items-center space-x-2">
                         <div class="bg-green-100 p-2 rounded-full"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4"></path></svg></div>
                        <h3 class="text-lg font-medium text-gray-600">Accounts</h3>
                    </div>
                    <p class="text-4xl font-bold mt-2 text-gray-800">{{ count($accounts) }}</p>
                </div>
            </div>

            <!-- Spending Chart Section -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Spending Last 30 Days ({{ $displayCurrency }})</h3>
                    <div style="height: 300px;">
                        <canvas id="spendingChart"></canvas>
                    </div>
                </div>
            </div>
            
            {{-- The rest of the content (Accounts list, etc.) is fine, so it's omitted for brevity but should be here --}}

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        window.addEventListener('load', function () {
            try {
                const chartLabels = @json($chartLabels);
                const chartData = @json($chartData);
                const displayCurrency = @json($displayCurrency);
                const spendingChartCanvas = document.getElementById('spendingChart');

                console.log("Chart script loaded.");
                console.log("Labels:", chartLabels);
                console.log("Data:", chartData);

                if (spendingChartCanvas) {
                    console.log("Canvas element found.");
                    
                    new Chart(spendingChartCanvas, {
                        type: 'bar',
                        data: {
                            labels: chartLabels,
                            datasets: [{
                                label: 'Spending',
                                data: chartData,
                                backgroundColor: 'rgba(79, 70, 229, 0.75)',
                                borderColor: 'rgba(79, 70, 229, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            const value = context.parsed.y || 0;
                                            return `Spending: ${new Intl.NumberFormat('en-US', { style: 'currency', currency: displayCurrency }).format(value)}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                     console.log("Chart initialized successfully.");

                } else {
                    console.error("Error: Canvas element with ID 'spendingChart' was not found.");
                }
            } catch (e) {
                console.error("An error occurred during chart initialization:", e);
            }
        });
    </script>
    
     <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
<!-- Accounts Section (Left) -->
<div class="lg:col-span-2">
    <div class="bg-white overflow-hidden shadow-lg rounded-xl">
        <div class="p-6">
            <!-- Card Header -->
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-xl font-semibold text-gray-700">Accounts</h3>
                <a href="{{ route('accounts.create') }}" class="text-indigo-500 hover:text-indigo-700" title="Add New Account">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path></svg>
                </a>
            </div>

            <!-- Accounts List -->
            <div>
                @forelse ($accounts as $account)
                    <!-- A single account row -->
                    <div class="flex items-center py-4 border-b border-gray-200 last:border-b-0">

                        <!-- Column 1: Icon -->
                        <div class="flex-shrink-0 mr-4">
                            <span class="text-blue-500">
                                <!-- A suitable "wallet" or "bank" icon -->
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3"></path>
                                </svg>
                            </span>
                        </div>

                        <!-- Column 2: Account Details (Grows to fill space) -->
                        <div class="flex-grow">
                            <p class="font-bold text-gray-900">{{ $account->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ number_format($account->balance, 2) }} {{ $account->currency }}
                            </p>
                        </div>

                        <!-- Column 3: Converted Balance and Actions (Fixed Width) -->
                        <div class="text-right flex-shrink-0 ml-4">
                            <div>
                                <p class="font-bold text-lg {{ (isset($account->converted_balance) ? $account->converted_balance : $account->balance) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format(isset($account->converted_balance) ? $account->converted_balance : $account->balance, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 uppercase">{{ $displayCurrency }}</p>
                            </div>
                            <div class="mt-1 space-x-3">
                                <a href="{{ route('accounts.edit', $account) }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600">Edit</a>
                                <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure? This will delete all associated transactions.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-10 px-4 border-2 border-dashed rounded-lg">
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No accounts</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first account.</p>
                        <div class="mt-4">
                            <a href="{{ route('accounts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-gray-70 uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Add Account
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

                <!-- Recent Transactions Section (Right) -->
<div class="lg:col-span-3">
    <div class="bg-white overflow-hidden shadow-lg rounded-xl">
        <div class="p-6">
            <!-- Card Header -->
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-xl font-semibold text-gray-700">Recent Transactions</h3>
                <a href="{{ route('transactions.create') }}" class="text-indigo-500 hover:text-indigo-700" title="Add New Transaction">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path></svg>
                </a>
            </div>
            
            <!-- Transactions List -->
            <div>
                @forelse ($recentTransactions as $transaction)
                    <!-- A single transaction row -->
                    <div class="flex items-center py-4 border-b border-gray-200 last:border-b-0">

                        <!-- Column 1: Icon -->
                        <div class="flex-shrink-0 mr-4">
                            <span class="{{ $transaction->type === 'income' ? 'text-green-500' : 'text-red-500' }}">
                                @if($transaction->type === 'income')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                @else
                                     <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
                                @endif
                            </span>
                        </div>

                        <!-- Column 2: Transaction Details (Grows to fill space) -->
                        <div class="flex-grow">
                            <p class="font-bold text-gray-900">{{ $transaction->description }}</p>
                            <p class="text-sm text-gray-500">{{ $transaction->account->name }} · {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}</p>
                        </div>

                        <!-- Column 3: Amount and Actions (Fixed Width) -->
                        <div class="text-right flex-shrink-0 ml-4">
                            <div>
                                <p class="font-bold text-lg {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format(isset($transaction->converted_amount) ? $transaction->converted_amount : $transaction->amount, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 uppercase">{{ $displayCurrency }}</p>
                            </div>
                            <div class="mt-1 space-x-3">
                                <a href="{{ route('transactions.edit', $transaction) }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600">Edit</a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-10 px-4 border-2 border-dashed rounded-lg">
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No transactions</h3>
                        <p class="mt-1 text-sm text-gray-500">Add your first transaction to see it here.</p>
                        <div class="mt-4">
                            <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-gray-70 uppercase tracking-widest hover:bg-blue-500 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                Add Transaction
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</x-app-layout>