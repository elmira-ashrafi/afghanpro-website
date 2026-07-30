@extends('layouts.admin')

@section('title', 'مدیریت کوپن‌های تخفیف')
@section('page-title', 'مدیریت کوپن‌های تخفیف')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">لیست کوپن‌های تخفیف</h5>
        <a href="{{ route('dashboard.admin.coupons.create') }}" class="btn btn-sm btn-primary">
            <i class="ri-add-line"></i>
            افزودن کوپن جدید
        </a>
    </div>
    
    <!-- Search Form -->
    <div class="card-body border-bottom">
        <form action="{{ route('dashboard.admin.coupons.index') }}" method="GET" class="row align-items-end">
            <div class="col-md-3 mb-3">
                <label class="form-label">کد کوپن</label>
                <input type="text" name="search" class="form-control" placeholder="جستجوی کد کوپن" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">وضعیت</label>
                <select name="is_active" class="form-select">
                    <option value="">همه</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">نوع تخفیف</label>
                <select name="discount_type" class="form-select">
                    <option value="">همه</option>
                    <option value="percentage" {{ request('discount_type') == 'percentage' ? 'selected' : '' }}>درصدی</option>
                    <option value="fixed" {{ request('discount_type') == 'fixed' ? 'selected' : '' }}>مقدار ثابت</option>
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
                        <th>کد</th>
                        <th>نوع</th>
                        <th>مقدار تخفیف</th>
                        <th>حداقل مبلغ سفارش</th>
                        <th>حداکثر تعداد استفاده</th>
                        <th>تاریخ شروع</th>
                        <th>تاریخ انقضا</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td><strong>{{ $coupon->code }}</strong></td>
                        <td>
                            @if ($coupon->discount_type == 'percentage')
                                <span class="badge bg-info">درصدی</span>
                            @else
                                <span class="badge bg-primary">مقدار ثابت</span>
                            @endif
                        </td>
                        <td>
                            @if ($coupon->discount_type == 'percentage')
                                {{ $coupon->discount_value }}%
                            @else
                                {{ number_format($coupon->discount_value) }} افغانی
                            @endif
                        </td>
                        <td>
                            @if ($coupon->min_order_amount)
                                {{ number_format($coupon->min_order_amount) }} افغانی
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($coupon->usage_limit)
                                {{ $coupon->usage_limit }}
                            @else
                                <span class="text-muted">نامحدود</span>
                            @endif
                        </td>
                        <td>
                            @if ($coupon->starts_at)
                                {{ $coupon->starts_at->format('Y/m/d') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($coupon->expires_at)
                                {{ $coupon->expires_at->format('Y/m/d') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($coupon->is_active)
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-danger">غیرفعال</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard.admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-warning">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $coupon->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $coupon->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $coupon->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $coupon->id }}">تایید حذف کوپن</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            آیا از حذف این کوپن اطمینان دارید؟<br>
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">هیچ کوپن تخفیفی یافت نشد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $coupons->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection 