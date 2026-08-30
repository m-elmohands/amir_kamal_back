<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveCategoryRequest;
use App\Models\Category;
use App\Services\AuditService;
use App\Services\TaxonomyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);

        return view('admin.categories.index', [
            'categories' => Category::with('translations')->withCount('products')->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function store(SaveCategoryRequest $request, TaxonomyService $service, AuditService $audit): RedirectResponse
    {
        $service->save($request, new Category, $request->validated(), $audit, 'category.created');

        return back()->with('success', __('Category created successfully.'));
    }

    public function update(SaveCategoryRequest $request, Category $category, TaxonomyService $service, AuditService $audit): RedirectResponse
    {
        $service->save($request, $category, $request->validated(), $audit, 'category.updated');

        return back()->with('success', __('Category updated successfully.'));
    }

    public function destroy(Request $request, Category $category, TaxonomyService $service, AuditService $audit): RedirectResponse
    {
        $this->authorize('delete', $category);
        $service->delete($request, $category, $audit, 'category.deleted');

        return back()->with('success', __('Category moved to trash.'));
    }
}
