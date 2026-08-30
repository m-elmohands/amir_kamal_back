<button class="btn btn-sm btn-outline-danger" type="button"
        data-delete-action="{{ route('admin.inquiries.destroy', $inquiry) }}"
        data-delete-name="{{ $inquiry->subject }}">{{ __('Delete') }}</button>
