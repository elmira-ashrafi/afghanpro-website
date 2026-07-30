<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the product categories.
     */
    public function index(Request $request)
    {
        $query = ProductCategory::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        // Apply parent filter
        if ($request->filled('parent_id')) {
            if ($request->parent_id === '0') {
                $query->whereNull('parent_id');
            } elseif ($request->parent_id === 'has_parent') {
                $query->whereNotNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }
        
        // Apply active status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        $categories = $query->orderBy('sort_order')->paginate(15);
        
        return view('dashboard.shop.admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new product category.
     */
    public function create()
    {
        $categories = ProductCategory::where('parent_id', null)->orderBy('name')->get();
        return view('dashboard.shop.admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created product category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:product_categories,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        ProductCategory::create($validated);

        return redirect()->route('dashboard.shop.admin.categories.index')
            ->with('success', 'دسته‌بندی با موفقیت ایجاد شد');
    }

    /**
     * Show the form for editing the specified product category.
     */
    public function edit(ProductCategory $category)
    {
        $categories = ProductCategory::where('id', '!=', $category->id)
            ->where(function($query) use ($category) {
                // Exclude the category and its children to prevent circular references
                $query->where('parent_id', null)
                    ->orWhere(function($query) use ($category) {
                        $query->where('parent_id', '!=', $category->id);
                    });
            })
            ->orderBy('name')
            ->get();
            
        return view('dashboard.shop.admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified product category in storage.
     */
    public function update(Request $request, ProductCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:product_categories,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Prevent self-parenting
        if (isset($validated['parent_id']) && $validated['parent_id'] == $category->id) {
            $validated['parent_id'] = null;
        }

        $category->update($validated);

        return redirect()->route('dashboard.shop.admin.categories.index')
            ->with('success', 'دسته‌بندی با موفقیت بروزرسانی شد');
    }

    /**
     * Remove the specified product category from storage.
     */
    public function destroy(ProductCategory $category)
    {
        // Move child categories to parent level (if any)
        ProductCategory::where('parent_id', $category->id)
            ->update(['parent_id' => $category->parent_id]);
            
        $category->delete();

        return redirect()->route('dashboard.shop.admin.categories.index')
            ->with('success', 'دسته‌بندی با موفقیت حذف شد');
    }
    
    /**
     * AJAX endpoint to create a category without leaving the product form
     */
    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:product_categories,id',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // Set active status
        $validated['is_active'] = true;
        
        $category = ProductCategory::create($validated);
        
        return response()->json([
            'success' => true,
            'category' => $category,
            'message' => 'دسته‌بندی با موفقیت ایجاد شد'
        ]);
    }
} 