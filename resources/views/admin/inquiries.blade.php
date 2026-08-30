@extends('admin.layout')
@section('title', __('Inquiries'))
@section('content')
<div id="inquiries-table-error" class="error table-error" hidden>{{ __('Inquiries could not be loaded. Please try again.') }}</div>
<section class="card table-card datatable-card p-0">
    <table id="inquiries-table" class="table admin-table admin-datatable align-middle mb-0"
           data-url="{{ route('admin.inquiries.data') }}" data-error-target="inquiries-table-error"
           data-column-names="name,subject,status,owner,follow_up,actions" data-non-orderable="owner,follow_up,actions" data-non-searchable="status,owner,follow_up,actions">
        <thead><tr><th>{{ __('Contact') }}</th><th>{{ __('Request') }}</th><th>{{ __('Stage') }}</th><th>{{ __('Owner') }}</th><th>{{ __('Next follow-up') }}</th><th>{{ __('Actions') }}</th></tr></thead>
    </table>
</section>

<x-admin.modal id="inquiry-status-modal" :title="__('Manage inquiry workflow')" size="lg">
    <form method="post" data-inquiry-status-form>
        @csrf
        @method('PATCH')
        <section class="inquiry-context mb-4" aria-label="{{ __('Inquiry details') }}">
            <div><span>{{ __('Contact') }}</span><strong data-inquiry-value="contact"></strong><small data-inquiry-value="contact_details"></small></div>
            <div><span>{{ __('Request') }}</span><strong data-inquiry-value="request"></strong><small data-inquiry-value="application"></small></div>
            <p data-inquiry-value="message"></p>
        </section>
        <div class="row g-3">
            <div class="col-12 col-md-6"><label class="form-label" for="inquiry-status">{{ __('Stage') }}</label><select class="form-select" id="inquiry-status" name="status">@foreach (['new', 'in_progress', 'quoted', 'won', 'lost', 'resolved'] as $status)<option value="{{ $status }}">{{ __(str($status)->replace('_', ' ')->title()->toString()) }}</option>@endforeach</select></div>
            <div class="col-12 col-md-6"><label class="form-label" for="inquiry-priority">{{ __('Priority') }}</label><select class="form-select" id="inquiry-priority" name="priority">@foreach (['low', 'normal', 'high', 'urgent'] as $priority)<option value="{{ $priority }}">{{ __(str($priority)->title()->toString()) }}</option>@endforeach</select></div>
            <div class="col-12 col-md-6"><label class="form-label" for="inquiry-owner">{{ __('Owner') }}</label><select class="form-select" id="inquiry-owner" name="assigned_to"><option value="">{{ __('Unassigned') }}</option>@foreach ($assignees as $assignee)<option value="{{ $assignee->id }}">{{ $assignee->name }}</option>@endforeach</select></div>
            <div class="col-12 col-md-6"><label class="form-label" for="inquiry-quote">{{ __('Quoted amount') }}</label><input class="form-control" id="inquiry-quote" type="number" min="0" step="0.01" name="quoted_amount"></div>
            <div class="col-12"><label class="form-label" for="inquiry-follow-up">{{ __('Next follow-up') }}</label><input class="form-control" id="inquiry-follow-up" type="datetime-local" name="next_follow_up_at"></div>
            <div class="col-12"><label class="form-label" for="inquiry-notes">{{ __('Internal notes') }}</label><textarea class="form-control" id="inquiry-notes" name="internal_notes" rows="4"></textarea><small class="form-text text-body-secondary">{{ __('Visible to administrators only.') }}</small></div>
        </div>
        <div class="modal-footer px-0 pb-0 mt-4">
            <button class="btn btn-light" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
        </div>
    </form>
</x-admin.modal>
@endsection
