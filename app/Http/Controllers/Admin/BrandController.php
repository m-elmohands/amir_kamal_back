<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveBrandRequest;
use App\Models\Brand;
use App\Services\AuditService;
use App\Services\TaxonomyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Brand::class);

        return view('admin.brands.index', [
            'brands' => Brand::with('translations')->withCount('products')->orderByTranslation('name')->paginate(15),
        ]);
    }

    public function store(SaveBrandRequest $request, TaxonomyService $service, AuditService $audit): RedirectResponse
    {
        $service->save($request, new Brand, $request->validated(), $audit, 'brand.created');

        return back()->with('success', __('Brand created successfully.'));
    }

    public function update(SaveBrandRequest $request, Brand $brand, TaxonomyService $service, AuditService $audit): RedirectResponse
    {
        $service->save($request, $brand, $request->validated(), $audit, 'brand.updated');

        return back()->with('success', __('Brand updated successfully.'));
    }

    public function destroy(Request $request, Brand $brand, TaxonomyService $service, AuditService $audit): RedirectResponse
    {
        $this->authorize('delete', $brand);
        $service->delete($request, $brand, $audit, 'brand.deleted');

        return back()->with('success', __('Brand moved to trash.'));
    }
}
