@extends('layouts.dashboard')

@section('title', 'افزودن محصول جدید')
@section('page-title', 'افزودن محصول جدید')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">داشبورد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shop.admin.index') }}">مدیریت محصولات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">افزودن محصول جدید</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form id="create-product-form" action="{{ route('dashboard.shop.admin.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">اطلاعات اصلی محصول</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">نام محصول</label>
                        <input type="text" class="form-control" id="product_name" name="name" placeholder="مثال: اکانت MEGA" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product_slug" class="form-label">اسلاگ (URL)</label>
                        <input type="text" class="form-control" id="product_slug" name="slug" placeholder="مثال: mega-account">
                        <div class="form-text">این مقدار در URL نمایش داده می‌شود. فقط از حروف انگلیسی، اعداد و خط تیره استفاده کنید.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="short_description" class="form-label">توضیحات کوتاه</label>
                        <textarea class="form-control" id="short_description" name="short_description" rows="2" placeholder="توضیح مختصر درباره محصول"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">توضیحات کامل</label>
                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="توضیحات کامل محصول"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Product Attributes Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ویژگی‌های محصول</h5>
                    <button type="button" class="btn btn-sm btn-primary" id="add-attribute-btn">
                        <i class="ri-add-line me-1"></i>افزودن ویژگی
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="ri-information-line fs-4"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1">راهنمای تعریف ویژگی‌ها</h6>
                                <p class="mb-0">
                                    هر ویژگی شامل یک نام (مانند "مدت زمان اشتراک") و چندین مقدار (مانند "1 ماهه | 12 ماهه") است. 
                                    پس از افزودن تمام ویژگی‌ها، ترکیب‌های مختلف در بخش "ترکیب‌های متغیر" ایجاد می‌شوند.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div id="attributes-container">
                        <!-- Sample Attribute 1 -->
                        <div class="attribute-item mb-4 p-3 border rounded">
                            <div class="d-flex justify-content-between mb-3">
                                <h6 class="mb-0">ویژگی جدید</h6>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-attribute-btn">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label class="form-label">نام ویژگی</label>
                                    <input type="text" class="form-control attribute-name" 
                                           name="attributes[0][name]" 
                                           placeholder="مثال: مدت زمان اشتراک">
                                </div>
                                
                                <div class="col-md-7">
                                    <label class="form-label">مقادیر (با خط عمودی | جدا کنید)</label>
                                    <input type="text" class="form-control attribute-values" 
                                           name="attributes[0][values]" 
                                           placeholder="مثال: 1 ماهه | 12 ماهه">
                                </div>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input is-variation-attribute" type="checkbox" 
                                       name="attributes[0][is_variation]" 
                                       id="is_variation_0" checked>
                                <label class="form-check-label" for="is_variation_0">
                                    استفاده از این ویژگی برای ایجاد ترکیب‌های متغیر محصول
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Variations Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ترکیب‌های متغیر</h5>
                    <button type="button" class="btn btn-sm btn-primary" id="generate-variations-btn">
                        <i class="ri-refresh-line me-1"></i>ایجاد ترکیب‌ها
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="ri-alert-line fs-4"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-1">ایجاد ترکیب‌های متغیر</h6>
                                <p class="mb-0">
                                    پس از تعریف ویژگی‌ها، با کلیک بر روی دکمه "ایجاد ترکیب‌ها"، تمام ترکیب‌های ممکن از ویژگی‌های انتخاب شده ایجاد می‌شوند.
                                    سپس می‌توانید برای هر ترکیب، قیمت و موجودی تعیین کنید.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div id="variations-container" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ترکیب</th>
                                        <th>کد محصول (SKU)</th>
                                        <th>قیمت (افغانی)</th>
                                        <th>موجودی</th>
                                        <th>وضعیت</th>
                                    </tr>
                                </thead>
                                <tbody id="variations-tbody">
                                    <!-- Variation rows will be added here dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Product Status Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">وضعیت انتشار</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="product_status" class="form-label">وضعیت</label>
                        <select class="form-select" id="product_status" name="status">
                            <option value="inactive">پیش‌نویس</option>
                            <option value="active">منتشر شده</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product_type" class="form-label">نوع محصول</label>
                        <select class="form-select" id="product_type" name="product_type">
                            <option value="simple">ساده</option>
                            <option value="variable" selected>متغیر</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="submit-product-btn">
                            <i class="ri-save-line me-1"></i>ذخیره محصول
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="save-draft-btn">
                            <i class="ri-draft-line me-1"></i>ذخیره پیش‌نویس
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Product Categories Component -->
            @include('components.product-category-selector', ['categories' => $categories, 'selectedCategories' => []])
            
            <!-- Product Images Card -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">تصاویر محصول</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">تصویر شاخص</label>
                        <input class="form-control" type="file" id="thumbnail" name="thumbnail">
                    </div>
                    
                    <div class="mb-3">
                        <label for="gallery" class="form-label">گالری تصاویر</label>
                        <input class="form-control" type="file" id="gallery" name="gallery[]" multiple>
                        <div class="form-text">می‌توانید چندین تصویر انتخاب کنید</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Variations Preview Modal -->
<div class="modal fade" id="variations-preview-modal" tabindex="-1" aria-labelledby="variationsPreviewLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variationsPreviewLabel">پیش‌نمایش ترکیب‌های متغیر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <p class="mb-0">بر اساس ویژگی‌های تعریف شده، ترکیب‌های زیر ایجاد خواهند شد:</p>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light" id="variations-preview-thead">
                            <!-- Column headers will be dynamically generated -->
                        </thead>
                        <tbody id="variations-preview-tbody">
                            <!-- Preview rows will be dynamically generated -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                <button type="button" class="btn btn-primary" id="confirm-variations-btn">تایید و ایجاد ترکیب‌ها</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let attributeCounter = 1;
        
        // Form submission handler
        const form = document.getElementById('create-product-form');
        const submitBtn = document.getElementById('submit-product-btn');
        const saveDraftBtn = document.getElementById('save-draft-btn');
        
        // Save draft button handler
        saveDraftBtn.addEventListener('click', function() {
            // Set status to inactive (draft)
            document.getElementById('product_status').value = 'inactive';
            
            // Then trigger form submission
            form.dispatchEvent(new Event('submit'));
        });
        
        // Add submit event listener to troubleshoot form submission
        form.addEventListener('submit', function(e) {
            // Prevent the default submission temporarily for validation
            e.preventDefault();
            
            // Log form data
            console.log('Submitting form...');
            
            // Check if variations are generated and visible
            const variationsContainer = document.getElementById('variations-container');
            const isVariationsVisible = !variationsContainer.classList.contains('d-none');
            const productType = document.getElementById('product_type').value;
            
            // Ensure status is using a valid value
            const statusSelect = document.getElementById('product_status');
            if (statusSelect.value !== 'active' && statusSelect.value !== 'inactive') {
                statusSelect.value = 'inactive'; // Set to inactive if invalid value
            }
            
            // Validate product name
            const productName = document.getElementById('product_name').value.trim();
            if (!productName) {
                alert('لطفاً نام محصول را وارد کنید.');
                document.getElementById('product_name').focus();
                return;
            }
            
            // Check if product is variable and needs variations
            if (productType === 'variable' && !isVariationsVisible) {
                const hasAttributes = document.querySelectorAll('.attribute-item').length > 0;
                
                if (hasAttributes) {
                    if (confirm('شما ویژگی‌های محصول را تعریف کرده‌اید اما ترکیب‌های متغیر را ایجاد نکرده‌اید. آیا می‌خواهید ادامه دهید؟')) {
                        // Continue with submission
                    } else {
                        return;
                    }
                }
            }
            
            // Check if any category is selected
            const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]:checked');
            if (categoryCheckboxes.length === 0) {
                if (!confirm('هیچ دسته‌بندی انتخاب نشده است. آیا می‌خواهید بدون دسته‌بندی ادامه دهید؟')) {
                    return;
                }
            }

            try {
                // Submit the form
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ri-loader-4-line me-1 spin"></i>در حال ذخیره...';
                
                // Use setTimeout to ensure UI updates before form submission
                setTimeout(() => {
                    form.submit();
                }, 100);
                
                // Add a backup to re-enable the button if submission takes too long
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="ri-save-line me-1"></i>ذخیره محصول';
                        console.log('Form submission took too long - button reset');
                    }
                }, 10000); // 10 seconds timeout
            } catch (error) {
                console.error('Form submission error:', error);
                alert('خطا در ارسال فرم. لطفا دوباره تلاش کنید.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ri-save-line me-1"></i>ذخیره محصول';
            }
        });
        
        // Direct click handler as a backup for the submit button
        submitBtn.addEventListener('click', function(e) {
            // If not already processing
            if (!submitBtn.disabled) {
                form.dispatchEvent(new Event('submit'));
            }
        });
        
        // Add new attribute
        document.getElementById('add-attribute-btn').addEventListener('click', function() {
            const attributesContainer = document.getElementById('attributes-container');
            
            const newAttribute = document.createElement('div');
            newAttribute.className = 'attribute-item mb-4 p-3 border rounded';
            newAttribute.innerHTML = `
                <div class="d-flex justify-content-between mb-3">
                    <h6 class="mb-0">ویژگی جدید</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-attribute-btn">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label class="form-label">نام ویژگی</label>
                        <input type="text" class="form-control attribute-name" 
                               name="attributes[${attributeCounter}][name]" 
                               placeholder="مثال: مدت زمان اشتراک">
                    </div>
                    
                    <div class="col-md-7">
                        <label class="form-label">مقادیر (با خط عمودی | جدا کنید)</label>
                        <input type="text" class="form-control attribute-values" 
                               name="attributes[${attributeCounter}][values]" 
                               placeholder="مثال: 1 ماهه | 12 ماهه">
                    </div>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input is-variation-attribute" type="checkbox" 
                           name="attributes[${attributeCounter}][is_variation]" 
                           id="is_variation_${attributeCounter}" checked>
                    <label class="form-check-label" for="is_variation_${attributeCounter}">
                        استفاده از این ویژگی برای ایجاد ترکیب‌های متغیر محصول
                    </label>
                </div>
            `;
            
            attributesContainer.appendChild(newAttribute);
            attributeCounter++;
            
            // Add event listener to the new remove button
            const removeButton = newAttribute.querySelector('.remove-attribute-btn');
            removeButton.addEventListener('click', function() {
                attributesContainer.removeChild(newAttribute);
            });
        });
        
        // Remove attribute button event delegation
        document.getElementById('attributes-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-attribute-btn')) {
                const button = e.target.closest('.remove-attribute-btn');
                const attributeItem = button.closest('.attribute-item');
                attributeItem.remove();
            }
        });
        
        // Helper function to generate all combinations of attributes
        function generateCombinations(arrays) {
            if (arrays.length === 0) return [[]];
            
            const firstArray = arrays[0];
            const restArrays = arrays.slice(1);
            const recursiveCombinations = generateCombinations(restArrays);
            
            const result = [];
            for (const item of firstArray) {
                for (const comb of recursiveCombinations) {
                    result.push([item, ...comb]);
                }
            }
            
            return result;
        }

        // Generate variations button
        document.getElementById('generate-variations-btn').addEventListener('click', function() {
            // Get all attributes marked for variation
            const attributeItems = document.querySelectorAll('.attribute-item');
            const variationAttributes = [];
            
            for (const item of attributeItems) {
                const name = item.querySelector('.attribute-name').value.trim();
                const valuesString = item.querySelector('.attribute-values').value.trim();
                const isVariation = item.querySelector('.is-variation-attribute').checked;
                
                if (name && valuesString && isVariation) {
                    const values = valuesString.split('|').map(val => val.trim()).filter(val => val);
                    
                    if (values.length > 0) {
                        variationAttributes.push({
                            name,
                            values
                        });
                    }
                }
            }
            
            if (variationAttributes.length === 0) {
                alert('لطفاً حداقل یک ویژگی برای ایجاد ترکیب‌های متغیر تعریف کنید.');
                return;
            }
            
            // Generate all combinations
            const attributeValueArrays = variationAttributes.map(attr => attr.values);
            const combinations = generateCombinations(attributeValueArrays);
            
            // Create table headers
            const previewTableHead = document.getElementById('variations-preview-thead');
            let headerRow = '<tr>';
            
            for (const attr of variationAttributes) {
                headerRow += `<th>${attr.name}</th>`;
            }
            
            headerRow += '<th>ترکیب نهایی</th></tr>';
            previewTableHead.innerHTML = headerRow;
            
            // Create table rows for each combination
            const previewTableBody = document.getElementById('variations-preview-tbody');
            previewTableBody.innerHTML = '';
            
            for (const combo of combinations) {
                let row = '<tr>';
                
                // Add each value
                for (const value of combo) {
                    row += `<td>${value}</td>`;
                }
                
                // Add combined name
                const combinedName = combo.join(' - ');
                row += `<td>${combinedName}</td>`;
                
                row += '</tr>';
                previewTableBody.innerHTML += row;
            }
            
            // Show the modal with proper focus management
            const modalElement = document.getElementById('variations-preview-modal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            // Handle focus when modal is hidden
            modalElement.addEventListener('hidden.bs.modal', function (event) {
                // Return focus to the button that opened the modal
                document.getElementById('generate-variations-btn').focus();
            }, { once: true });
        });
        
        // Confirm variations button
        document.getElementById('confirm-variations-btn').addEventListener('click', function() {
            // Get all attributes marked for variation
            const attributeItems = document.querySelectorAll('.attribute-item');
            const variationAttributes = [];
            
            for (const item of attributeItems) {
                const name = item.querySelector('.attribute-name').value.trim();
                const valuesString = item.querySelector('.attribute-values').value.trim();
                const isVariation = item.querySelector('.is-variation-attribute').checked;
                
                if (name && valuesString && isVariation) {
                    const values = valuesString.split('|').map(val => val.trim()).filter(val => val);
                    
                    if (values.length > 0) {
                        variationAttributes.push({
                            name,
                            values
                        });
                    }
                }
            }
            
            // Generate all combinations
            const attributeValueArrays = variationAttributes.map(attr => attr.values);
            const combinations = generateCombinations(attributeValueArrays);
            
            // Hide the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('variations-preview-modal'));
            modal.hide();
            
            // Show the variations container
            document.getElementById('variations-container').classList.remove('d-none');
            
            // Generate variation rows
            const variationsTbody = document.getElementById('variations-tbody');
            variationsTbody.innerHTML = '';
            
            combinations.forEach((combo, index) => {
                const combinedName = combo.join(' - ');
                // Generate a simple SKU based on the combination
                const sku = combinedName.replace(/[^a-zA-Z0-9]/g, '-').toUpperCase();
                
                const row = `
                    <tr>
                        <td>${combinedName}</td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="variations[${index}][sku]" value="${sku}">
                            ${variationAttributes.map((attr, attrIndex) => 
                                `<input type="hidden" name="variations[${index}][attributes][${attr.name}]" value="${combo[attrIndex]}">`
                            ).join('')}
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" name="variations[${index}][price]" value="0">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" name="variations[${index}][stock]" value="0" min="-1">
                            <small class="text-muted d-block">برای موجودی نامحدود، مقدار -1 را وارد کنید.</small>
                        </td>
                        <td>
                            <select class="form-select form-select-sm" name="variations[${index}][status]">
                                <option value="1" selected>فعال</option>
                                <option value="0">غیرفعال</option>
                            </select>
                        </td>
                    </tr>
                `;
                
                variationsTbody.innerHTML += row;
            });
        });
    });
</script>
@endpush 