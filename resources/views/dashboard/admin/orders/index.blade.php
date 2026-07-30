@extends('layouts.admin')

@section('title', 'مدیریت سفارش‌ها')
@section('page-title', 'مدیریت سفارش‌ها')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">لیست سفارش‌ها</h5>
    </div>
    
    <!-- Search Form -->
    <div class="card-body border-bottom">
        <form action="{{ route('dashboard.admin.orders.index') }}" method="GET" class="row align-items-end">
            <div class="col-md-3 mb-3">
                <label class="form-label">جستجوی کاربر/کد رهگیری</label>
                <input type="text" name="search" class="form-control" placeholder="نام، نام خانوادگی یا کد رهگیری" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">وضعیت</label>
                <select name="status" class="form-select">
                    <option value="">همه</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار تایید</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>در حال پردازش</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>ارسال شده</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تحویل داده شده</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">روش پرداخت</label>
                <select name="payment_method" class="form-select">
                    <option value="">همه</option>
                    <option value="wallet" {{ request('payment_method') == 'wallet' ? 'selected' : '' }}>کیف پول</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>پرداخت نقدی</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ri-filter-line me-1"></i>فیلتر
                </button>
            </div>
        </form>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>کاربر</th>
                        <th>مبلغ کل</th>
                        <th>روش پرداخت</th>
                        <th>وضعیت</th>
                        <th>کد رهگیری</th>
                        <th>تاریخ ثبت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            <a href="{{ route('dashboard.admin.users.show', $order->user_id) }}">
                                {{ $order->user->name }} {{ $order->user->lastname }}
                            </a>
                        </td>
                        <td>{{ number_format($order->total_amount) }} افغانی</td>
                        <td>
                            @if ($order->payment_method == 'wallet')
                                <span class="badge bg-primary">کیف پول</span>
                            @elseif ($order->payment_method == 'cash')
                                <span class="badge bg-success">پرداخت نقدی</span>
                            @else
                                <span class="badge bg-info">{{ $order->payment_method }}</span>
                            @endif
                        </td>
                        <td>
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-warning">در انتظار تایید</span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-info">در حال پردازش</span>
                                    @break
                                @case('shipped')
                                    <span class="badge bg-primary">ارسال شده</span>
                                    @break
                                @case('delivered')
                                    <span class="badge bg-success">تحویل داده شده</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success">تکمیل شده</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">لغو شده</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">نامشخص</span>
                            @endswitch
                        </td>
                        <td>
                            @if ($order->order_number)
                                {{ $order->order_number }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('Y/m/d H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard.admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="ri-eye-line"></i>
                                </a>
                                @if ($order->status != 'delivered' && $order->status != 'cancelled' && $order->status != 'completed')
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $order->id }}">
                                    <i class="ri-close-circle-line"></i>
                                </button>
                                @endif
                            </div>
                            
                            <!-- Cancel Modal -->
                            @if ($order->status != 'delivered' && $order->status != 'cancelled' && $order->status != 'completed')
                            <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $order->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cancelModalLabel{{ $order->id }}">تایید لغو سفارش</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            آیا از لغو این سفارش اطمینان دارید؟<br>
                                            <strong>سفارش شماره: {{ $order->order_number }}</strong>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                            <form action="{{ route('dashboard.admin.orders.cancel', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger">لغو سفارش</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">هیچ سفارشی یافت نشد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection 