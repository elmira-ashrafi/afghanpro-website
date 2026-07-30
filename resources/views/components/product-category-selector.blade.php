<div class="product-categories-container">
    <div class="card mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">دسته‌بندی‌ها</h5>
            <button type="button" class="btn btn-sm btn-primary" id="add-category-btn">
                <i class="ri-add-line me-1"></i>افزودن دسته‌بندی جدید
            </button>
        </div>
        <div class="card-body">
            @if($categories->count() > 0)
                <div class="mb-3">
                    @foreach($categories as $category)
                        <div class="form-check mb-2">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   value="{{ $category->id }}" 
                                   id="category_{{ $category->id }}" 
                                   name="categories[]"
                                   {{ isset($selectedCategories) && in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                            <label class="form-check-label" for="category_{{ $category->id }}">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} me-1"></i>
                                @endif
                                {{ $category->name }}
                            </label>
                            
                            @if($category->children->count() > 0)
                                <div class="ms-4 mt-2">
                                    @foreach($category->children as $childCategory)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   value="{{ $childCategory->id }}" 
                                                   id="category_{{ $childCategory->id }}" 
                                                   name="categories[]"
                                                   {{ isset($selectedCategories) && in_array($childCategory->id, $selectedCategories) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category_{{ $childCategory->id }}">
                                                @if($childCategory->icon)
                                                    <i class="{{ $childCategory->icon }} me-1"></i>
                                                @endif
                                                {{ $childCategory->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="ri-information-line fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-0">هیچ دسته‌بندی یافت نشد. با استفاده از دکمه «افزودن دسته‌بندی جدید» می‌توانید دسته‌بندی ایجاد کنید.</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="mb-0">
                <a href="{{ route('dashboard.shop.admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="ri-list-check me-1"></i>مدیریت دسته‌بندی‌ها
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="quick-add-category-modal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">افزودن دسته‌بندی جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quick-add-category-form">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">نام دسته‌بندی</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_parent_id" class="form-label">دسته‌بندی والد</label>
                        <select class="form-select" id="category_parent_id" name="category_parent_id">
                            <option value="">بدون والد (دسته‌بندی اصلی)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="quick-add-category-error" class="alert alert-danger d-none"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-primary" id="save-quick-category-btn">
                    <i class="ri-save-line me-1"></i>ذخیره دسته‌بندی
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addCategoryBtn = document.getElementById('add-category-btn');
        const quickAddCategoryModal = new bootstrap.Modal(document.getElementById('quick-add-category-modal'));
        const saveQuickCategoryBtn = document.getElementById('save-quick-category-btn');
        const quickAddCategoryForm = document.getElementById('quick-add-category-form');
        const categoryNameInput = document.getElementById('category_name');
        const categoryParentInput = document.getElementById('category_parent_id');
        const quickAddCategoryError = document.getElementById('quick-add-category-error');
        
        // Open modal when add category button is clicked
        addCategoryBtn.addEventListener('click', function() {
            quickAddCategoryError.classList.add('d-none');
            quickAddCategoryForm.reset();
            quickAddCategoryModal.show();
        });
        
        // Save category when save button is clicked
        saveQuickCategoryBtn.addEventListener('click', function() {
            const categoryName = categoryNameInput.value.trim();
            const parentId = categoryParentInput.value;
            
            if (!categoryName) {
                quickAddCategoryError.textContent = 'لطفاً نام دسته‌بندی را وارد کنید.';
                quickAddCategoryError.classList.remove('d-none');
                return;
            }
            
            // Disable button while saving
            saveQuickCategoryBtn.disabled = true;
            saveQuickCategoryBtn.innerHTML = '<i class="ri-loader-4-line me-1 spin"></i>در حال ذخیره...';
            
            // Send AJAX request to save category
            fetch('{{ route("dashboard.shop.admin.categories.ajax-store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: categoryName,
                    parent_id: parentId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new category checkbox to the list
                    const categoriesContainer = document.querySelector('.product-categories-container .card-body');
                    let categoryHtml = '';
                    
                    if (!document.querySelector('.form-check')) {
                        // If no categories yet, remove the "no categories found" message
                        categoriesContainer.innerHTML = '';
                    }
                    
                    // Create checkbox HTML
                    categoryHtml += `
                        <div class="form-check mb-2">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   value="${data.category.id}" 
                                   id="category_${data.category.id}" 
                                   name="categories[]"
                                   checked>
                            <label class="form-check-label" for="category_${data.category.id}">
                                ${data.category.name}
                            </label>
                        </div>
                    `;
                    
                    // Insert at the beginning of the container
                    const firstCheckbox = categoriesContainer.querySelector('.form-check');
                    if (firstCheckbox) {
                        firstCheckbox.insertAdjacentHTML('beforebegin', categoryHtml);
                    } else {
                        // If there were no categories before, create a container for them
                        categoriesContainer.insertAdjacentHTML('afterbegin', `<div class="mb-3">${categoryHtml}</div>`);
                    }
                    
                    // Add option to parent dropdown
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.textContent = data.category.name;
                    categoryParentInput.appendChild(option);
                    
                    // Close modal
                    quickAddCategoryModal.hide();
                    
                    // Show success message
                    alert('دسته‌بندی با موفقیت ایجاد شد.');
                } else {
                    quickAddCategoryError.textContent = data.message || 'خطا در ذخیره دسته‌بندی.';
                    quickAddCategoryError.classList.remove('d-none');
                }
            })
            .catch(error => {
                quickAddCategoryError.textContent = 'خطا در ارتباط با سرور.';
                quickAddCategoryError.classList.remove('d-none');
                console.error('Error saving category:', error);
            })
            .finally(() => {
                // Re-enable button
                saveQuickCategoryBtn.disabled = false;
                saveQuickCategoryBtn.innerHTML = '<i class="ri-save-line me-1"></i>ذخیره دسته‌بندی';
            });
        });
    });
</script>
@endpush 