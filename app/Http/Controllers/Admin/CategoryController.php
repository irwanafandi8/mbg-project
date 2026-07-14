<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\AuditLog;
use App\Models\ComplaintCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * List all categories.
     */
    public function index(Request $request): View
    {
        $query = ComplaintCategory::withCount('complaints');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $categories = $query->latest()->paginate(10)->withQueryString();
        $activeCategories = ComplaintCategory::where('is_active', true)->count();
        $inactiveCategories = ComplaintCategory::where('is_active', false)->count();

        return view('admin.categories.index', compact('categories', 'activeCategories', 'inactiveCategories'));
    }

    /**
     * Show create category form.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a new category.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $category = ComplaintCategory::create($request->validated());
        AuditLog::log('create_category', ComplaintCategory::class, $category->id, null, $category->toArray());

        return redirect()->route(admin_route_name() . '.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update a category.
     */
    public function update(Request $request, ComplaintCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', "unique:complaint_categories,name,{$category->id}"],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $old = $category->toArray();
        $category->update($validated);
        AuditLog::log('update_category', ComplaintCategory::class, $category->id, $old, $category->fresh()->toArray());

        return redirect()->route(admin_route_name() . '.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Show edit category form.
     */
    public function edit(ComplaintCategory $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Delete a category.
     */
    public function destroy(ComplaintCategory $category): RedirectResponse
    {
        if ($category->complaints()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki aduan.');
        }

        AuditLog::log('delete_category', ComplaintCategory::class, $category->id, $category->toArray(), null);
        $category->delete();

        return redirect()->route(admin_route_name() . '.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Toggle category active status.
     */
    public function toggleStatus(ComplaintCategory $category): RedirectResponse
    {
        $category->update(['is_active' => !$category->is_active]);

        return back()->with('success', 'Status kategori berhasil diperbarui.');
    }
}
