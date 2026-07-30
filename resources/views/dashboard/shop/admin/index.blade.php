@extends('layouts.dashboard')

@section('title', 'مدیریت محصولات')
@section('page-title', 'مدیریت محصولات')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.index') }}">فروشگاه</a></li>
                        <li class="breadcrumb-item active" aria-current="page">مدیریت محصولات</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <h5 class="mb-0">لیست محصولات</h5>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('dashboard.shop.admin.import') }}" class="btn btn-success me-2">
            <i class="ri-upload-line me-1"></i>ورود محصولات
        </a>
        <a href="{{ route('dashboard.shop.admin.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i>افزودن محصول جدید
        </a>
    </div>
</div>

<!-- Search Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.shop.admin.index') }}" method="GET" class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">نام محصول</label>
                        <input type="text" name="search" class="form-control" placeholder="جستجو..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">دسته‌بندی</label>
                        <select name="category_id" class="form-select">
                            <option value="">همه دسته‌بندی‌ها</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">وضعیت</label>
                        <select name="status" class="form-select">
                            <option value="">همه</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>فعال</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">تصویر</th>
                                <th scope="col">نام محصول</th>
                                <th scope="col">دسته‌بندی</th>
                                <th scope="col">قیمت</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->thumbnail)
                                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="img-thumbnail" width="40">
                                    @else
                                        <img src="https://placehold.co/60x60/4a90e2/ffffff?text={{ substr($product->name, 0, 1) }}" alt="{{ $product->name }}" class="img-thumbnail" width="40">
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>
                                    @foreach($product->categories as $category)
                                        <span class="badge bg-info me-1">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $product->price_range }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'danger' }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('dashboard.shop.admin.edit', $product->id) }}" class="btn btn-outline-primary">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $product->id }})">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="ri-shopping-basket-line fs-1 text-muted"></i>
                                    </div>
                                    <h5>هیچ محصولی یافت نشد</h5>
                                    <p class="text-muted">محصول جدیدی اضافه کنید یا محصولات را از فایل CSV وارد کنید.</p>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('dashboard.shop.admin.import') }}" class="btn btn-success mx-2">
                                            <i class="ri-upload-line me-1"></i>ورود محصولات
                                        </a>
                                        <a href="{{ route('dashboard.shop.admin.create') }}" class="btn btn-primary mx-2">
                                            <i class="ri-add-line me-1"></i>افزودن محصول جدید
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="row mt-4">
    <div class="col-12">
        {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">تایید حذف محصول</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                آیا از حذف این محصول اطمینان دارید؟ این عملیات غیرقابل بازگشت است.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف محصول</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(productId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = "{{ route('dashboard.shop.admin.destroy', ':id') }}".replace(':id', productId);
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endpush 