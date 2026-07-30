<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SupportController extends Controller
{
    /**
     * Display a listing of support staff.
     */
    public function index(Request $request)
    {
        $query = User::where('is_support', true);
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Apply admin role filter
        if ($request->filled('is_admin')) {
            $query->where('is_admin', $request->is_admin);
        }
        
        $supporters = $query->paginate(15);
        
        return view('dashboard.admin.supporters.index', compact('supporters'));
    }

    /**
     * Show the form for creating a new support staff.
     */
    public function create()
    {
        return view('dashboard.admin.supporters.create');
    }

    /**
     * Store a newly created support staff in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telegram_number' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'is_admin' => 'nullable|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_support'] = true;
        $validated['is_admin'] = $request->has('is_admin');
        $validated['is_verified'] = true;

        $user = User::create($validated);

        // Create wallets for the support user
        $user->afghanWallet()->create(['balance' => 0]);
        $user->dollarWallet()->create(['balance' => 0]);

        return redirect()->route('dashboard.admin.supporters.index')
            ->with('success', 'کارمند پشتیبانی با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified support staff.
     */
    public function show($id)
    {
        $supporter = User::where('is_support', true)->findOrFail($id);
        
        // Initialize stats - these should be calculated based on your application logic
        // For now, initializing with zeros to prevent undefined variable errors
        $tradeDeposits = 0;
        $tradeWithdrawals = 0;
        $moneyTransfers = 0;
        
        return view('dashboard.admin.supporters.show', compact('supporter', 'tradeDeposits', 'tradeWithdrawals', 'moneyTransfers'));
    }

    /**
     * Show the form for editing the specified support staff.
     */
    public function edit($id)
    {
        $supporter = User::where('is_support', true)->findOrFail($id);
        return view('dashboard.admin.supporters.edit', compact('supporter'));
    }

    /**
     * Update the specified support staff in storage.
     */
    public function update(Request $request, $id)
    {
        $supporter = User::where('is_support', true)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($supporter->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($supporter->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'telegram_number' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'is_admin' => 'nullable|boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->has('is_admin');

        $supporter->update($validated);

        return redirect()->route('dashboard.admin.supporters.index')
            ->with('success', 'اطلاعات کارمند پشتیبانی با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified support staff from storage.
     */
    public function destroy($id)
    {
        $supporter = User::where('is_support', true)->findOrFail($id);
        
        // Remove support status instead of deleting
        $supporter->update(['is_support' => false]);

        return redirect()->route('dashboard.admin.supporters.index')
            ->with('success', 'کارمند پشتیبانی با موفقیت حذف شد.');
    }
} 