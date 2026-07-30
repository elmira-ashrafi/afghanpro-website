@extends('layouts.dashboard')

@section('title', 'تاریخچه برداشت‌های نقدی')
@section('page-title', 'تاریخچه برداشت‌های نقدی از نمایندگی')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white p-3 p-md-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ri-history-line me-2 text-primary"></i>تاریخچه برداشت‌های نقدی</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('dashboard.wallets.withdraw.agency') }}" class="btn btn-primary btn-sm rounded-pill">
                        <i class="ri-add-line me-1"></i>درخواست جدید
                    </a>
                    <a href="{{ route('dashboard.wallets') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="ri-wallet-3-line me-1"></i>کیف پول‌ها
                    </a>
                </div>
            </div>
            
            <!-- Desktop view -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">تاریخ</th>
                            <th scope="col">شماره پیگیری</th>
                            <th scope="col">مبلغ</th>
                            <th scope="col">نمایندگی</th>
                            <th scope="col">وضعیت</th>
                            <th scope="col" class="text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->created_at->format('Y/m/d H:i') }}</td>
                                <td class="text-nowrap">{{ $withdrawal->tracking_number }}</td>
                                <td class="text-nowrap">
                                    {{ number_format($withdrawal->amount) }} 
                                    <small class="text-secondary">{{ $withdrawal->currency_type == 'AFN' ? 'افغانی' : 'دلار' }}</small>
                                </td>
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
                                <td class="text-center">
                                    <a href="{{ route('dashboard.wallets.withdraw.agency.show', $withdrawal->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="ri-eye-line me-1"></i>جزئیات
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ri-history-line mb-3 text-secondary" style="font-size: 2.5rem;"></i>
                                        <h6 class="fw-normal text-secondary">هیچ برداشت نقدی ثبت نشده است</h6>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile view -->
            <div class="d-md-none">
                <ul class="list-group list-group-flush">
                    @forelse($withdrawals as $withdrawal)
                        <li class="list-group-item p-3">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="fw-medium text-nowrap">{{ $withdrawal->tracking_number }}</div>
                                    <span>
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
                                    </span>
                                </div>
                                
                                <div class="d-flex justify-content-between gap-2">
                                    <div class="text-secondary small">
                                        <i class="ri-building-line me-1"></i>{{ $withdrawal->agency->name }}
                                    </div>
                                    <div class="fw-bold">
                                        {{ number_format($withdrawal->amount) }} 
                                        <small>{{ $withdrawal->currency_type == 'AFN' ? 'افغانی' : 'دلار' }}</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
                                    <div class="text-secondary small">
                                        <i class="ri-calendar-line me-1"></i>{{ $withdrawal->created_at->format('Y/m/d H:i') }}
                                    </div>
                                    <a href="{{ route('dashboard.wallets.withdraw.agency.show', $withdrawal->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="ri-eye-line me-1"></i>جزئیات
                                    </a>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item py-5 text-center">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ri-history-line mb-3 text-secondary" style="font-size: 2.5rem;"></i>
                                <h6 class="fw-normal text-secondary">هیچ برداشت نقدی ثبت نشده است</h6>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
            
            @if($withdrawals->hasPages())
                <div class="card-footer bg-white p-3">
                    {{ $withdrawals->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 