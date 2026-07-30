<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AfghanWallet;
use App\Models\DollarWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }
    
    /**
     * Process login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);
        
        // Check if user exists with phone number
        $user = User::where('phone', $credentials['phone'])->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'phone' => 'The provided credentials do not match our records.',
            ]);
        }
        
        Auth::login($user);
        
        return redirect()->intended(route('dashboard.index'));
    }
    
    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }
    
    /**
     * Process registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'telegram_number' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        // Create user
        $user = User::create($validated);
        
        // Create wallets for the user
        AfghanWallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
        
        DollarWallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
        
        // Log the user in
        Auth::login($user);
        
        return redirect()->route('dashboard.index')->with('success', 'Account created successfully!');
    }
    
    /**
     * Process logout
     */
    public function logout()
    {
        Session::flush();
        Auth::logout();
        
        return redirect()->route('home');
    }
    
    /**
     * Show the verification form for phone verification
     */
    public function showVerify()
    {
        return view('auth.verify');
    }
    
    /**
     * Process verification code
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);
        
        // In a real application, you would verify the code against a stored code
        // For now, we'll just accept any 6-digit code and mark the user as verified
        
        $user = Auth::user();
        $user->is_verified = true;
        $user->phone_verified_at = now();
        $user->save();
        
        return redirect()->route('dashboard.index')->with('success', 'Phone number verified successfully!');
    }
    
    /**
     * Resend the verification code
     */
    public function resendVerificationCode()
    {
        // In a real application, you would generate a new code and send it to the user's phone
        // For now, we'll just return with a success message
        
        return back()->with('resent', true);
    }
    
    /**
     * Show password reset request form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }
    
    /**
     * Process password reset request
     */
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
        ]);
        
        $user = User::where('phone', $validated['phone'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'phone' => 'We could not find a user with that phone number.',
            ]);
        }
        
        // In a real application, you would send a verification code to the user's phone
        // For now, we'll just redirect to a page where they can enter a "verification code"
        
        return redirect()->route('auth.reset-password-form', ['phone' => $validated['phone']])
            ->with('success', 'Verification code sent to your phone.');
    }
    
    /**
     * Show reset password form
     */
    public function showResetPassword(Request $request)
    {
        return view('auth.reset-password', ['phone' => $request->phone]);
    }
    
    /**
     * Process reset password
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'verification_code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::where('phone', $validated['phone'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'phone' => 'We could not find a user with that phone number.',
            ]);
        }
        
        // In a real application, you would verify the code
        // For now, we'll just update the password
        
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return redirect()->route('auth.login')->with('success', 'Password reset successfully.');
    }
}
