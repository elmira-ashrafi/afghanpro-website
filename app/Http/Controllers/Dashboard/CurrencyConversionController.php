<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CurrencyConversionRequest;
use Illuminate\Support\Facades\Auth;

class CurrencyConversionController extends Controller
{
    /**
     * Display a listing of currency conversion requests.
     */
    public function index(Request $request)
    {
        $query = CurrencyConversionRequest::with('user');
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('lastname', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply conversion type filter
        if ($request->filled('conversion_type')) {
            if ($request->conversion_type === 'USD-to-AFN') {
                $query->where('from_currency', 'USD')
                      ->where('to_currency', 'AFN');
            } elseif ($request->conversion_type === 'AFN-to-USD') {
                $query->where('from_currency', 'AFN')
                      ->where('to_currency', 'USD');
            }
        }
        
        $conversionRequests = $query->orderBy('created_at', 'desc')
                                    ->paginate(15);
        
        return view('dashboard.admin.currency-conversions.index', compact('conversionRequests'));
    }

    /**
     * Display the specified conversion request.
     */
    public function show($id)
    {
        $conversionRequest = CurrencyConversionRequest::with('user', 'admin')->findOrFail($id);
        
        // Get related transactions
        $transactions = \App\Models\Transaction::where('reference_id', $conversionRequest->id)
            ->where('reference_type', CurrencyConversionRequest::class)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('dashboard.admin.currency-conversions.show', compact('conversionRequest', 'transactions'));
    }

    /**
     * Update the status of a conversion request.
     */
    public function update(Request $request, $id)
    {
        $conversionRequest = CurrencyConversionRequest::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'admin_notes' => 'nullable|string|max:500',
        ]);
        
        $oldStatus = $conversionRequest->status;
        $conversionRequest->status = $validated['status'];
        $conversionRequest->admin_notes = $validated['admin_notes'];
        $conversionRequest->admin_id = Auth::user()->id;
        
        if ($validated['status'] === 'approved' && $oldStatus !== 'approved') {
            $conversionRequest->approved_at = now();
            
            // Calculate conversion rate based on currency direction
            if ($conversionRequest->from_currency === 'USD' && $conversionRequest->to_currency === 'AFN') {
                $conversionRequest->conversion_rate = \App\Models\SystemSetting::getSetting('usd_to_afn_rate', 83.5);
            } else {
                $conversionRequest->conversion_rate = \App\Models\SystemSetting::getSetting('afn_to_usd_rate', 0.012);
            }
            
            // Calculate fee amount
            $feePercentage = $conversionRequest->fee_percentage ?? 
                ($conversionRequest->from_currency === 'USD' ? 
                    \App\Models\SystemSetting::getSetting('dollar_to_afghani_fee', 0.5) : 
                    \App\Models\SystemSetting::getSetting('afghani_to_dollar_fee', 1));
            
            $feeAmount = ($conversionRequest->amount * $feePercentage) / 100;
            $amountAfterFee = $conversionRequest->amount - $feeAmount;
            
            // Calculate converted amount
            $conversionRequest->converted_amount = $amountAfterFee * $conversionRequest->conversion_rate;
            $conversionRequest->save();
        }
        
        if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
            $conversionRequest->completed_at = now();
            $conversionRequest->save();
            
            // Process the conversion
            $result = $conversionRequest->processConversion();
            
            if (!$result) {
                return redirect()->back()->with('error', 'مشکلی در پردازش تبدیل ارز رخ داد. لطفا موجودی کاربر را بررسی کنید.');
            }
            
            return redirect()->route('dashboard.admin.currency-conversions.index')
                ->with('success', 'درخواست تبدیل ارز با موفقیت انجام شد و مبلغ به کیف پول مقصد اضافه شد.');
        } else {
            $conversionRequest->save();
            
            return redirect()->route('dashboard.admin.currency-conversions.index')
                ->with('success', 'وضعیت درخواست تبدیل ارز با موفقیت بروزرسانی شد.');
        }
    }
} 