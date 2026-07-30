@extends('layouts.admin')

@section('title', 'جزئیات کوپن تخفیف')
@section('page-title', 'جزئیات کوپن تخفیف')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">جزئیات کوپن تخفیف: {{ $coupon->code }}</h5>
        <div>
            <a href="{{ route('dashboard.admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-warning">
                <i class="ri-edit-line"></i>
                ویرایش کوپن
            </a>
            <a href="{{ route('dashboard.admin.coupons.index') }}" class="btn btn-sm btn-primary">
                <i class="ri-arrow-right-line"></i>
                بازگشت به لیست کوپن‌ها
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">کد تخفیف:</span>
                    <span class="fw-bold">{{ $coupon->code }}</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">نوع تخفیف:</span>
                    <span>
                        @if ($coupon->type == 'percentage')
                            <span class="badge bg-info">درصدی</span>
                        @else
                            <span class="badge bg-primary">مقدار ثابت</span>
                        @endif
                    </span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">مقدار تخفیف:</span>
                    <span>
                        @if ($coupon->type == 'percentage')
                            {{ $coupon->value }}%
                        @else
                            {{ number_format($coupon->value) }} افغانی
                        @endif
                    </span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">حداقل مبلغ سفارش:</span>
                    <span>
                        @if ($coupon->min_order_amount)
                            {{ number_format($coupon->min_order_amount) }} افغانی
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">وضعیت:</span>
                    <span>
                        @if ($coupon->is_active)
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-danger">غیرفعال</span>
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">حداکثر تعداد استفاده:</span>
                    <span>{{ $coupon->max_uses ?? 'نامحدود' }}</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">حداکثر استفاده هر کاربر:</span>
                    <span>{{ $coupon->max_uses_per_user ?? 'نامحدود' }}</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تعداد استفاده فعلی:</span>
                    <span>{{ $coupon->times_used ?? 0 }}</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تاریخ شروع:</span>
                    <span>
                        @if ($coupon->starts_at)
                            {{ $coupon->starts_at->format('Y/m/d') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تاریخ انقضا:</span>
                    <span>
                        @if ($coupon->expires_at)
                            {{ $coupon->expires_at->format('Y/m/d') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تاریخ ایجاد:</span>
                    <span>{{ $coupon->created_at->format('Y/m/d H:i') }}</span>
                </div>
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">آخرین بروزرسانی:</span>
                    <span>{{ $coupon->updated_at->format('Y/m/d H:i') }}</span>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCouponModal">
                <i class="ri-delete-bin-line me-1"></i> حذف کوپن
            </button>
        </div>
    </div>
</div>

<!-- Usage History -->
@if(count($usageHistory) > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">تاریخچه استفاده</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>کاربر</th>
                        <th>شماره سفارش</th>
                        <th>مبلغ سفارش</th>
                        <th>میزان تخفیف</th>
                        <th>تاریخ استفاده</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usageHistory as $usage)
                    <tr>
                        <td>
                            <a href="{{ route('dashboard.admin.users.show', $usage->user_id) }}">
                                {{ $usage->user->name }} {{ $usage->user->lastname }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('dashboard.admin.orders.show', $usage->order_id) }}">
                                {{ $usage->order_id }}
                            </a>
                        </td>
                        <td>{{ number_format($usage->order_total) }} افغانی</td>
                        <td>{{ number_format($usage->discount_amount) }} افغانی</td>
                        <td>{{ $usage->created_at->format('Y/m/d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Delete Modal -->
<div class="modal fade" id="deleteCouponModal" tabindex="-1" aria-labelledby="deleteCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCouponModalLabel">تایید حذف کوپن</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                آیا از حذف این کوپن تخفیف اطمینان دارید؟<br>
                <strong>{{ $coupon->code }}</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <form action="{{ route('dashboard.admin.coupons.destroy', $coupon->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 