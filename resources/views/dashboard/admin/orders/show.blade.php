@extends('layouts.admin')

@section('title', 'جزئیات سفارش')
@section('page-title', 'جزئیات سفارش')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">جزئیات سفارش #{{ $order->id }}</h5>
        <div>
            <a href="{{ route('dashboard.admin.orders.index') }}" class="btn btn-sm btn-primary">
                <i class="ri-arrow-right-line"></i>
                بازگشت به لیست سفارش‌ها
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">شماره سفارش:</span>
                    <span>{{ $order->id }}</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">کاربر:</span>
                    <span>
                        <a href="{{ route('dashboard.admin.users.show', $order->user_id) }}">
                            {{ $order->user->name }} {{ $order->user->lastname }}
                        </a>
                    </span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">وضعیت سفارش:</span>
                    <span>
                        @switch($order->status)
                            @case('pending')
                                <span class="badge bg-warning">در انتظار بررسی</span>
                                @break
                            @case('processing')
                                <span class="badge bg-info">در حال پردازش</span>
                                @break
                            @case('completed')
                                <span class="badge bg-success">تکمیل شده</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger">لغو شده</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                        @endswitch
                    </span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تاریخ ثبت سفارش:</span>
                    <span>{{ $order->created_at->format('Y/m/d H:i') }}</span>
                </div>
                @if($order->status == 'completed')
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تاریخ تکمیل:</span>
                    <span>{{ $order->updated_at->format('Y/m/d H:i') }}</span>
                </div>
                @endif
                @if($order->status == 'cancelled')
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تاریخ لغو:</span>
                    <span>{{ $order->updated_at->format('Y/m/d H:i') }}</span>
                </div>
                @endif
            </div>
            
            <div class="col-md-6">
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">مبلغ کل سفارش:</span>
                    <span>{{ number_format($order->total_amount + $order->discount_amount) }} افغانی</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تخفیف:</span>
                    <span>{{ number_format($order->discount_amount) }} افغانی</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted font-weight-bold">مبلغ نهایی:</span>
                    <span class="fw-bold">{{ number_format($order->total_amount) }} افغانی</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">روش پرداخت:</span>
                    <span>
                        @if ($order->payment_method == 'afghan_wallet')
                            <span class="badge bg-primary">کیف پول افغانی</span>
                        @elseif ($order->payment_method == 'agency_visit')
                            <span class="badge bg-secondary">مراجعه به نمایندگی</span>
                        @elseif ($order->payment_method == 'wallet')
                            <span class="badge bg-primary">کیف پول</span>
                        @elseif ($order->payment_method == 'cash')
                            <span class="badge bg-success">پرداخت نقدی</span>
                        @else
                            <span class="badge bg-info">{{ $order->payment_method }}</span>
                        @endif
                    </span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">وضعیت پرداخت:</span>
                    <span>
                        @if($order->payment_status == 'pending')
                            <span class="badge bg-warning">در انتظار پرداخت</span>
                        @elseif($order->payment_status == 'paid')
                            <span class="badge bg-success">پرداخت شده</span>
                        @elseif($order->payment_status == 'failed')
                            <span class="badge bg-danger">ناموفق</span>
                        @else
                            <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                        @endif
                    </span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">کد تخفیف استفاده شده:</span>
                    <span>{{ $order->coupon_code ?? 'بدون کد تخفیف' }}</span>
                </div>
                @if($order->agency_id && $order->agency)
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">نمایندگی:</span>
                    <span>{{ $order->agency->name }} - {{ $order->agency->address ?? '' }}</span>
                </div>
                @endif
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">کد رهگیری:</span>
                    <span>{{ $order->order_number ?? '-' }}</span>
                </div>
            </div>
        </div>
        
        @if($order->status != 'cancelled')
        <hr class="my-4">
        
        <form action="{{ route('dashboard.admin.orders.update', $order->id) }}" method="POST" class="mb-4">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label">تغییر وضعیت سفارش</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>در حال پردازش</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">وضعیت پرداخت</label>
                        <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>در انتظار پرداخت</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>ناموفق</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="notes" class="form-label">یادداشت</label>
                        <textarea class="form-control rich-textarea @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4" style="min-height: 100px; resize: vertical;">{{ old('notes', $order->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="update_button" class="form-label">&nbsp;</label>
                        <button type="submit" id="update_button" class="btn btn-primary d-block w-100">بروزرسانی سفارش</button>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">اقلام سفارش</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>تصویر</th>
                        <th>محصول</th>
                        <th>قیمت واحد</th>
                        <th>تعداد</th>
                        <th>قیمت کل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                    <tr>
                        <td>
                            @if($item->product && $item->product->thumbnail)
                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->name }}" width="50" height="50" class="rounded">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="ri-image-line text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-medium">{{ $item->name }}</div>
                            @if(!empty($item->attributes))
                                <small class="text-muted">
                                    @php
                                        $attributes = is_array($item->attributes) ? $item->attributes : json_decode($item->attributes, true);
                                    @endphp
                                    @if(is_array($attributes))
                                        @foreach($attributes as $key => $value)
                                            {{ $key }}: {{ $value }}{{ !$loop->last ? ' / ' : '' }}
                                        @endforeach
                                    @endif
                                </small>
                            @elseif($item->product)
                                <small class="text-muted">{{ optional($item->product->category)->name ?? 'بدون دسته‌بندی' }}</small>
                            @endif
                        </td>
                        <td>{{ number_format($item->price) }} افغانی</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->total) }} افغانی</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="mb-3">
                                <i class="ri-information-line fs-3 text-muted"></i>
                            </div>
                            <h6>هیچ محصولی در این سفارش وجود ندارد</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">جمع کل:</td>
                        <td>{{ number_format($order->total_amount + $order->discount_amount) }} افغانی</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td colspan="4" class="text-end">تخفیف:</td>
                        <td class="text-danger">{{ number_format($order->discount_amount) }} افغانی-</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">مبلغ نهایی:</td>
                        <td class="fw-bold">{{ number_format($order->total_amount) }} افغانی</td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($order->status != 'cancelled')
<div class="d-flex justify-content-end mt-4">
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
        <i class="ri-close-circle-line me-1"></i> لغو سفارش
    </button>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">تایید لغو سفارش</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                آیا از لغو این سفارش اطمینان دارید؟<br>
                <strong>سفارش شماره: {{ $order->order_number }}</strong><br>
                <small class="text-muted">در صورت لغو سفارش، مبلغ پرداخت شده به کیف پول کاربر برگشت داده خواهد شد.</small>
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

<style>
.rich-textarea {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    padding: 0.5rem;
    background-color: #fff;
    font-family: inherit;
    line-height: 1.5;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.rich-textarea:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endsection 