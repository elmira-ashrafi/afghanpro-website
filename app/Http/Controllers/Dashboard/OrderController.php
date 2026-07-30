<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\AfghanWallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user']);
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('dashboard.admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        return view('dashboard.admin.orders.show', compact('order'));
    }

    /**
     * Update the specified order status.
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $order->status = $validated['status'];
        $order->payment_status = $validated['payment_status'];
        
        if (isset($validated['notes'])) {
            $order->notes = $validated['notes'];
        }
        
        $order->save();
        
        return redirect()->route('dashboard.admin.orders.show', $order->id)
            ->with('success', 'سفارش با موفقیت بروزرسانی شد.');
    }
    
    /**
     * Cancel an order.
     */
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status == 'delivered') {
            return redirect()->back()->with('error', 'سفارش‌های تحویل داده شده قابل لغو نیستند.');
        }
        
        try {
            DB::beginTransaction();
            
            // Check if order was paid using Afghan wallet and issue refund
            if ($order->payment_method == 'afghan_wallet' && $order->payment_status == 'paid') {
                $user = User::findOrFail($order->user_id);
                $afghanWallet = AfghanWallet::where('user_id', $user->id)->first();
                
                if (!$afghanWallet) {
                    throw new \Exception('کیف پول افغانی کاربر یافت نشد.');
                }
                
                // Return the money to user's wallet
                $refundAmount = $order->total_amount;
                $success = $afghanWallet->deposit($refundAmount);
                
                if (!$success) {
                    throw new \Exception('خطا در بازگرداندن مبلغ به کیف پول کاربر.');
                }
                
                // Create a transaction record for the refund
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'afghan_wallet_id' => $afghanWallet->id,
                    'amount' => $refundAmount,
                    'currency_type' => 'AFN',
                    'transaction_type' => 'refund',
                    'status' => 'completed',
                    'description' => 'بازگشت وجه سفارش #' . $order->order_number . ' به کیف پول',
                    'reference_type' => 'App\Models\Order',
                    'reference_id' => $order->id,
                ]);
                
                Log::info('Order cancelled and refunded', [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'refund_amount' => $refundAmount,
                    'transaction_id' => $transaction->id
                ]);
            }
            
            // Update order status
            $order->status = 'cancelled';
            $order->save();
            
            DB::commit();
            
            return redirect()->route('dashboard.admin.orders.index')
                ->with('success', 'سفارش با موفقیت لغو شد و در صورت پرداخت با کیف پول  مبلغ به کیف پول کاربر برگشت داده خواهد شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling order: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'خطا در لغو سفارش: ' . $e->getMessage());
        }
    }
    
    /**
     * Get orders by user.
     */
    public function userOrders($userId)
    {
        $user = User::findOrFail($userId);
        $orders = Order::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(15);
        
        return view('dashboard.admin.orders.user', compact('orders', 'user'));
    }
} 