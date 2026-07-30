@extends('layouts.admin')

@section('title', 'مدیریت کاربران')
@section('page-title', 'مدیریت کاربران')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">لیست کاربران</h5>
        <a href="{{ route('dashboard.admin.users.create') }}" class="btn btn-sm btn-primary">
            <i class="ri-add-line"></i>
            افزودن کاربر جدید
        </a>
    </div>
    
    <!-- Search Form -->
    <div class="card-body border-bottom">
        <form action="{{ route('dashboard.admin.users.index') }}" method="GET" class="row align-items-end">
            <div class="col-md-3 mb-3">
                <label class="form-label">نام/نام خانوادگی</label>
                <input type="text" name="search" class="form-control" placeholder="جستجو..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">وضعیت کاربر</label>
                <select name="is_verified" class="form-select">
                    <option value="">همه</option>
                    <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>تایید شده</option>
                    <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>در انتظار تایید</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">سطح دسترسی</label>
                <select name="role" class="form-select">
                    <option value="">همه</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مدیر سیستم</option>
                    <option value="support" {{ request('role') == 'support' ? 'selected' : '' }}>پشتیبان</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>کاربر عادی</option>
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
                        <th>نام</th>
                        <th>ایمیل</th>
                        <th>شماره تماس</th>
                        <th>تاریخ ثبت‌نام</th>
                        <th>سطح دسترسی</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }} {{ $user->lastname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->created_at->format('Y/m/d H:i') }}</td>
                        <td>
                            @if ($user->is_admin)
                                <span class="badge bg-danger">مدیر سیستم</span>
                            @elseif ($user->is_support)
                                <span class="badge bg-info">پشتیبان</span>
                            @else
                                <span class="badge bg-secondary">کاربر عادی</span>
                            @endif
                        </td>
                        <td>
                            @if ($user->is_verified)
                                <span class="badge bg-success">تایید شده</span>
                            @else
                                <span class="badge bg-warning">در انتظار تایید</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard.admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('dashboard.admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <a href="{{ route('dashboard.admin.orders.user', $user->id) }}" class="btn btn-sm btn-primary">
                                    <i class="ri-shopping-bag-line"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">تایید حذف کاربر</h5>
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">هیچ کاربری یافت نشد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $users->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
@endsection 