@props(['id', 'title', 'size' => 'modal-lg'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $size }}">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="eyebrow">{{ __('Admin workspace') }}</span>
                    <h2 class="modal-title fs-5" id="{{ $id }}-title">{{ $title }}</h2>
                </div>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">{{ $slot }}</div>
        </div>
    </div>
</div>
