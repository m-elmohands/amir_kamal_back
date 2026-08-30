<button class="btn btn-sm btn-outline-danger" type="button"
        data-delete-action="{{ route('admin.inquiries.destroy', $inquiry) }}"
        data-delete-name="{{ $inquiry->subject }}">{{ __('Delete') }}</button>
@php
    $workflow = [
        'contact' => $inquiry->name.($inquiry->company ? ' · '.$inquiry->company : ''),
        'contact_details' => collect([$inquiry->email, $inquiry->phone])->filter()->implode(' · '),
        'request' => $inquiry->subject,
        'message' => $inquiry->message,
        'application' => collect([
            $inquiry->product?->name,
            $inquiry->requested_quantity ? __('Quantity').': '.$inquiry->requested_quantity : null,
            $inquiry->machine_model ? __('Machine').': '.$inquiry->machine_model : null,
            $inquiry->material ? __('Material').': '.$inquiry->material : null,
        ])->filter()->implode(' · '),
        'status' => $inquiry->status,
        'priority' => $inquiry->priority,
        'assigned_to' => $inquiry->assigned_to,
        'quoted_amount' => $inquiry->quoted_amount,
        'next_follow_up_at' => $inquiry->next_follow_up_at?->format('Y-m-d\TH:i'),
        'internal_notes' => $inquiry->internal_notes,
    ];
@endphp
<button class="btn btn-sm btn-primary" type="button"
        data-inquiry-status-action="{{ route('admin.inquiries.update', $inquiry) }}"
        data-inquiry="{{ json_encode($workflow) }}">
    {{ __('Manage') }}
</button>
