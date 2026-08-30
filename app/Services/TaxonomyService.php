<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaxonomyService
{
    public function save(Request $request, Model $model, array $data, AuditService $audit, string $action): void
    {
        $data['is_active'] = $request->boolean('is_active');
        $model->fill($data)->save();
        $this->invalidateCache();
        $audit->record($request, $action, $model);
    }

    public function delete(Request $request, Model $model, AuditService $audit, string $action): void
    {
        $audit->record($request, $action, $model);
        $model->delete();
        $this->invalidateCache();
    }

    private function invalidateCache(): void
    {
        Cache::forget('catalog.filters.en');
        Cache::forget('catalog.filters.ar');
    }
}
