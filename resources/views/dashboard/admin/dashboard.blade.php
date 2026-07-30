@extends('layouts.admin')

@section('title', 'پنل مدیریت')

@section('content')
<div class="main-header d-flex justify-content-between align-items-center">
    <div>
        <h5 class="mb-1">پنل مدیریت</h5>
        <p class="text-muted mb-0">خلاصه وضعیت سیستم و درخواست‌های در انتظار</p>
    </div>
    <div>
        <span class="badge bg-primary fs-6">امروز: {{ \Carbon\Carbon::now()->format('Y/m/d') }}</span>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="stats-card red">
            <div>
                <h6 class="text-white mb-2">پرداخت‌های حساب پی</h6>
                <div class="stat-value">{{ $pendingHesabPayPayments }}</div>
                <div>در انتظار بررسی</div>
            </div>
            <i class="ri-bank-card-line stat-icon"></i>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="stats-card blue">
            <div>
                <h6 class="text-white mb-2">کل کاربران</h6>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div>کاربران ثبت شده</div>
            </div>
            <i class="ri-user-line stat-icon"></i>
        </div>
    </div>
</div>


<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">پرداخت‌های اخیر حساب پی</h5>
                <a href="{{ route('dashboard.admin.hesabpay.index') }}" class="btn btn-sm btn-primary">
                    مشاهده همه
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>کاربر</th>
                                <th>مبلغ</th>
                                <th>شماره تلفن</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\HesabPayPayment::orderBy('created_at', 'desc')->take(5)->get() as $payment)
                            <tr>
                                <td>{{ $payment->user->name }} {{ $payment->user->lastname }}</td>
                                <td>{{ number_format($payment->amount) }} افغانی</td>
                                <td>{{ $payment->phone_number }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status == 'pending' ? 'warning' : ($payment->status == 'completed' ? 'success' : 'danger') }}">
                                        {{ $payment->status == 'pending' ? 'در انتظار' : ($payment->status == 'completed' ? 'تکمیل شده' : 'ناموفق') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.admin.hesabpay.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 