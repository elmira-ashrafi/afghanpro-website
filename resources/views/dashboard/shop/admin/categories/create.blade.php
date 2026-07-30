@extends('layouts.dashboard')

@section('title', 'افزودن دسته‌بندی جدید')
@section('page-title', 'افزودن دسته‌بندی جدید')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.admin.index') }}">مدیریت محصولات</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.admin.categories.index') }}">مدیریت دسته‌بندی‌ها</a></li>
                        <li class="breadcrumb-item active" aria-current="page">افزودن دسته‌بندی جدید</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">افزودن دسته‌بندی جدید</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dashboard.shop.admin.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">نام دسته‌بندی</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        <div class="form-text">نام دسته‌بندی که به کاربران نمایش داده می‌شود.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="slug" class="form-label">اسلاگ (URL)</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}">
                        <div class="form-text">این مقدار در URL نمایش داده می‌شود. فقط از حروف انگلیسی، اعداد و خط تیره استفاده کنید. اگر خالی بگذارید، به صورت خودکار از نام دسته‌بندی ساخته می‌شود.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">دسته‌بندی والد</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">بدون والد (دسته‌بندی اصلی)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                
                                @foreach($category->children as $childCategory)
                                    <option value="{{ $childCategory->id }}" {{ old('parent_id') == $childCategory->id ? 'selected' : '' }}>—— {{ $childCategory->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        <div class="form-text">می‌توانید این دسته‌بندی را به عنوان زیرمجموعه یک دسته‌بندی دیگر تعریف کنید.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">توضیحات</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        <div class="form-text">توضیحات مختصر درباره این دسته‌بندی (اختیاری).</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="icon" class="form-label">آیکون</label>
                        <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon') }}">
                        <div class="form-text">نام کلاس آیکون (مثلاً: <code>ri-apps-line</code>). از آیکون‌های <a href="https://remixicon.com/" target="_blank">Remix Icon</a> استفاده کنید.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">ترتیب نمایش</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                        <div class="form-text">اعداد کوچکتر در ابتدا نمایش داده می‌شوند.</div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                فعال
                            </label>
                            <div class="form-text">اگر فعال نباشد، در فروشگاه نمایش داده نمی‌شود.</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard.shop.admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-go-back-line me-1"></i>
                            بازگشت
                        </a>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>
                            ذخیره دسته‌بندی
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate slug from name
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        nameInput.addEventListener('keyup', function() {
            if (!slugInput.value) {
                // Convert to lowercase, replace spaces with dashes, remove special chars
                slugInput.value = nameInput.value
                    .trim()
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-');
            }
        });
    });
</script>
@endpush 