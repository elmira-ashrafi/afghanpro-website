<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agency;
use App\Models\User;
use App\Models\DollarWallet;
use App\Models\Transaction;
use App\Models\SystemSetting;
use App\Models\AgencyWithdrawal;
use App\Models\AfghanWallet;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Pending requests counts
        $pendingHesabPayPayments = \App\Models\HesabPayPayment::where('status', 'pending')->count();
        
        // User counts
        $totalUsers = User::count();
        $totalAgencies = Agency::count();
        
        return view('dashboard.admin.dashboard', compact(
            'user',
            'pendingHesabPayPayments',
            'totalUsers',
            'totalAgencies'
        ));
    }
    
    
    
    /**
     * Display system settings
     */
    public function settings()
    {
        $user = Auth::user();
        
        // Fetch all system settings
        $settings = SystemSetting::all()->pluck('value', 'key');
        
        return view('dashboard.admin.settings', compact('user', 'settings'));
    }
    
    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'domestic_transfer_fee' => 'required|numeric|min:0|max:100',
            'neighboring_transfer_fee' => 'required|numeric|min:0|max:100',
            'international_transfer_fee' => 'required|numeric|min:0|max:100',
            'trade_withdrawal_fee' => 'required|numeric|min:0|max:100',
            'dollar_to_afghani_fee' => 'required|numeric|min:0|max:100',
            'afghani_to_dollar_fee' => 'required|numeric|min:0|max:100',
            'min_transfer_amount' => 'required|numeric|min:0',
            'max_transfer_amount' => 'required|numeric|min:0|gt:min_transfer_amount',
            'usd_to_afn_rate' => 'required|numeric|min:0.01',
            'afn_to_usd_rate' => 'required|numeric|min:0.000001',
        ]);
        
        foreach ($validated as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return redirect()->route('dashboard.admin.settings')->with('success', 'تنظیمات با موفقیت بروزرسانی شد.');
    }
    
    /**
     * Display all agencies
     */
    public function agencies(Request $request)
    {
        $user = Auth::user();
        $query = Agency::query();
        
        // Apply search filter for name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        // Apply province filter
        if ($request->filled('province')) {
            $query->where('province', 'like', "%{$request->province}%");
        }
        
        // Apply city filter
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }
        
        // Apply active status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        $agencies = $query->orderBy('name')->paginate(15);
        
        return view('dashboard.admin.agencies.index', compact('user', 'agencies'));
    }
    
    /**
     * Show form to create a new agency
     */
    public function createAgency()
    {
        $user = Auth::user();
        
        return view('dashboard.admin.agencies.create', compact('user'));
    }
    
    /**
     * Store a new agency
     */
    public function storeAgency(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'working_hours' => 'nullable|array',
        ]);
        
        $agency = Agency::create($validated);
        
        return redirect()->route('dashboard.admin.agencies.index')
            ->with('success', 'نمایندگی با موفقیت ایجاد شد.');
    }
    
    /**
     * Show form to edit an agency
     */
    public function editAgency($id)
    {
        $user = Auth::user();
        $agency = Agency::findOrFail($id);
        
        return view('dashboard.admin.agencies.edit', compact('user', 'agency'));
    }
    
    /**
     * Update an agency
     */
    public function updateAgency(Request $request, $id)
    {
        $agency = Agency::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'working_hours' => 'nullable|array',
        ]);
        
        $agency->update($validated);
        
        return redirect()->route('dashboard.admin.agencies.index')
            ->with('success', 'نمایندگی با موفقیت بروزرسانی شد.');
    }
    
    /**
     * Delete an agency
     */
    public function destroyAgency($id)
    {
        $agency = Agency::findOrFail($id);
        
        // Check if agency can be deleted
        $hasRelatedOrders = $agency->orders()->exists();
        $hasRelatedTradeRequests = $agency->tradeAccountRequests()->exists();
        $hasRelatedMoneyTransfers = $agency->moneyTransfers()->exists();
        
        if ($hasRelatedOrders || $hasRelatedTradeRequests || $hasRelatedMoneyTransfers) {
            return redirect()->route('dashboard.admin.agencies.index')
                ->with('error', 'نمایندگی قابل حذف نیست زیرا دارای اطلاعات مرتبط است.');
        }
        
        $agency->delete();
        
        return redirect()->route('dashboard.admin.agencies.index')
            ->with('success', 'نمایندگی با موفقیت حذف شد.');
    }
    
    /**
     * Display all agency withdrawals
     */
    public function agencyWithdrawals(Request $request)
    {
        $user = Auth::user();
        $query = AgencyWithdrawal::with(['user', 'agency']);
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply currency type filter
        if ($request->filled('currency_type')) {
            $query->where('currency_type', $request->currency_type);
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%");
                  })
                  ->orWhereHas('agency', function($agencyQuery) use ($search) {
                      $agencyQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $withdrawals = $query->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('dashboard.admin.agency-withdrawals.index', compact('user', 'withdrawals'));
    }
    
    /**
     * Display agency withdrawal details
     */
    public function showAgencyWithdrawal($id)
    {
        $user = Auth::user();
        $withdrawal = AgencyWithdrawal::with(['user', 'agency', 'transaction'])
            ->findOrFail($id);
        
        return view('dashboard.admin.agency-withdrawals.show', compact('user', 'withdrawal'));
    }
    
    /**
     * Update agency withdrawal status
     */
    public function updateAgencyWithdrawalStatus(Request $request, $id)
    {
        $withdrawal = AgencyWithdrawal::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,completed,rejected,cancelled',
        ]);
        
        $withdrawal->status = $validated['status'];
        
        if ($validated['status'] == 'completed') {
            $withdrawal->completed_at = now();
        } else if ($validated['status'] == 'rejected' || $validated['status'] == 'cancelled') {
            // If rejecting or cancelling, refund the user if the transaction exists
            if (!$withdrawal->transaction) {
                return redirect()->back()->with('error', 'تراکنش مربوط به این درخواست یافت نشد.');
            }
            
            // Return funds to appropriate wallet
            if ($withdrawal->wallet_type == 'afghan_wallet') {
                $wallet = AfghanWallet::where('user_id', $withdrawal->user_id)->first();
                $wallet->deposit($withdrawal->amount);
                
                // Create refund transaction
                $transaction = new Transaction();
                $transaction->user_id = $withdrawal->user_id;
                $transaction->amount = $withdrawal->amount;
                $transaction->currency_type = 'AFN';
                $transaction->transaction_type = 'refund';
                $transaction->status = 'completed';
                $transaction->description = 'برگشت وجه برداشت نقدی - ' . $withdrawal->tracking_number;
                $transaction->save();
            } else {
                $wallet = DollarWallet::where('user_id', $withdrawal->user_id)->first();
                $wallet->deposit($withdrawal->amount);
                
                // Create refund transaction
                $transaction = new Transaction();
                $transaction->user_id = $withdrawal->user_id;
                $transaction->amount = $withdrawal->amount;
                $transaction->currency_type = 'USD';
                $transaction->transaction_type = 'refund';
                $transaction->status = 'completed';
                $transaction->description = 'برگشت وجه برداشت نقدی - ' . $withdrawal->tracking_number;
                $transaction->save();
            }
        }
        
        $withdrawal->support_user_id = Auth::id();
        $withdrawal->save();
        
        return redirect()->route('dashboard.admin.agency-withdrawals')
            ->with('success', 'وضعیت درخواست برداشت نقدی با موفقیت بروزرسانی شد.');
    }
} 