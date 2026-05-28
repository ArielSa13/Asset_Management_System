<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\ActivityLogService;

class CategoryController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLog
    ) {}

    public function index()
    {
        $categories = Category::withCount('assets')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        $this->activityLog->log('create', 'category', "Created category: {$category->name}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        $this->activityLog->log('update', 'category', "Updated category: {$category->name}");

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->assets()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki asset.');
        }

        $this->activityLog->log('delete', 'category', "Deleted category: {$category->name}");
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
