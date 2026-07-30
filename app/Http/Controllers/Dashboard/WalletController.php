<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AfghanWallet;
use App\Models\DollarWallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Display a listing of all wallets.
     */
    public function index(Request $request)
    {
        // Afghan wallets query
        $afghanQuery = AfghanWallet::with('user');
        
        // Apply Afghan wallet search
        if ($request->filled('afghan_query')) {
            $search = $request->afghan_query;
            $afghanQuery->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $afghanWallets = $afghanQuery->paginate(15, ['*'], 'afghan_page');
        
        // Dollar wallets query
        $dollarQuery = DollarWallet::with('user');
        
        // Apply Dollar wallet search
        if ($request->filled('dollar_query')) {
            $search = $request->dollar_query;
            $dollarQuery->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $dollarWallets = $dollarQuery->paginate(15, ['*'], 'dollar_page');
        
        return view('dashboard.admin.wallets.index', compact('afghanWallets', 'dollarWallets'));
    }

    /**
     * Show the form for adjusting a wallet balance.
     */
    public function edit($id, $type)
    {
        if ($type == 'afghan') {
            $wallet = AfghanWallet::findOrFail($id);
        } else {
            $wallet = DollarWallet::findOrFail($id);
        }
        
        return view('dashboard.admin.wallets.edit', compact('wallet', 'type'));
    }

    /**
     * Update the wallet balance.
     */
    public function update(Request $request, $id, $type)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'operation' => 'required|in:add,subtract',
            'description' => 'required|string|max:255',
        ]);

        if ($type == 'afghan') {
            $wallet = AfghanWallet::findOrFail($id);
            $currencyType = 'AFN';
        } else {
            $wallet = DollarWallet::findOrFail($id);
            $currencyType = 'USD';
        }

        $amount = abs($request->amount);
        
        if ($request->operation == 'add') {
            $wallet->balance += $amount;
            $transactionType = 'deposit';
        } else {
            if ($wallet->balance < $amount) {
                return redirect()->back()->with('error', 'موجودی کیف پول کافی نیست.');
            }
            $wallet->balance -= $amount;
            $transactionType = 'withdraw';
        }

        $wallet->save();

        // Record transaction
        Transaction::create([
            'user_id' => $wallet->user_id,
            'afghan_wallet_id' => $type == 'afghan' ? $wallet->id : null,
            'dollar_wallet_id' => $type == 'dollar' ? $wallet->id : null,
            'amount' => $amount,
            'currency_type' => $currencyType,
            'transaction_type' => $transactionType,
            'description' => $request->description,
            'status' => 'completed',
            'reference_id' => Auth::id(),
            'reference_type' => 'admin_adjustment',
        ]);

        return redirect()->route('dashboard.admin.wallets.index')
            ->with('success', 'موجودی کیف پول با موفقیت بروزرسانی شد.');
    }

    /**
     * Display the transaction history for a specific wallet.
     */
    public function transactions($id, $type)
    {
        if ($type == 'afghan') {
            $wallet = AfghanWallet::findOrFail($id);
            $transactions = Transaction::where('user_id', $wallet->user_id)
                ->where('currency_type', 'AFN')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $wallet = DollarWallet::findOrFail($id);
            $transactions = Transaction::where('user_id', $wallet->user_id)
                ->where('currency_type', 'USD')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        return view('dashboard.admin.wallets.transactions', compact('wallet', 'transactions', 'type'));
    }
} 