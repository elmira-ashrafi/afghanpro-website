@extends('layouts.admin')

@section('title', 'مدیریت نمایندگی‌ها')

@section('page-title', 'مدیریت نمایندگی‌ها')

@section('content')
<div class="row mb-4">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">لیست نمایندگی‌ها</h5>
                <a href="{{ route('dashboard.admin.agencies.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i>
                    ایجاد نمایندگی جدید
                </a>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <div class="mb-4">
                    <form action="{{ route('dashboard.admin.agencies.index') }}" method="GET" class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">نام نمایندگی</label>
                            <input type="text" name="search" class="form-control" placeholder="جستجو..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">استان</label>
                            <input type="text" name="province" class="form-control" placeholder="استان" value="{{ request('province') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">شهر</label>
                            <input type="text" name="city" class="form-control" placeholder="شهر" value="{{ request('city') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">وضعیت</label>
                            <select name="is_active" class="form-select">
                                <option value="">همه</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-filter-line me-1"></i>فیلتر
                            </button>
                        </div>
                    </form>
                </div>
                
                @if($agencies->isEmpty())
                    <div class="alert alert-info">
                        هیچ نمایندگی‌ای یافت نشد.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">نام</th>
                                    <th scope="col">استان</th>
                                    <th scope="col">شهر</th>
                                    <th scope="col">آدرس</th>
                                    <th scope="col">تلفن</th>
                                    <th scope="col">وضعیت</th>
                                    <th scope="col">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agencies as $agency)
                                    <tr>
                                        <th scope="row">{{ ($agencies->currentPage() - 1) * $agencies->perPage() + $loop->iteration }}</th>
                                        <td>{{ $agency->name }}</td>
                                        <td>{{ $agency->province }}</td>
                                        <td>{{ $agency->city }}</td>
                                        <td>{{ Str::limit($agency->address, 30) }}</td>
                                        <td dir="ltr">{{ $agency->phone }}</td>
                                        <td>
                                            @if($agency->is_active)
                                                <span class="badge bg-success">فعال</span>
                                            @else
                                                <span class="badge bg-danger">غیرفعال</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('dashboard.admin.agencies.edit', $agency->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form action="{{ route('dashboard.admin.agencies.destroy', $agency->id) }}" method="POST" class="d-inline-block delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا از حذف این نمایندگی اطمینان دارید؟')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $agencies->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 