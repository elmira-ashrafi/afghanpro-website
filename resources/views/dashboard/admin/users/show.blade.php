@extends('layouts.admin')

@section('title', 'نمایش اطلاعات کاربر')
@section('page-title', 'نمایش اطلاعات کاربر')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">اطلاعات پروفایل</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar mx-auto mb-3" style="width:100px; height:100px;">
                        <img src="https://ui-avatars.com/api/?name={{ $user->name }}+{{ $user->lastname }}&background=6200EA&color=fff" class="img-fluid rounded-circle" alt="{{ $user->name }}">
                    </div>
                    <h5 class="mb-0">{{ $user->name }} {{ $user->lastname }}</h5>
                    <p class="text-muted">
                        @if ($user->is_admin)
                            <span class="badge bg-danger">مدیر سیستم</span>
                        @elseif ($user->is_support)
                            <span class="badge bg-info">پشتیبان</span>
                        @else
                            <span class="badge bg-secondary">کاربر عادی</span>
                        @endif
                        
                        @if ($user->is_verified)
                            <span class="badge bg-success">تایید شده</span>
                        @else
                            <span class="badge bg-warning">در انتظار تایید</span>
                        @endif
                    </p>
                </div>
                
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تاریخ عضویت:</span>
                    <span>{{ $user->created_at->format('Y/m/d H:i') }}</span>
                </div>
                
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">آخرین بروزرسانی:</span>
                    <span>{{ $user->updated_at->format('Y/m/d H:i') }}</span>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('dashboard.admin.users.edit', $user->id) }}" class="btn btn-warning">
                        <i class="ri-edit-line me-1"></i> ویرایش کاربر
                    </a>
                    <a href="{{ route('dashboard.admin.orders.user', $user->id) }}" class="btn btn-primary">
                        <i class="ri-shopping-bag-line me-1"></i> مشاهده سفارش‌ها
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                        <i class="ri-delete-bin-line me-1"></i> حذف کاربر
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">اطلاعات تماس</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <h6 class="text-muted mb-1">ایمیل</h6>
                            <div>{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <h6 class="text-muted mb-1">شماره تماس</h6>
                            <div>{{ $user->phone }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <h6 class="text-muted mb-1">شماره تلگرام</h6>
                            <div>{{ $user->telegram_number ?? 'ثبت نشده' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <h6 class="text-muted mb-1">آدرس</h6>
                            <div>{{ $user->province ?? '' }} {{ $user->city ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">کیف پول دلاری</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">${{ number_format($user->dollarWallet->balance, 2) }}</h3>
                                <p class="text-muted mb-0">موجودی فعلی</p>
                            </div>
                            <a href="{{ route('dashboard.admin.wallets.edit', ['id' => $user->dollarWallet->id, 'type' => 'dollar']) }}" class="btn btn-sm btn-primary">
                                <i class="ri-money-dollar-circle-line"></i> مدیریت موجودی
                            </a>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('dashboard.admin.wallets.transactions', ['id' => $user->dollarWallet->id, 'type' => 'dollar']) }}" class="btn btn-sm btn-outline-secondary w-100">
                                مشاهده تراکنش‌ها
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">کیف پول افغانی</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">{{ number_format($user->afghanWallet->balance) }} افغانی</h3>
                                <p class="text-muted mb-0">موجودی فعلی</p>
                            </div>
                            <a href="{{ route('dashboard.admin.wallets.edit', ['id' => $user->afghanWallet->id, 'type' => 'afghan']) }}" class="btn btn-sm btn-primary">
                                <i class="ri-money-dollar-circle-line"></i> مدیریت موجودی
                            </a>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('dashboard.admin.wallets.transactions', ['id' => $user->afghanWallet->id, 'type' => 'afghan']) }}" class="btn btn-sm btn-outline-secondary w-100">
                                مشاهده تراکنش‌ها
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">تایید حذف کاربر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                آیا از حذف این کاربر اطمینان دارید؟<br>
                <strong>{{ $user->name }} {{ $user->lastname }}</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <form action="{{ route('dashboard.admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 