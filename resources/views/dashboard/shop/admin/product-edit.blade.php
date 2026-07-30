@extends('layouts.dashboard')

@section('title', 'ویرایش محصول')
@section('page-title', 'ویرایش محصول')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.index') }}">فروشگاه</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.admin.index') }}">مدیریت محصولات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ویرایش محصول</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">ویرایش محصول "{{ $product->name }}"</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.shop.admin.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">نام محصول <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="slug" class="form-label">نامک (اختیاری)</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $product->slug) }}">
                                <div class="form-text">اگر خالی بگذارید، به صورت خودکار از نام محصول ساخته می‌شود.</div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="short_description" class="form-label">توضیح کوتاه</label>
                                <input type="text" class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}">
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">توضیحات کامل</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">وضعیت <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>فعال</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="categories" class="form-label">دسته‌بندی‌ها</label>
                                <select class="form-select @error('categories') is-invalid @enderror" id="categories" name="categories[]" multiple>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="thumbnail" class="form-label">تصویر شاخص</label>
                                @if($product->thumbnail)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail">
                                <div class="form-text">برای تغییر تصویر فعلی، فایل جدید را انتخاب کنید.</div>
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="gallery" class="form-label">گالری تصاویر</label>
                                @if($product->gallery->count() > 0)
                                    <div class="row mb-2">
                                        @foreach($product->gallery as $image)
                                            <div class="col-4 mb-2">
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="img-thumbnail" style="height: 80px;">
                                                    <div class="form-check position-absolute top-0 end-0 m-1">
                                                        <input class="form-check-input" type="checkbox" name="delete_gallery[]" value="{{ $image->id }}" id="delete_image_{{ $image->id }}">
                                                        <label class="form-check-label visually-hidden" for="delete_image_{{ $image->id }}">
                                                            حذف
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-text mb-2">برای حذف تصاویر، چک‌باکس مربوطه را علامت بزنید.</div>
                                @endif
                                <input type="file" class="form-control @error('gallery.*') is-invalid @enderror" id="gallery" name="gallery[]" multiple>
                                <div class="form-text">می‌توانید چندین تصویر را به گالری اضافه کنید.</div>
                                @error('gallery.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4">
                        <h5>مدیریت ویژگی‌ها</h5>
                        <div class="form-text mb-3">
                            اگر محصول شما دارای تنوع (مانند رنگ، اندازه، و غیره) است، ویژگی‌ها را تعریف کنید.
                        </div>
                        
                        <div id="attributes-container">
                            @foreach($product->attributes as $index => $attribute)
                                <div class="attribute-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">ویژگی {{ $index + 1 }}</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-attribute">
                                            <i class="ri-delete-bin-line"></i> حذف
                                        </button>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">نام ویژگی</label>
                                            <input type="text" class="form-control" name="attributes[{{ $attribute->id }}][name]" value="{{ $attribute->name }}" placeholder="مثال: رنگ، سایز، مدت، ...">
                                            <input type="hidden" name="attributes[{{ $attribute->id }}][id]" value="{{ $attribute->id }}">
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label">مقادیر (با | جدا کنید)</label>
                                            <input type="text" class="form-control" name="attributes[{{ $attribute->id }}][values]" value="{{ implode('|', $attribute->values) }}" placeholder="مثال: قرمز|آبی|سبز">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="text-center">
                            <button type="button" id="add-attribute" class="btn btn-outline-primary">
                                <i class="ri-add-line"></i> افزودن ویژگی جدید
                            </button>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4">
                        <h5>مدیریت تنوع‌ها (واریاسیون‌ها)</h5>
                        <div class="form-text mb-3">
                            برای هر ترکیب از ویژگی‌ها، قیمت و موجودی را تعیین کنید.
                        </div>
                        
                        <div id="variations-container">
                            @foreach($product->variations as $index => $variation)
                                <div class="variation-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">تنوع {{ $index + 1 }}</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-variation">
                                            <i class="ri-delete-bin-line"></i> حذف
                                        </button>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">ترکیب ویژگی‌ها</label>
                                            <div class="attributes-combination">
                                                @foreach($variation->attributes as $attrName => $attrValue)
                                                    <div class="mb-2">
                                                        <label class="form-label">{{ $attrName }}</label>
                                                        <input type="text" class="form-control" name="variations[{{ $variation->id }}][attributes][{{ $attrName }}]" value="{{ $attrValue }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <input type="hidden" name="variations[{{ $variation->id }}][id]" value="{{ $variation->id }}">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">قیمت (افغانی)</label>
                                                    <input type="number" class="form-control" name="variations[{{ $variation->id }}][price]" value="{{ $variation->price }}" min="0">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">موجودی</label>
                                                    <input type="number" class="form-control" name="variations[{{ $variation->id }}][stock]" value="{{ $variation->stock }}" min="-1">
                                                    <div class="form-text">برای موجودی نامحدود، مقدار -1 را وارد کنید.</div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">کد محصول (SKU)</label>
                                                <input type="text" class="form-control" name="variations[{{ $variation->id }}][sku]" value="{{ $variation->sku }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="text-center">
                            <button type="button" id="add-variation" class="btn btn-outline-primary">
                                <i class="ri-add-line"></i> افزودن تنوع جدید
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard.shop.admin.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-go-back-line me-1"></i>بازگشت
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>ذخیره تغییرات
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
        // Generate slug from name
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        if (nameInput && slugInput) {
            nameInput.addEventListener('blur', function() {
                if (slugInput.value.trim() === '') {
                    // Convert name to slug
                    const slug = nameInput.value
                        .toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim();
                    
                    slugInput.value = slug;
                }
            });
        }
        
        // Attributes management
        const attributesContainer = document.getElementById('attributes-container');
        const addAttributeBtn = document.getElementById('add-attribute');
        let attributeCounter = {{ $product->attributes->count() }};
        
        if (addAttributeBtn) {
            addAttributeBtn.addEventListener('click', function() {
                attributeCounter++;
                
                const attributeItem = document.createElement('div');
                attributeItem.className = 'attribute-item border rounded p-3 mb-3';
                attributeItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">ویژگی جدید</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-attribute">
                            <i class="ri-delete-bin-line"></i> حذف
                        </button>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">نام ویژگی</label>
                            <input type="text" class="form-control" name="attributes[new_${attributeCounter}][name]" placeholder="مثال: رنگ، سایز، مدت، ...">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">مقادیر (با | جدا کنید)</label>
                            <input type="text" class="form-control" name="attributes[new_${attributeCounter}][values]" placeholder="مثال: قرمز|آبی|سبز">
                        </div>
                    </div>
                `;
                
                attributesContainer.appendChild(attributeItem);
                
                // Add event listener to the remove button
                const removeBtn = attributeItem.querySelector('.remove-attribute');
                removeBtn.addEventListener('click', function() {
                    attributeItem.remove();
                });
            });
        }
        
        // Add event listeners to existing remove attribute buttons
        const removeAttributeBtns = document.querySelectorAll('.remove-attribute');
        removeAttributeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const attributeItem = this.closest('.attribute-item');
                attributeItem.remove();
            });
        });
        
        // Variations management
        const variationsContainer = document.getElementById('variations-container');
        const addVariationBtn = document.getElementById('add-variation');
        let variationCounter = {{ $product->variations->count() }};
        
        if (addVariationBtn) {
            addVariationBtn.addEventListener('click', function() {
                variationCounter++;
                
                const variationItem = document.createElement('div');
                variationItem.className = 'variation-item border rounded p-3 mb-3';
                variationItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">تنوع جدید</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-variation">
                            <i class="ri-delete-bin-line"></i> حذف
                        </button>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ترکیب ویژگی‌ها</label>
                            <div class="attributes-combination">
                                @foreach($product->attributes as $attribute)
                                    <div class="mb-2">
                                        <label class="form-label">{{ $attribute->name }}</label>
                                        <select class="form-select" name="variations[new_${variationCounter}][attributes][{{ $attribute->name }}]">
                                            @foreach($attribute->values as $value)
                                                <option value="{{ $value }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">قیمت (افغانی)</label>
                                    <input type="number" class="form-control" name="variations[new_${variationCounter}][price]" value="0" min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">موجودی</label>
                                    <input type="number" class="form-control" name="variations[new_${variationCounter}][stock]" value="0" min="-1">
                                    <div class="form-text">برای موجودی نامحدود، مقدار -1 را وارد کنید.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">کد محصول (SKU)</label>
                                <input type="text" class="form-control" name="variations[new_${variationCounter}][sku]">
                            </div>
                        </div>
                    </div>
                `;
                
                variationsContainer.appendChild(variationItem);
                
                // Add event listener to the remove button
                const removeBtn = variationItem.querySelector('.remove-variation');
                removeBtn.addEventListener('click', function() {
                    variationItem.remove();
                });
            });
        }
        
        // Add event listeners to existing remove variation buttons
        const removeVariationBtns = document.querySelectorAll('.remove-variation');
        removeVariationBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const variationItem = this.closest('.variation-item');
                variationItem.remove();
            });
        });
    });
</script>
@endpush 