@extends('layouts.admin')

@section('title', 'مدیریت برداشت‌های نقدی نمایندگی')

@section('page-title', 'مدیریت برداشت‌های نقدی از نمایندگی')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">لیست درخواست‌های برداشت نقدی از نمایندگی</h5>
                <a href="{{ route('dashboard.admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="ri-dashboard-line me-1"></i>بازگشت به داشبورد
                </a>
            </div>
            
            <!-- Search Form -->
            <div class="card-body border-bottom">
                <form action="{{ route('dashboard.admin.agency-withdrawals') }}" method="GET" class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">وضعیت</label>
                        <select name="status" class="form-select">
                            <option value="">همه</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>تایید شده</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>رد شده</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">نوع ارز</label>
                        <select name="currency_type" class="form-select">
                            <option value="">همه</option>
                            <option value="USD" {{ request('currency_type') == 'USD' ? 'selected' : '' }}>دلار</option>
                            <option value="AFN" {{ request('currency_type') == 'AFN' ? 'selected' : '' }}>افغانی</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">جستجو</label>
                        <input type="text" name="search" class="form-control" placeholder="شماره پیگیری، نام کاربر یا نمایندگی" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-filter-line me-1"></i>فیلتر
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">تاریخ</th>
                                <th scope="col">شماره پیگیری</th>
                                <th scope="col">کاربر</th>
                                <th scope="col">مبلغ</th>
                                <th scope="col">ارز</th>
                                <th scope="col">نمایندگی</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->created_at->format('Y/m/d H:i') }}</td>
                                    <td>{{ $withdrawal->tracking_number }}</td>
                                    <td>{{ $withdrawal->user->name }} {{ $withdrawal->user->lastname }}</td>
                                    <td>{{ number_format($withdrawal->amount) }}</td>
                                    <td>{{ $withdrawal->currency_type == 'AFN' ? 'افغانی' : 'دلار' }}</td>
                                    <td>{{ $withdrawal->agency->name }}</td>
                                    <td>
                                        @if($withdrawal->status == 'completed')
                                            <span class="badge bg-success">تکمیل شده</span>
                                        @elseif($withdrawal->status == 'pending')
                                            <span class="badge bg-warning">در انتظار تایید</span>
                                        @elseif($withdrawal->status == 'approved')
                                            <span class="badge bg-info">تایید شده</span>
                                        @elseif($withdrawal->status == 'rejected')
                                            <span class="badge bg-danger">رد شده</span>
                                        @elseif($withdrawal->status == 'cancelled')
                                            <span class="badge bg-secondary">لغو شده</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.admin.agency-withdrawals.show', $withdrawal->id) }}" class="btn btn-sm btn-primary">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="ri-history-line d-block mb-3" style="font-size: 2rem;"></i>
                                        <p class="mb-0">هیچ درخواست برداشتی ثبت نشده است</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $withdrawals->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 