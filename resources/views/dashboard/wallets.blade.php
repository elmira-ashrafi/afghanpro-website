@extends('layouts.dashboard')

@section('title', 'کیف پول‌ها')
@section('page-title', 'کیف پول‌های من')

@section('content')
<div class="container-fluid">
    <!-- Wallet Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-0">
                    <div class="wallet-card-modern afn p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="wallet-icon-large me-3 bg-success-subtle text-success rounded-circle">
                                <i class="ri-money-afghani-circle-line"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 small">آخرین بروزرسانی: {{ $afghanWallet->updated_at->format('Y/m/d H:i') }}</p>
                                <h5 class="mb-0">کیف پول افغانی</h5>
                            </div>
                        </div>
                        <h2 class="balance display-5 mb-0 d-flex align-items-center">
                            {{ number_format($afghanWallet->balance) }}
                            <span class="fs-6 ms-2 text-muted">افغانی</span>
                        </h2>
                        <div class="wallet-actions mt-4">
                            <a href="{{ route('dashboard.wallets.deposit.afghani') }}" class="btn btn-sm btn-outline-success me-2">
                                <i class="ri-bank-card-line me-1"></i>افزایش موجودی
                            </a>
                            <a href="{{ route('dashboard.wallets.withdraw.agency') }}" class="btn btn-sm btn-outline-success">
                                <i class="ri-money-withdraw-line me-1"></i>برداشت
                            </a>
                            <a href="{{ route('dashboard.wallets.deposit.history') }}" class="btn btn-sm btn-link">
                                <i class="ri-history-line me-1"></i>تاریخچه شارژها
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-0">
                    <div class="wallet-card-modern usd p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="wallet-icon-large me-3 bg-primary-subtle text-primary rounded-circle">
                                <i class="ri-money-dollar-circle-line"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 small">آخرین بروزرسانی: {{ $dollarWallet->updated_at->format('Y/m/d H:i') }}</p>
                                <h5 class="mb-0">کیف پول دلاری</h5>
                            </div>
                        </div>
                        <h2 class="balance display-5 mb-0 d-flex align-items-center">
                            {{ number_format($dollarWallet->balance, 2) }}
                            <span class="fs-6 ms-2 text-muted">دلار</span>
                        </h2>
                        <div class="wallet-actions mt-4">
                            <a href="{{ route('dashboard.wallets.deposit.dollar') }}" class="btn btn-sm btn-outline-primary me-2">
                                <i class="ri-bank-card-line me-1"></i>افزایش موجودی
                            </a>
                            <a href="{{ route('dashboard.wallets.withdraw.agency') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-money-withdraw-line me-1"></i>برداشت
                            </a>
                            <a href="{{ route('dashboard.wallets.deposit.history') }}" class="btn btn-sm btn-link">
                                <i class="ri-history-line me-1"></i>تاریخچه شارژها
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="ri-tools-line me-2 text-primary"></i>عملیات سریع
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-2">
                        
                        <div class="col-md-4 col-sm-6">
                            <div class="dropdown h-100">
                                <button class="btn btn-light d-flex align-items-center justify-content-center p-3 w-100 h-100 rounded-3 dropdown-toggle" type="button" id="depositDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-bank-card-line me-2 text-primary fs-4"></i>
                                    <span>افزایش موجودی</span>
                                </button>
                                <ul class="dropdown-menu w-100 text-end" aria-labelledby="depositDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard.wallets.deposit.afghani') }}">
                                            <i class="ri-money-afghani-circle-line me-2"></i>شارژ کیف پول افغانی
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard.wallets.deposit.dollar') }}">
                                            <i class="ri-money-dollar-circle-line me-2"></i>شارژ کیف پول دلاری
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard.wallets.deposit.history') }}">
                                            <i class="ri-history-line me-2"></i>تاریخچه شارژها
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-6">
                            <a href="{{ route('dashboard.wallets.withdraw.agency') }}" class="btn btn-light d-flex align-items-center justify-content-center p-3 w-100 h-100 rounded-3">
                                <i class="ri-store-2-line me-2 text-primary fs-4"></i>
                                <span>برداشت نقدی از نمایندگی</span>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions History -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="walletsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-4 py-2 rounded-pill" id="afn-tab" data-bs-toggle="tab" data-bs-target="#afn-content" type="button" role="tab" aria-controls="afn-content" aria-selected="true">
                                <i class="ri-money-afghani-circle-line me-1"></i>کیف پول افغانی
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-2 rounded-pill" id="usd-tab" data-bs-toggle="tab" data-bs-target="#usd-content" type="button" role="tab" aria-controls="usd-content" aria-selected="false">
                                <i class="ri-money-dollar-circle-line me-1"></i>کیف پول دلاری
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="walletsTabContent">
                    <!-- Afghan Wallet Transactions -->
                    <div class="tab-pane fade show active" id="afn-content" role="tabpanel" aria-labelledby="afn-tab">
                        <div class="card-header bg-white py-3">
                            <div class="row align-items-center">
                                <div class="col-lg-6 mb-3 mb-lg-0">
                                    <h5 class="mb-0 d-flex align-items-center">
                                        <i class="ri-history-line me-2 text-success"></i>
                                        تاریخچه تراکنش‌های افغانی
                                    </h5>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex justify-content-lg-end gap-2">
                                        <a href="{{ route('dashboard.wallets.deposit.history') }}" class="btn btn-sm btn-outline-success">
                                            <i class="ri-history-line me-1"></i>
                                            تاریخچه شارژها
                                        </a>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="جستجو در تراکنش‌ها...">
                                            <button class="btn btn-outline-success" type="button">
                                                <i class="ri-search-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle transaction-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">تاریخ</th>
                                            <th scope="col">شرح</th>
                                            <th scope="col">نوع تراکنش</th>
                                            <th scope="col">مبلغ</th>
                                            <th scope="col">وضعیت</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($afghanTransactions as $transaction)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $transaction->created_at->format('Y/m/d') }}</span>
                                                        <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-wrap" style="max-width: 250px;">
                                                        {{ $transaction->description }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($transaction->transaction_type == 'deposit')
                                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">واریز</span>
                                                    @elseif($transaction->transaction_type == 'withdraw')
                                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">برداشت</span>
                                                    @elseif($transaction->transaction_type == 'transfer')
                                                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">انتقال</span>
                                                    @elseif($transaction->transaction_type == 'conversion')
                                                        <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2">تبدیل ارز</span>
                                                    @elseif($transaction->transaction_type == 'refund')
                                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">برگشت وجه</span>
                                                    @else
                                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">{{ $transaction->transaction_type }}</span>
                                                    @endif
                                                </td>
                                                <td class="{{ $transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund' ? 'text-success fw-bold' : ($transaction->transaction_type == 'withdraw' ? 'text-danger fw-bold' : '') }}">
                                                    {{ $transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund' ? '+' : ($transaction->transaction_type == 'withdraw' ? '-' : '') }}
                                                    {{ number_format($transaction->amount) }} افغانی
                                                </td>
                                                <td>
                                                    @if($transaction->status == 'completed')
                                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">تکمیل شده</span>
                                                    @elseif($transaction->status == 'pending')
                                                        <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2">در انتظار</span>
                                                    @elseif($transaction->status == 'failed')
                                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">ناموفق</span>
                                                    @elseif($transaction->status == 'cancelled')
                                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">لغو شده</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon bg-light mb-3">
                                                            <i class="ri-history-line"></i>
                                                        </div>
                                                        <h6>هیچ تراکنشی یافت نشد</h6>
                                                        <p class="text-muted mb-0">هنوز هیچ تراکنشی در کیف پول افغانی شما ثبت نشده است.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white py-3">
                            {{ $afghanTransactions->appends(['dollar_page' => $dollarTransactions->currentPage()])->links() }}
                        </div>
                    </div>
                    
                    <!-- Dollar Wallet Transactions -->
                    <div class="tab-pane fade" id="usd-content" role="tabpanel" aria-labelledby="usd-tab">
                        <div class="card-header bg-white py-3">
                            <div class="row align-items-center">
                                <div class="col-lg-6 mb-3 mb-lg-0">
                                    <h5 class="mb-0 d-flex align-items-center">
                                        <i class="ri-history-line me-2 text-primary"></i>
                                        تاریخچه تراکنش‌های دلاری
                                    </h5>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex justify-content-lg-end gap-2">
                                        <a href="{{ route('dashboard.wallets.deposit.history') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-history-line me-1"></i>
                                            تاریخچه شارژها
                                        </a>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="جستجو در تراکنش‌ها...">
                                            <button class="btn btn-outline-primary" type="button">
                                                <i class="ri-search-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle transaction-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">تاریخ</th>
                                            <th scope="col">شرح</th>
                                            <th scope="col">نوع تراکنش</th>
                                            <th scope="col">مبلغ</th>
                                            <th scope="col">وضعیت</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dollarTransactions as $transaction)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $transaction->created_at->format('Y/m/d') }}</span>
                                                        <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-wrap" style="max-width: 250px;">
                                                        {{ $transaction->description }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($transaction->transaction_type == 'deposit')
                                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">واریز</span>
                                                    @elseif($transaction->transaction_type == 'withdraw')
                                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">برداشت</span>
                                                    @elseif($transaction->transaction_type == 'transfer')
                                                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">انتقال</span>
                                                    @elseif($transaction->transaction_type == 'conversion')
                                                        <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2">تبدیل ارز</span>
                                                    @elseif($transaction->transaction_type == 'refund')
                                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">برگشت وجه</span>
                                                    @else
                                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">{{ $transaction->transaction_type }}</span>
                                                    @endif
                                                </td>
                                                <td class="{{ $transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund' ? 'text-success fw-bold' : ($transaction->transaction_type == 'withdraw' ? 'text-danger fw-bold' : '') }}">
                                                    {{ $transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund' ? '+' : ($transaction->transaction_type == 'withdraw' ? '-' : '') }}
                                                    {{ number_format($transaction->amount, 2) }} دلار
                                                </td>
                                                <td>
                                                    @if($transaction->status == 'completed')
                                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">تکمیل شده</span>
                                                    @elseif($transaction->status == 'pending')
                                                        <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2">در انتظار</span>
                                                    @elseif($transaction->status == 'failed')
                                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">ناموفق</span>
                                                    @elseif($transaction->status == 'cancelled')
                                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">لغو شده</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon bg-light mb-3">
                                                            <i class="ri-history-line"></i>
                                                        </div>
                                                        <h6>هیچ تراکنشی یافت نشد</h6>
                                                        <p class="text-muted mb-0">هنوز هیچ تراکنشی در کیف پول دلاری شما ثبت نشده است.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white py-3">
                            {{ $dollarTransactions->appends(['afghan_page' => $afghanTransactions->currentPage()])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .wallet-icon-large {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }
    
    .wallet-card-modern {
        height: 100%;
        border-radius: 0.75rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .wallet-card-modern.afn {
        background-color: #effaf5;
        color: #198754;
    }
    
    .wallet-card-modern.usd {
        background-color: #eff6ff;
        color: #0d6efd;
    }
    
    .empty-state {
        padding: 2rem 1rem;
    }
    
    .empty-state-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin: 0 auto;
        border-radius: 50%;
    }
    
    .nav-pills .nav-link.active {
        background-color: #f8f9fa;
        color: #0d6efd;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .nav-pills .nav-link {
        color: #6c757d;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .transaction-table th, 
    .transaction-table td {
        vertical-align: middle;
        padding: 1rem;
    }
    
    .badge {
        font-weight: 500;
    }
    
    @media (max-width: 767.98px) {
        .transaction-table {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
        
        .transaction-table thead {
            display: none;
        }
        
        .transaction-table tbody tr {
            display: flex;
            flex-direction: column;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .transaction-table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border: none;
        }
        
        .transaction-table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 0.5rem;
        }
        
        .wallet-card-modern {
            padding: 1.5rem !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add data-label attributes to table cells for responsive view
        const tables = document.querySelectorAll('.transaction-table');
        tables.forEach(table => {
            const headerCells = table.querySelectorAll('thead th');
            const headerTexts = Array.from(headerCells).map(cell => cell.textContent.trim());
            
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (index < headerTexts.length) {
                        cell.setAttribute('data-label', headerTexts[index]);
                    }
                });
            });
        });
    });
</script>
@endpush 