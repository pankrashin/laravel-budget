<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AccountController extends Controller
{
    use AuthorizesRequests;

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'required|string|size:3',
            'initial_balance' => 'nullable|numeric|min:0',
        ]);

        $account = $request->user()->accounts()->create([
            'name' => $request->name,
            'currency' => strtoupper($request->currency),
        ]);

        // Create initial balance transaction if provided
        if ($request->filled('initial_balance') && $request->initial_balance > 0) {
            $account->transactions()->create([
                'type' => 'income',
                'amount' => $request->initial_balance,
                'description' => 'Initial balance',
                'transaction_date' => now(),
            ]);
            
            return redirect()->route('dashboard')->with('success', 'Account created successfully with initial balance of ' . number_format($request->initial_balance, 2) . ' ' . strtoupper($request->currency) . '!');
        }

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(Account $account)
    {
        $this->authorize('update', $account);

        return view('accounts.edit', compact('account'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, Account $account)
    {
        $this->authorize('update', $account);

        $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'required|string|size:3',
            'balance_adjustment' => 'nullable|numeric',
        ]);

        $account->update([
            'name' => $request->name,
            'currency' => strtoupper($request->currency),
        ]);

        // Create balance adjustment transaction if provided
        if ($request->filled('balance_adjustment') && $request->balance_adjustment != 0) {
            $account->transactions()->create([
                'type' => $request->balance_adjustment > 0 ? 'income' : 'expense',
                'amount' => abs($request->balance_adjustment),
                'description' => $request->balance_adjustment > 0 ? 'Balance adjustment (added)' : 'Balance adjustment (subtracted)',
                'transaction_date' => now(),
            ]);
            
            $adjustmentText = $request->balance_adjustment > 0 ? 'added' : 'subtracted';
            $adjustmentAmount = number_format(abs($request->balance_adjustment), 2);
            
            return redirect()->route('dashboard')->with('success', 'Account updated successfully! Balance ' . $adjustmentText . ': ' . $adjustmentAmount . ' ' . $account->currency);
        }

        return redirect()->route('dashboard')->with('success', 'Account updated successfully!');
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        $account->delete();

        return redirect()->route('dashboard')->with('success', 'Account deleted successfully!');
    }
}