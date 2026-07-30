<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AfghanWallet;
use App\Models\DollarWallet;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\Agency;
use App\Models\User;
use App\Models\SystemSetting;
use App\Models\AgencyWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard home page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user wallets
        $afghanWallet = AfghanWallet::where('user_id', $user->id)->first();
        $dollarWallet = DollarWallet::where('user_id', $user->id)->first();
        
        // Recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        return view('dashboard.index', compact(
            'user',
            'afghanWallet',
            'dollarWallet',
            'recentTransactions',
            'recentOrders'
        ));
    }
    
    /**
     * Wallets page
     */
    public function wallets()
    {
        $user = Auth::user();
        
        // Get user wallets
        $afghanWallet = AfghanWallet::where('user_id', $user->id)->first();
        $dollarWallet = DollarWallet::where('user_id', $user->id)->first();
        
        // Get wallet transactions with pagination
        $afghanTransactions = Transaction::where('user_id', $user->id)
            ->where('currency_type', 'AFN')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'afghan_page');
            
        $dollarTransactions = Transaction::where('user_id', $user->id)
            ->where('currency_type', 'USD')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'dollar_page');
        
        return view('dashboard.wallets', compact(
            'user',
            'afghanWallet',
            'dollarWallet',
            'afghanTransactions',
            'dollarTransactions'
        ));
    }
    
    /**
     * Show afghani wallet deposit form
     */
    public function depositAfghaniForm()
    {
        $user = Auth::user();
        $afghanWallet = AfghanWallet::where('user_id', $user->id)->first();
        $agencies = Agency::all();
        
        return view('dashboard.wallet-deposit.afghani', compact('user', 'afghanWallet', 'agencies'));
    }
    
    /**
     * Process afghani wallet deposit
     */
    public function depositAfghani(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:hesab_pay,agency_visit',
            'agency_id' => 'required_if:payment_method,agency_visit|nullable|exists:agencies,id',
            'phone_number' => 'required_if:payment_method,hesab_pay|nullable|string',
            'description' => 'nullable|string',
        ]);
        
        $trackingCode = 'AFN' . time() . rand(1000, 9999);
        
        // Create deposit request
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $validated['amount'];
        $transaction->currency_type = 'AFN';
        $transaction->transaction_type = 'deposit';
        $transaction->status = 'pending';
        $transaction->description = 'درخواست افزایش موجودی کیف پول افغانی';
        if (!empty($validated['description'])) {
            $transaction->description .= ' - ' . $validated['description'];
        }
        $transaction->reference_id = $trackingCode;
        $transaction->reference_type = 'wallet_deposit';
        
        // If payment method is HesabPay, redirect to HesabPay
        if ($validated['payment_method'] == 'hesab_pay') {
            // Redirect to HesabPay controller
            return app()->make('App\Http\Controllers\HesabPayController')
                ->processPayment($request);
        }
        
        // For agency visit
        $transaction->save();
        
        // Show confirmation page
        return redirect()->route('dashboard.wallets')
            ->with('success', 'درخواست شارژ کیف پول افغانی ثبت شد. لطفا به نمایندگی مراجعه کنید.');
    }
    
    /**
     * Show dollar wallet deposit form
     */
    public function depositDollarForm()
    {
        $user = Auth::user();
        $dollarWallet = DollarWallet::where('user_id', $user->id)->first();
        $agencies = Agency::all();
        
        return view('dashboard.wallet-deposit.dollar', compact('user', 'dollarWallet', 'agencies'));
    }
    
    /**
     * Process dollar wallet deposit
     */
    public function depositDollar(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:agency_visit',
            'agency_id' => 'required|exists:agencies,id',
            'description' => 'nullable|string',
        ]);
        
        $trackingCode = 'USD' . time() . rand(1000, 9999);
        
        // Create deposit request
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $validated['amount'];
        $transaction->currency_type = 'USD';
        $transaction->transaction_type = 'deposit';
        $transaction->status = 'pending';
        $transaction->description = 'درخواست افزایش موجودی کیف پول دلاری';
        if (!empty($validated['description'])) {
            $transaction->description .= ' - ' . $validated['description'];
        }
        $transaction->reference_id = $trackingCode;
        $transaction->reference_type = 'wallet_deposit';
        $transaction->save();
        
        // For dollar deposit, only agency visit is allowed
        return redirect()->route('dashboard.wallets')
            ->with('success', 'درخواست شارژ کیف پول دلاری ثبت شد. لطفا به نمایندگی مراجعه کنید.');
    }
    
    /**
     * Profile page
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('dashboard.profile', compact('user'));
    }
    
    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'telegram_number' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
        ]);
        
        User::where('id', $user->id)->update($validated);
        
        return back()->with('success', 'اطلاعات پروفایل با موفقیت بروزرسانی شد.');
    }
    
    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'رمز عبور فعلی صحیح نیست.',
            ]);
        }
        
        User::where('id', $user->id)->update([
            'password' => Hash::make($validated['password'])
        ]);
        
        return back()->with('success', 'رمز عبور با موفقیت تغییر یافت.');
    }
    
    
    
    /**
     * Shop index page
     */
    public function shopIndex()
    {
        $user = Auth::user();
        
        return view('dashboard.shop.index', compact('user'));
    }
    
    /**
     * Shop product page
     */
    public function shopProduct($id)
    {
        $user = Auth::user();
        $afghanWallet = AfghanWallet::where('user_id', $user->id)->first();
        $dollarWallet = DollarWallet::where('user_id', $user->id)->first();
        
        // In a real application, we'd fetch the product from the database
        // For now, we'll just pretend we have a product
        $product = [
            'id' => $id,
            'name' => 'محصول نمونه',
            'description' => 'توضیحات محصول نمونه',
            'price' => 10,
            'currency' => 'dollar',
        ];
        
        return view('dashboard.shop.product', compact('user', 'afghanWallet', 'dollarWallet', 'product'));
    }
    
    
    /**
     * Show form for agency withdrawal
     */
    public function agencyWithdrawalForm()
    {
        $user = Auth::user();
        $afghanWallet = AfghanWallet::where('user_id', $user->id)->first();
        $dollarWallet = DollarWallet::where('user_id', $user->id)->first();
        
        // Get active agencies
        $agencies = Agency::where('is_active', true)->orderBy('name')->get();
        
        return view('dashboard.agency-withdrawal.create', compact('user', 'afghanWallet', 'dollarWallet', 'agencies'));
    }
    
    /**
     * Store agency withdrawal request
     */
    public function storeAgencyWithdrawal(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'currency_type' => 'required|in:AFN,USD',
            'wallet_type' => 'required|in:afghan_wallet,dollar_wallet',
            'agency_id' => 'required|exists:agencies,id',
            'description' => 'nullable|string',
        ]);
        
        // Check wallet balance
        if ($validated['wallet_type'] == 'afghan_wallet') {
            $wallet = AfghanWallet::where('user_id', $user->id)->first();
            if ($wallet->balance < $validated['amount']) {
                return back()->withErrors([
                    'amount' => 'موجودی کیف پول افغانی شما کافی نیست.'
                ])->withInput();
            }
        } else {
            $wallet = DollarWallet::where('user_id', $user->id)->first();
            if ($wallet->balance < $validated['amount']) {
                return back()->withErrors([
                    'amount' => 'موجودی کیف پول دلاری شما کافی نیست.'
                ])->withInput();
            }
        }
        
        // Create agency withdrawal request
        $withdrawal = new AgencyWithdrawal();
        $withdrawal->user_id = $user->id;
        $withdrawal->agency_id = $validated['agency_id'];
        $withdrawal->full_name = $validated['full_name'];
        $withdrawal->phone = $validated['phone'];
        $withdrawal->city = $validated['city'];
        $withdrawal->amount = $validated['amount'];
        $withdrawal->currency_type = $validated['currency_type'];
        $withdrawal->wallet_type = $validated['wallet_type'];
        $withdrawal->description = $validated['description'];
        $withdrawal->tracking_number = 'AW' . time() . rand(1000, 9999);
        $withdrawal->status = 'pending';
        $withdrawal->save();
        
        // Deduct from user's wallet
        if ($validated['wallet_type'] == 'afghan_wallet') {
            $wallet->withdraw($validated['amount']);
            $currencyType = 'AFN';
        } else {
            $wallet->withdraw($validated['amount']);
            $currencyType = 'USD';
        }
        
        // Create transaction record
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $validated['amount'];
        $transaction->currency_type = $currencyType;
        $transaction->transaction_type = 'withdraw';
        $transaction->status = 'completed';
        $transaction->description = 'برداشت نقدی از نمایندگی - ' . Agency::find($validated['agency_id'])->name;
        $transaction->reference_id = $withdrawal->id;
        $transaction->reference_type = AgencyWithdrawal::class;
        $transaction->save();
        
        return redirect()->route('dashboard.wallets.withdraw.agency.history')
            ->with('success', 'درخواست برداشت نقدی شما با موفقیت ثبت شد. شماره پیگیری: ' . $withdrawal->tracking_number);
    }
    
    /**
     * Show agency withdrawal history
     */
    public function agencyWithdrawalHistory()
    {
        $user = Auth::user();
        $withdrawals = AgencyWithdrawal::where('user_id', $user->id)
            ->with('agency')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('dashboard.agency-withdrawal.history', compact('user', 'withdrawals'));
    }
    
    /**
     * Show specific agency withdrawal details
     */
    public function showAgencyWithdrawal($id)
    {
        $user = Auth::user();
        $withdrawal = AgencyWithdrawal::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['agency', 'transaction'])
            ->firstOrFail();
        
        return view('dashboard.agency-withdrawal.show', compact('user', 'withdrawal'));
    }
    
    /**
     * Show wallet deposit history
     */
    public function walletDepositHistory()
    {
        $user = Auth::user();
        
        // Get deposit transactions with agency information
        $depositTransactions = Transaction::where('user_id', $user->id)
            ->where('transaction_type', 'deposit')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Get agency information for each transaction
        foreach ($depositTransactions as $transaction) {
            if ($transaction->reference_type == 'wallet_deposit') {
                // برای تراکنش‌های پرداخت افغانی یا دلاری، از فیلد agency_id استفاده می‌کنیم
                $agencyId = null;
                
                // استخراج agency_id از توضیحات یا اطلاعات دیگر تراکنش
                $parts = explode(' - ', $transaction->description);
                if (count($parts) >= 2) {
                    // ممکن است نام نمایندگی در بخش دوم توضیحات ذخیره شده باشد
                    $agencyName = trim(end($parts));
                    $agency = Agency::where('name', 'like', "%{$agencyName}%")->first();
                    if ($agency) {
                        $transaction->agency = $agency;
                    }
                }
                
                // اگر از روش بالا agency پیدا نشد، همه نمایندگی‌ها را بررسی می‌کنیم
                if (!isset($transaction->agency) || !$transaction->agency) {
                    // یافتن نمایندگی فعال برای نمایش
                    $transaction->agency = Agency::where('is_active', true)->first();
                }
            }
        }
        
        return view('dashboard.wallet-deposit.history', compact('user', 'depositTransactions'));
    }
}
