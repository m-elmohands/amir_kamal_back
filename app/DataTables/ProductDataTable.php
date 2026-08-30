<?php

namespace App\DataTables;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductDataTable
{
    public function __construct(private readonly DataTables $dataTables) {}

    public function response(): JsonResponse
    {
        $locale = app()->getLocale();
        $query = DB::table('products')
            ->leftJoin('product_translations as pt', fn ($join) => $join->on('pt.product_id', '=', 'products.id')->where('pt.locale', $locale))
            ->leftJoin('product_translations as pe', fn ($join) => $join->on('pe.product_id', '=', 'products.id')->where('pe.locale', 'en'))
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('category_translations as ct', fn ($join) => $join->on('ct.category_id', '=', 'categories.id')->where('ct.locale', $locale))
            ->leftJoin('category_translations as ce', fn ($join) => $join->on('ce.category_id', '=', 'categories.id')->where('ce.locale', 'en'))
            ->whereNull('products.deleted_at')
            ->select(['products.id', 'products.sku', 'products.availability_status', 'products.stock_quantity', 'products.is_active', 'products.created_at'])
            ->selectRaw('COALESCE(pt.name, pe.name) as name')
            ->selectRaw('COALESCE(pt.summary, pe.summary) as summary')
            ->selectRaw('COALESCE(ct.name, ce.name) as category_name');

        return $this->dataTables->query($query)
            ->filterColumn('name', fn ($query, string $keyword) => $query->where(fn ($nested) => $nested->where('pt.name', 'like', "%{$keyword}%")->orWhere('pe.name', 'like', "%{$keyword}%")->orWhere('pt.summary', 'like', "%{$keyword}%")->orWhere('pe.summary', 'like', "%{$keyword}%")))
            ->filterColumn('category_name', fn ($query, string $keyword) => $query->where(fn ($nested) => $nested->where('ct.name', 'like', "%{$keyword}%")->orWhere('ce.name', 'like', "%{$keyword}%")))
            ->orderColumn('name', 'COALESCE(pt.name, pe.name) $1')
            ->orderColumn('category_name', 'COALESCE(ct.name, ce.name) $1')
            ->editColumn('name', fn ($row) => '<strong>'.e($row->name).'</strong><br><span class="muted">'.e(str($row->summary)->limit(70)).'</span>')
            ->editColumn('is_active', fn ($row) => '<span class="status-badge '.($row->is_active ? 'is-active' : 'is-hidden').'">'.__($row->is_active ? 'Active' : 'Hidden').'</span>')
            ->editColumn('availability_status', fn ($row) => view('admin.products.availability', ['status' => $row->availability_status, 'quantity' => $row->stock_quantity])->render())
            ->addColumn('actions', fn ($row) => view('admin.products.actions', ['productId' => $row->id])->render())
            ->rawColumns(['name', 'availability_status', 'is_active', 'actions'])
            ->toJson();
    }
}
