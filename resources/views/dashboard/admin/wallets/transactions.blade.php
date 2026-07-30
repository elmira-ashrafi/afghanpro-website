@extends('layouts.admin')

@section('title', 'تراکنش‌های کیف پول')
@section('page-title', 'تراکنش‌های کیف پول')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">تراکنش‌های کیف پول {{ $type == 'dollar' ? 'دلاری' : 'افغانی' }} کاربر: {{ $wallet->user->name }} {{ $wallet->user->lastname }}</h5>
        <div>
            <a href="{{ route('dashboard.admin.users.show', $wallet->user_id) }}" class="btn btn-sm btn-primary me-2">
                <i class="ri-user-line"></i>
                مشاهده پروفایل کاربر
            </a>
            <a href="{{ route('dashboard.admin.wallets.edit', ['id' => $wallet->id, 'type' => $type]) }}" class="btn btn-sm btn-warning">
                <i class="ri-money-dollar-circle-line"></i>
                ویرایش موجودی
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-4">
            <div class="d-flex">
                <div class="me-3">
                    <i class="ri-information-line fs-4"></i>
                </div>
                <div>
                    <h6 class="alert-heading mb-1">اطلاعات موجودی</h6>
                    <p class="mb-0">
                        موجودی فعلی: <strong>{{ $type == 'dollar' ? number_format($wallet->balance, 2) . ' دلار' : number_format($wallet->balance) . ' افغانی' }}</strong><br>
                        آخرین بروزرسانی: {{ $wallet->updated_at->format('Y/m/d H:i') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>شناسه</th>
                        <th>نوع</th>
                        <th>مبلغ</th>
                        <th>توضیحات</th>
                        <th>وضعیت</th>
                        <th>مرجع</th>
                        <th>تاریخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>
                            @if ($transaction->transaction_type == 'deposit')
                                <span class="badge bg-success">افزایش موجودی</span>
                            @elseif ($transaction->transaction_type == 'withdraw')
                                <span class="badge bg-danger">کاهش موجودی</span>
                            @elseif ($transaction->transaction_type == 'transfer')
                                <span class="badge bg-primary">انتقال</span>
                            @elseif ($transaction->transaction_type == 'order')
                                <span class="badge bg-info">سفارش</span>
                            @elseif ($transaction->transaction_type == 'conversion')
                                <span class="badge bg-warning">تبدیل ارز</span>
                            @elseif ($transaction->transaction_type == 'refund')
                                <span class="badge bg-success">برگشت وجه</span>
                            @else
                                <span class="badge bg-secondary">{{ $transaction->transaction_type }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund')
                                <span class="text-success">+{{ $type == 'dollar' ? number_format($transaction->amount, 2) . ' دلار' : number_format($transaction->amount) . ' افغانی' }}</span>
                            @elseif ($transaction->transaction_type == 'withdraw')
                                <span class="text-danger">-{{ $type == 'dollar' ? number_format($transaction->amount, 2) . ' دلار' : number_format($transaction->amount) . ' افغانی' }}</span>
                            @else
                                <span>{{ $type == 'dollar' ? number_format($transaction->amount, 2) . ' دلار' : number_format($transaction->amount) . ' افغانی' }}</span>
                            @endif
                        </td>
                        <td>{{ $transaction->description }}</td>
                        <td>
                            @if ($transaction->status == 'completed')
                                <span class="badge bg-success">انجام شده</span>
                            @elseif ($transaction->status == 'pending')
                                <span class="badge bg-warning">در انتظار</span>
                            @elseif ($transaction->status == 'failed')
                                <span class="badge bg-danger">ناموفق</span>
                            @elseif ($transaction->status == 'cancelled')
                                <span class="badge bg-danger">لغو شده</span>
                            @else
                                <span class="badge bg-secondary">{{ $transaction->status }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($transaction->reference_id && $transaction->reference_type)
                                @if (strpos($transaction->reference_type, 'Order') !== false)
                                    <a href="{{ route('dashboard.admin.orders.show', $transaction->reference_id) }}" class="btn btn-sm btn-info">
                                        <i class="ri-shopping-bag-line me-1"></i>سفارش
                                    </a>
                                @else
                                    <span class="badge bg-secondary">{{ $transaction->reference_id }}</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $transaction->created_at->format('Y/m/d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">هیچ تراکنشی یافت نشد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection 