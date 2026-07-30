@extends('layouts.admin')

@section('title', 'مدیریت کیف پول‌ها')
@section('page-title', 'مدیریت کیف پول‌ها')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">کیف پول‌های افغانی</h5>
            </div>
            
            <!-- Afghan Wallet Search Form -->
            <div class="card-body border-bottom">
                <form action="{{ route('dashboard.admin.wallets.index') }}" method="GET" class="row align-items-end">
                    <input type="hidden" name="afghan_search" value="1">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">جستجوی کاربر</label>
                        <input type="text" name="afghan_query" class="form-control" placeholder="نام، نام خانوادگی یا ایمیل کاربر" value="{{ request('afghan_query') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-filter-line me-1"></i>جستجو
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
                                <th>موجودی (افغانی)</th>
                                <th>آخرین بروزرسانی</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($afghanWallets as $wallet)
                            <tr>
                                <td>{{ $wallet->id }}</td>
                                <td>
                                    <a href="{{ route('dashboard.admin.users.show', $wallet->user_id) }}">
                                        {{ $wallet->user->name }} {{ $wallet->user->lastname }}
                                    </a>
                                </td>
                                <td>{{ number_format($wallet->balance) }}</td>
                                <td>{{ $wallet->updated_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.admin.wallets.edit', ['id' => $wallet->id, 'type' => 'afghan']) }}" class="btn btn-sm btn-warning">
                                            <i class="ri-money-dollar-circle-line"></i> تغییر موجودی
                                        </a>
                                        <a href="{{ route('dashboard.admin.wallets.transactions', ['id' => $wallet->id, 'type' => 'afghan']) }}" class="btn btn-sm btn-info">
                                            <i class="ri-exchange-funds-line"></i> تراکنش‌ها
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">هیچ کیف پولی یافت نشد</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $afghanWallets->appends(request()->except(['dollar_query', 'page']))->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">کیف پول‌های دلاری</h5>
            </div>
            
            <!-- Dollar Wallet Search Form -->
            <div class="card-body border-bottom">
                <form action="{{ route('dashboard.admin.wallets.index') }}" method="GET" class="row align-items-end">
                    <input type="hidden" name="dollar_search" value="1">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">جستجوی کاربر</label>
                        <input type="text" name="dollar_query" class="form-control" placeholder="نام، نام خانوادگی یا ایمیل کاربر" value="{{ request('dollar_query') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-filter-line me-1"></i>جستجو
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
                                <th>موجودی (دلار)</th>
                                <th>آخرین بروزرسانی</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dollarWallets as $wallet)
                            <tr>
                                <td>{{ $wallet->id }}</td>
                                <td>
                                    <a href="{{ route('dashboard.admin.users.show', $wallet->user_id) }}">
                                        {{ $wallet->user->name }} {{ $wallet->user->lastname }}
                                    </a>
                                </td>
                                <td>{{ number_format($wallet->balance, 2) }}</td>
                                <td>{{ $wallet->updated_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.admin.wallets.edit', ['id' => $wallet->id, 'type' => 'dollar']) }}" class="btn btn-sm btn-warning">
                                            <i class="ri-money-dollar-circle-line"></i> تغییر موجودی
                                        </a>
                                        <a href="{{ route('dashboard.admin.wallets.transactions', ['id' => $wallet->id, 'type' => 'dollar']) }}" class="btn btn-sm btn-info">
                                            <i class="ri-exchange-funds-line"></i> تراکنش‌ها
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">هیچ کیف پولی یافت نشد</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $dollarWallets->appends(request()->except(['afghan_query', 'page']))->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 