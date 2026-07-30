@extends('layouts.dashboard')

@section('title', 'مدیریت پرداخت‌های حساب پی')

@section('content')
    <div class="container-fluid">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        مدیریت پرداخت‌های حساب پی
                    </h2>
                </div>
            </div>
        </div>

        <!-- Cards with statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">تعداد کل پرداخت‌ها</h3>
                        <p class="display-5">{{ number_format($totalPayments) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">پرداخت‌های موفق</h3>
                        <p class="display-5">{{ number_format($completedPayments) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">پرداخت‌های در انتظار</h3>
                        <p class="display-5">{{ number_format($pendingPayments) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">مجموع مبالغ پرداخت شده</h3>
                        <p class="display-5">{{ number_format($totalAmount) }} <small>افغانی</small></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and filters -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard.admin.hesabpay.index') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">جستجو</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="کد پیگیری، شماره تراکنش یا شماره تلفن...">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">وضعیت</label>
                            <select class="form-select" name="status">
                                <option value="">همه</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>ناموفق</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">از تاریخ</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">تا تاریخ</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">اعمال فیلتر</button>
                            <a href="{{ route('dashboard.admin.hesabpay.index') }}" class="btn btn-secondary">حذف فیلترها</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payments list -->
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap">
                    <thead>
                        <tr>
                            <th>شناسه</th>
                            <th>کاربر</th>
                            <th>کد پیگیری</th>
                            <th>شماره تراکنش</th>
                            <th>مبلغ (افغانی)</th>
                            <th>شماره تلفن</th>
                            <th>وضعیت</th>
                            <th>تاریخ ایجاد</th>
                            <th>تاریخ تکمیل</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>
                                    @if($payment->user)
                                        <a href="{{ route('dashboard.admin.users.show', $payment->user->id) }}">
                                            {{ $payment->user->name }} {{ $payment->user->lastname }}
                                        </a>
                                    @else
                                        کاربر نامشخص
                                    @endif
                                </td>
                                <td>{{ $payment->tracking_code }}</td>
                                <td>{{ $payment->transaction_id ?? 'ندارد' }}</td>
                                <td>{{ number_format($payment->amount) }}</td>
                                <td>{{ $payment->phone_number }}</td>
                                <td>
                                    @if($payment->status == 'completed')
                                        <span class="badge bg-success">تکمیل شده</span>
                                    @elseif($payment->status == 'pending')
                                        <span class="badge bg-warning">در انتظار</span>
                                    @elseif($payment->status == 'failed')
                                        <span class="badge bg-danger">ناموفق</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $payment->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $payment->completed_at ? $payment->completed_at->format('Y-m-d H:i') : '-' }}</td>
                                <td>
                                    <a href="{{ route('dashboard.admin.hesabpay.show', $payment->id) }}" class="btn btn-sm btn-primary">مشاهده</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">هیچ پرداختی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                {{ $payments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection 