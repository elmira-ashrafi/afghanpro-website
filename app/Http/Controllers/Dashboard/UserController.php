<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
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
        
        // Apply verification status filter
        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }
        
        // Apply role filter
        if ($request->filled('role')) {
            switch ($request->role) {
                case 'admin':
                    $query->where('is_admin', true);
                    break;
                case 'support':
                    $query->where('is_support', true)
                          ->where('is_admin', false);
                    break;
                case 'user':
                    $query->where('is_admin', false)
                          ->where('is_support', false);
                    break;
            }
        }
        
        $users = $query->paginate(15);
        
        return view('dashboard.admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('dashboard.admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        try {
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
                'is_support' => 'nullable|boolean',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $validated['is_admin'] = $request->has('is_admin') ? true : false;
            $validated['is_support'] = $request->has('is_support') ? true : false;
            $validated['is_verified'] = $request->has('is_verified') ? true : false;

            $user = User::create($validated);

            // Create wallets for the user
            $user->afghanWallet()->create(['balance' => 0]);
            $user->dollarWallet()->create(['balance' => 0]);

            return redirect()->route('dashboard.admin.users.index')
                ->with('success', 'کاربر با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            Log::error('User creation error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'خطا در ایجاد کاربر: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'telegram_number' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'is_admin' => 'nullable|boolean',
            'is_support' => 'nullable|boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->has('is_admin') ? true : false;
        $validated['is_support'] = $request->has('is_support') ? true : false;
        $validated['is_verified'] = $request->has('is_verified') ? true : false;

        $user->update($validated);

        return redirect()->route('dashboard.admin.users.index')
            ->with('success', 'اطلاعات کاربر با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard.admin.users.index')
            ->with('success', 'کاربر با موفقیت حذف شد.');
    }
} 