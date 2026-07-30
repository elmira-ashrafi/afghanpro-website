@extends('layouts.dashboard')

@section('title', 'مدیریت دسته‌بندی‌ها')
@section('page-title', 'مدیریت دسته‌بندی‌ها')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.admin.index') }}">مدیریت محصولات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">مدیریت دسته‌بندی‌ها</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <h5 class="mb-0">لیست دسته‌بندی‌ها</h5>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('dashboard.shop.admin.categories.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i>افزودن دسته‌بندی جدید
        </a>
    </div>
</div>

<!-- Search Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.shop.admin.categories.index') }}" method="GET" class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">نام دسته‌بندی</label>
                        <input type="text" name="search" class="form-control" placeholder="جستجو..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">دسته‌بندی والد</label>
                        <select name="parent_id" class="form-select">
                            <option value="">همه</option>
                            <option value="0" {{ request('parent_id') === '0' ? 'selected' : '' }}>فقط دسته‌های اصلی</option>
                            <option value="has_parent" {{ request('parent_id') === 'has_parent' ? 'selected' : '' }}>فقط زیردسته‌ها</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">وضعیت</label>
                        <select name="is_active" class="form-select">
                            <option value="">همه</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>فعال</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غیرفعال</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-filter-line me-1"></i>فیلتر
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">نام</th>
                                <th scope="col">اسلاگ</th>
                                <th scope="col">دسته‌بندی والد</th>
                                <th scope="col">ترتیب</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($categories->count() > 0)
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td>
                                            @if($category->parent)
                                                {{ $category->parent->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->sort_order }}</td>
                                        <td>
                                            @if($category->is_active)
                                                <span class="badge bg-success">فعال</span>
                                            @else
                                                <span class="badge bg-danger">غیرفعال</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('dashboard.shop.admin.categories.edit', $category->id) }}" class="btn btn-outline-primary">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $category->id }})">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-4">هیچ دسته‌بندی یافت نشد</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $categories->appends(request()->query())->links() }}
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">تایید حذف دسته‌بندی</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                آیا از حذف این دسته‌بندی اطمینان دارید؟ این عملیات غیرقابل بازگشت است.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف دسته‌بندی</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(categoryId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = "{{ route('dashboard.shop.admin.categories.destroy', ':id') }}".replace(':id', categoryId);
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endpush 