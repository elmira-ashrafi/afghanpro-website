<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->paginate(15);
        return view('dashboard.admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('dashboard.admin.coupons.create');
    }

    /**
     * Store a newly created coupon in database.
     */
    public function store(Request $request)
    {
        try {
            // Log incoming request
            Log::info('Coupon creation request:', $request->all());
            
            // Convert checkbox value to boolean properly 
            $is_active = $request->has('is_active') ? true : false;
            
            $validated = $request->validate([
                'code' => 'nullable|string|max:30|unique:coupons,code',
                'discount_type' => 'required|in:percentage,fixed',
                'discount_value' => 'required|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:0',
                'max_uses_per_user' => 'nullable|integer|min:0',
                'min_order_amount' => 'nullable|numeric|min:0',
                'max_discount_amount' => 'nullable|numeric|min:0',
                'starts_at' => 'nullable|date',
                'expires_at' => 'nullable|date|after_or_equal:starts_at',
            ]);
    
            // Generate a random code if not provided
            if (empty($validated['code'])) {
                $validated['code'] = strtoupper(Str::random(8));
            }
    
            // Add the is_active field to validated data
            $validated['is_active'] = $is_active;
            
            Log::info('Validated data:', $validated);
            
            $coupon = Coupon::create($validated);
            
            Log::info('Coupon created with ID: ' . $coupon->id);
    
            return redirect()->route('dashboard.admin.coupons.index')
                ->with('success', 'کوپن تخفیف با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            Log::error('Error creating coupon: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'خطا در ایجاد کوپن: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified coupon.
     */
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('dashboard.admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('dashboard.admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in database.
     */
    public function update(Request $request, $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            
            // Log incoming request
            Log::info('Coupon update request:', $request->all());
            
            // Convert checkbox value to boolean properly
            $is_active = $request->has('is_active') ? true : false;
    
            $validated = $request->validate([
                'code' => 'required|string|max:30|unique:coupons,code,' . $coupon->id,
                'discount_type' => 'required|in:percentage,fixed',
                'discount_value' => 'required|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:0',
                'max_uses_per_user' => 'nullable|integer|min:0',
                'min_order_amount' => 'nullable|numeric|min:0',
                'max_discount_amount' => 'nullable|numeric|min:0',
                'starts_at' => 'nullable|date',
                'expires_at' => 'nullable|date|after_or_equal:starts_at',
            ]);
    
            // Add the is_active field to validated data
            $validated['is_active'] = $is_active;
            
            Log::info('Validated data for update:', $validated);
    
            $coupon->update($validated);
            
            Log::info('Coupon updated with ID: ' . $coupon->id);
    
            return redirect()->route('dashboard.admin.coupons.index')
                ->with('success', 'کوپن تخفیف با موفقیت بروزرسانی شد.');
        } catch (\Exception $e) {
            Log::error('Error updating coupon: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'خطا در بروزرسانی کوپن: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified coupon from database.
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('dashboard.admin.coupons.index')
            ->with('success', 'کوپن تخفیف با موفقیت حذف شد.');
    }
} 