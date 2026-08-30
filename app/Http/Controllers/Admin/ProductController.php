<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index');
    }

    public function data(ProductDataTable $dataTable): JsonResponse
    {
        return $dataTable->response();
    }

    public function create(Request $request): View
    {
        return $this->form($request, new Product);
    }

    public function edit(Request $request, Product $product): View
    {
        return $this->form($request, $product);
    }

    private function form(Request $request, Product $product): View
    {
        $data = [
            'product' => $product->loadMissing('translations'),
            'categories' => Category::with('translations')->orderByTranslation('name')->get(),
            'brands' => Brand::with('translations')->orderByTranslation('name')->get(),
        ];

        return view($request->boolean('modal') ? 'admin.products._form' : 'admin.products.form', $data);
    }

    public function store(SaveProductRequest $request, AuditService $audit): RedirectResponse
    {
        return $this->save($request, new Product, $audit, 'created');
    }

    public function update(SaveProductRequest $request, Product $product, AuditService $audit): RedirectResponse
    {
        return $this->save($request, $product, $audit, 'updated');
    }

    private function save(SaveProductRequest $request, Product $product, AuditService $audit, string $action): RedirectResponse
    {
        $data = $request->validated();
        foreach (['en', 'ar'] as $locale) {
            if (! empty($data[$locale]['name'])) {
                $data[$locale]['specifications'] = $this->parseSpecifications($data[$locale]['specifications_text'] ?? '');
                unset($data[$locale]['specifications_text']);
            } else {
                unset($data[$locale]);
            }
        }
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $product->fill($data)->save();
        Cache::forget('catalog.filters.en');
        Cache::forget('catalog.filters.ar');
        $audit->record($request, "product.{$action}", $product);

        return redirect()->route('admin.products.index')->with('success', "Product {$action} successfully.");
    }

    private function parseSpecifications(string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value))->filter()->mapWithKeys(function (string $line) {
            $parts = array_map('trim', explode(':', $line, 2));

            return count($parts) === 2 ? [$parts[0] => $parts[1]] : [];
        })->all();
    }

    public function destroy(Request $request, Product $product, AuditService $audit): RedirectResponse
    {
        $audit->record($request, 'product.deleted', $product);
        $product->delete();

        return back()->with('success', 'Product moved to trash.');
    }
}
