@extends('layouts.admin')

@section('title', 'مدیریت پشتیبان‌ها')
@section('page-title', 'مدیریت پشتیبان‌ها')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">لیست پشتیبان‌ها</h5>
        <a href="{{ route('dashboard.admin.supporters.create') }}" class="btn btn-sm btn-primary">
            <i class="ri-add-line"></i>
            افزودن پشتیبان جدید
        </a>
    </div>
    
    <!-- Search Form -->
    <div class="card-body border-bottom">
        <form action="{{ route('dashboard.admin.supporters.index') }}" method="GET" class="row align-items-end">
            <div class="col-md-4 mb-3">
                <label class="form-label">نام/نام خانوادگی</label>
                <input type="text" name="search" class="form-control" placeholder="جستجوی نام، نام خانوادگی یا ایمیل" value="{{ request('search') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">دسترسی مدیر</label>
                <select name="is_admin" class="form-select">
                    <option value="">همه</option>
                    <option value="1" {{ request('is_admin') == '1' ? 'selected' : '' }}>دارد</option>
                    <option value="0" {{ request('is_admin') == '0' ? 'selected' : '' }}>ندارد</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
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
                        <th>تاریخ عضویت</th>
                        <th>دسترسی مدیر</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supporters as $supporter)
                    <tr>
                        <td>{{ $supporter->id }}</td>
                        <td>{{ $supporter->name }} {{ $supporter->lastname }}</td>
                        <td>{{ $supporter->email }}</td>
                        <td>{{ $supporter->phone }}</td>
                        <td>{{ $supporter->created_at->format('Y/m/d H:i') }}</td>
                        <td>
                            @if ($supporter->is_admin)
                                <span class="badge bg-success">دارد</span>
                            @else
                                <span class="badge bg-secondary">ندارد</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard.admin.supporters.show', $supporter->id) }}" class="btn btn-sm btn-info">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('dashboard.admin.supporters.edit', $supporter->id) }}" class="btn btn-sm btn-warning">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $supporter->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $supporter->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $supporter->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $supporter->id }}">تایید حذف پشتیبان</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            آیا از حذف این پشتیبان اطمینان دارید؟<br>
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">هیچ پشتیبانی یافت نشد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $supporters->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection 