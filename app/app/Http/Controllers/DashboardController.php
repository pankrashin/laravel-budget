<?php

namespace App\Http\Controllers;

use App\Services\CurrencyConverterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     *
     * @param Request $request
     * @param CurrencyConverterService $converter
     * @return \Illuminate\View\View
     */
    public function index(Request $request, CurrencyConverterService $converter)
    {
        $user = Auth::user();

        $supportedCurrencies = $converter->getSupportedCurrencies();
        
        $firstAccount = $user->accounts()->first();
        $userBaseCurrency = $firstAccount ? $firstAccount->currency : 'USD';
        
        $requestedCurrency = in_array(strtoupper($request->query('currency')), $supportedCurrencies) ? strtoupper($request->query('currency')) : $userBaseCurrency;
        $displayCurrency = in_array($requestedCurrency, $supportedCurrencies) ? $requestedCurrency : 'USD';

        $accounts = $user->accounts()->with('transactions')->get();
        $recentTransactions = $user->transactions()->with('account')->latest()->get();
        
        $netWorth = 0;
        
        if ($accounts->isNotEmpty()) {
            foreach ($accounts as $account) {
                $account->converted_balance = $converter->convert($account->balance, $account->currency, $displayCurrency);
                $netWorth += $account->converted_balance;
            }
        }
        
        if ($recentTransactions->isNotEmpty()) {
            foreach ($recentTransactions as $transaction) {
                $transaction->converted_amount = $converter->convert($transaction->amount, $transaction->account->currency, $displayCurrency);
            }
        }

        $dailyTotals = [];
        for ($i = 0; $i < 30; $i++) {
            $dateKey = now()->subDays($i)->format('Y-m-d');
            $dailyTotals[$dateKey] = 0;
        }

        $thirtyDaysAgo = now()->subDays(29)->startOfDay();
        $expenses = $user->transactions()
            ->where('type', 'expense')
            ->where('transaction_date', '>=', $thirtyDaysAgo)
            ->with('account')
            ->get();

        foreach ($expenses as $expense) {
            $dateKey = Carbon::parse($expense->transaction_date)->format('Y-m-d');

            if (isset($dailyTotals[$dateKey])) {
                $convertedAmount = $converter->convert($expense->amount, $expense->account->currency, $displayCurrency);
                $dailyTotals[$dateKey] += $convertedAmount;
            }
        }
        
        // Step D: Sort the daily totals by date (the array key) to ensure the chart is in chronological order.
        ksort($dailyTotals);

        // Step E: Prepare the final, simple, indexed arrays that Chart.js needs.
        $chartLabels = [];
        $chartData = [];
        foreach ($dailyTotals as $date => $total) {
            $chartLabels[] = Carbon::parse($date)->format('M d'); // e.g., "Oct 27"
            $chartData[] = round($total, 2);
        }
        
        // --- 4. RETURN THE VIEW WITH ALL DATA ---
        return view('dashboard', [
            'accounts' => $accounts,
            'recentTransactions' => $recentTransactions,
            'netWorth' => $netWorth,
            'displayCurrency' => $displayCurrency,
            'supportedCurrencies' => $supportedCurrencies,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}