<button class="status-badge status-{{ $inquiry->status }}" type="button"
        data-inquiry-status-action="{{ route('admin.inquiries.update', $inquiry) }}"
        data-inquiry-status="{{ $inquiry->status }}">
    {{ __(str($inquiry->status)->replace('_', ' ')->title()->toString()) }}
</button>
