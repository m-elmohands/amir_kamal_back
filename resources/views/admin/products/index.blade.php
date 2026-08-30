@extends('admin.layout')
@section('title', __('Products'))
@section('content')
<div class="page-intro"><p class="muted mb-0">{{ __('Search, sort, and paginate products without loading the full catalog.') }}</p><a class="btn btn-primary" href="{{ route('admin.products.create') }}" data-modal-url="{{ route('admin.products.create', ['modal' => 1]) }}" data-modal-title="{{ __('Add product') }}">{{ __('Add product') }}</a></div>
<div id="products-table-error" class="error table-error" hidden>{{ __('Products could not be loaded. Please try again.') }}</div>
<section class="card table-card datatable-card p-0">
    <table id="products-table" class="table admin-table admin-datatable align-middle mb-0"
           data-url="{{ route('admin.products.data') }}" data-error-target="products-table-error"
           data-column-names="name,sku,category_name,is_active,actions">
        <thead><tr><th>{{ __('Product') }}</th><th>{{ __('SKU') }}</th><th>{{ __('Category') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
    </table>
</section>
@endsection
