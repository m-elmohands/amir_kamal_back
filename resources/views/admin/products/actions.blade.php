<div class="table-actions">
    <a class="btn btn-sm btn-light" href="{{ route('admin.products.edit', $productId) }}"
       data-modal-url="{{ route('admin.products.edit', [$productId, 'modal' => 1]) }}"
       data-modal-title="{{ __('Edit product') }}">{{ __('Edit') }}</a>
    <button class="btn btn-sm btn-outline-danger" type="button"
            data-delete-action="{{ route('admin.products.destroy', $productId) }}"
            data-delete-name="{{ __('this product') }}">{{ __('Delete') }}</button>
</div>
