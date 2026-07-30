<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HesabPayPayment;
use App\Models\Transaction;
use App\Models\AfghanWallet;

class HesabPayAdminController extends Controller
{
    /**
     * Display a listing of the HesabPay payments.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = HesabPayPayment::with('user');
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_code', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Get payments with pagination
        $payments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get payment statistics
        $totalPayments = HesabPayPayment::count();
        $completedPayments = HesabPayPayment::where('status', 'completed')->count();
        $pendingPayments = HesabPayPayment::where('status', 'pending')->count();
        $failedPayments = HesabPayPayment::where('status', 'failed')->count();
        
        $totalAmount = HesabPayPayment::where('status', 'completed')->sum('amount');
        
        return view('dashboard.admin.hesabpay.index', compact(
            'user',
            'payments',
            'totalPayments',
            'completedPayments',
            'pendingPayments',
            'failedPayments',
            'totalAmount'
        ));
    }
    
    /**
     * Display the specified HesabPay payment.
     */
    public function show($id)
    {
        $user = Auth::user();
        $payment = HesabPayPayment::with(['user', 'transaction'])->findOrFail($id);
        
        return view('dashboard.admin.hesabpay.show', compact('user', 'payment'));
    }
    
    /**
     * Mark a payment as completed manually.
     */
    public function markCompleted(Request $request, $id)
    {
        $payment = HesabPayPayment::findOrFail($id);
        
        // Check if payment is already completed
        if ($payment->status === 'completed') {
            return redirect()->route('dashboard.admin.hesabpay.show', $payment->id)
                ->with('error', 'پرداخت قبلا تکمیل شده است.');
        }
        
        // Update payment status
        $payment->status = 'completed';
        $payment->completed_at = now();
        $payment->save();
        
        // Find transaction or create one if it doesn't exist
        $transaction = Transaction::where('reference_id', $payment->tracking_code)
            ->where('reference_type', 'hesabpay_payment')
            ->first();
            
        if (!$transaction) {
            $transaction = new Transaction();
            $transaction->user_id = $payment->user_id;
            $transaction->amount = $payment->amount;
            $transaction->currency_type = 'AFN';
            $transaction->transaction_type = 'deposit';
            $transaction->reference_id = $payment->tracking_code;
            $transaction->reference_type = 'hesabpay_payment';
            $transaction->description = 'شارژ کیف پول افغانی از طریق حساب پی';
        }
        
        $transaction->status = 'completed';
        $transaction->save();
        
        // Add money to user's wallet
        $wallet = AfghanWallet::where('user_id', $payment->user_id)->first();
        if ($wallet) {
            $wallet->deposit($payment->amount);
        }
        
        return redirect()->route('dashboard.admin.hesabpay.show', $payment->id)
            ->with('success', 'وضعیت پرداخت با موفقیت به تکمیل شده تغییر یافت.');
    }
    
    /**
     * Mark a payment as failed manually.
     */
    public function markFailed(Request $request, $id)
    {
        $payment = HesabPayPayment::findOrFail($id);
        
        // Check if payment is already completed
        if ($payment->status === 'completed') {
            return redirect()->route('dashboard.admin.hesabpay.show', $payment->id)
                ->with('error', 'پرداخت قبلا تکمیل شده است و نمی‌تواند ناموفق علامت‌گذاری شود.');
        }
        
        // Update payment status
        $payment->status = 'failed';
        $payment->save();
        
        // Update transaction if exists
        $transaction = Transaction::where('reference_id', $payment->tracking_code)
            ->where('reference_type', 'hesabpay_payment')
            ->first();
            
        if ($transaction) {
            $transaction->status = 'failed';
            $transaction->save();
        }
        
        return redirect()->route('dashboard.admin.hesabpay.show', $payment->id)
            ->with('success', 'وضعیت پرداخت با موفقیت به ناموفق تغییر یافت.');
    }
} 