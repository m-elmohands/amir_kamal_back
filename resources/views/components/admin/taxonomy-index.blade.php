@php
    $isCategory = $type === 'category';
    $title = $isCategory ? __('Categories') : __('Brands');
    $singular = $isCategory ? __('Category') : __('Brand');
    $collection = $isCategory ? $categories : $brands;
    $newModel = $isCategory ? new \App\Models\Category : new \App\Models\Brand;
    $routeName = $isCategory ? 'admin.categories' : 'admin.brands';
@endphp

<div class="page-intro">
    <div>
        <p class="eyebrow">{{ __('Catalog organization') }}</p>
        <p class="muted mb-0">{{ __('Manage translated names, visibility, and catalog relationships.') }}</p>
    </div>
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#create-{{ $type }}-modal">
        <span aria-hidden="true">＋</span> {{ __('Add') }} {{ $singular }}
    </button>
</div>

<section class="card table-card p-0">
    <div class="responsive-table">
        <table class="table admin-table align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Slug') }}</th>
                    <th>{{ __('Products') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($collection as $item)
                    <tr>
                        <td data-label="{{ __('Name') }}">
                            <strong>{{ $item->name }}</strong>
                            <small class="d-block text-body-secondary">{{ $item->translate('ar')?->name ?: __('No Arabic translation') }}</small>
                        </td>
                        <td data-label="{{ __('Slug') }}"><code>{{ $item->slug }}</code></td>
                        <td data-label="{{ __('Products') }}">{{ $item->products_count }}</td>
                        <td data-label="{{ __('Status') }}">
                            <span class="status-badge {{ $item->is_active ? 'is-active' : 'is-hidden' }}">
                                {{ __($item->is_active ? 'Active' : 'Hidden') }}
                            </span>
                        </td>
                        <td data-label="{{ __('Actions') }}">
                            <div class="table-actions">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="modal"
                                        data-bs-target="#edit-{{ $type }}-{{ $item->id }}-modal">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-outline-danger" type="button"
                                        data-delete-action="{{ route("{$routeName}.destroy", $item) }}"
                                        data-delete-name="{{ $item->name }}">{{ __('Delete') }}</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">{{ __('No records found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div class="pagination-wrap">{{ $collection->links() }}</div>

<x-admin.modal id="create-{{ $type }}-modal" :title="__('Add').' '.$singular" size="modal-xl">
    <x-admin.taxonomy-form :type="$type" :item="$newModel" />
</x-admin.modal>

@foreach ($collection as $item)
    <x-admin.modal id="edit-{{ $type }}-{{ $item->id }}-modal" :title="__('Edit').' '.$singular" size="modal-xl">
        <x-admin.taxonomy-form :type="$type" :item="$item" />
    </x-admin.modal>
@endforeach
