@extends('layouts.admin')

@section('title', 'نمایش اطلاعات پشتیبان')
@section('page-title', 'نمایش اطلاعات پشتیبان')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">اطلاعات پشتیبان</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar mx-auto mb-3" style="width:100px; height:100px;">
                        <img src="https://ui-avatars.com/api/?name={{ $supporter->name }}+{{ $supporter->lastname }}&background=6200EA&color=fff" class="img-fluid rounded-circle" alt="{{ $supporter->name }}">
                    </div>
                    <h5 class="mb-0">{{ $supporter->name }} {{ $supporter->lastname }}</h5>
                    <p class="text-muted">
                        @if ($supporter->is_admin)
                            <span class="badge bg-danger">مدیر سیستم</span>
                        @else
                            <span class="badge bg-info">پشتیبان</span>
                        @endif
                    </p>
                </div>
                
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">تاریخ عضویت:</span>
                    <span>{{ $supporter->created_at->format('Y/m/d H:i') }}</span>
                </div>
                
                <div class="info-item d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-muted">آخرین بروزرسانی:</span>
                    <span>{{ $supporter->updated_at->format('Y/m/d H:i') }}</span>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('dashboard.admin.supporters.edit', $supporter->id) }}" class="btn btn-warning">
                        <i class="ri-edit-line me-1"></i> ویرایش پشتیبان
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSupporterModal">
                        <i class="ri-delete-bin-line me-1"></i> حذف دسترسی پشتیبان
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
                            <div>{{ $supporter->email }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <h6 class="text-muted mb-1">شماره تماس</h6>
                            <div>{{ $supporter->phone }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <h6 class="text-muted mb-1">شماره تلگرام</h6>
                            <div>{{ $supporter->telegram_number ?? 'ثبت نشده' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <h6 class="text-muted mb-1">آدرس</h6>
                            <div>{{ $supporter->province ?? '' }} {{ $supporter->city ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">آمار فعالیت‌ها</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-item text-center mb-3 p-3 bg-light rounded">
                            <div class="display-6 fw-bold text-primary">{{ $tradeDeposits }}</div>
                            <div>درخواست ازدیاد پول</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-item text-center mb-3 p-3 bg-light rounded">
                            <div class="display-6 fw-bold text-success">{{ $tradeWithdrawals }}</div>
                            <div>درخواست نقدی سازی</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-item text-center mb-3 p-3 bg-light rounded">
                            <div class="display-6 fw-bold text-info">{{ $moneyTransfers }}</div>
                            <div>حواله‌های پردازش شده</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteSupporterModal" tabindex="-1" aria-labelledby="deleteSupporterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSupporterModalLabel">تایید حذف دسترسی پشتیبان</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                آیا از حذف دسترسی پشتیبانی برای این کاربر اطمینان دارید؟<br>
                <strong>{{ $supporter->name }} {{ $supporter->lastname }}</strong><br>
                <small class="text-muted">این عمل تنها دسترسی پشتیبانی را از کاربر سلب می‌کند و حساب کاربری حذف نمی‌شود.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <form action="{{ route('dashboard.admin.supporters.destroy', $supporter->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف دسترسی</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 