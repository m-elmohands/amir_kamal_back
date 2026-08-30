@extends('admin.layout')
@section('title', __('Inquiries'))
@section('content')
<div id="inquiries-table-error" class="error table-error" hidden>{{ __('Inquiries could not be loaded. Please try again.') }}</div>
<section class="card table-card datatable-card p-0">
    <table id="inquiries-table" class="table admin-table admin-datatable align-middle mb-0"
           data-url="{{ route('admin.inquiries.data') }}" data-error-target="inquiries-table-error"
           data-column-names="name,subject,message,status,actions">
        <thead><tr><th>{{ __('Contact') }}</th><th>{{ __('Request') }}</th><th>{{ __('Message') }}</th><th>{{ __('Status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
    </table>
</section>

<x-admin.modal id="inquiry-status-modal" :title="__('Update inquiry status')" size="">
    <form method="post" data-inquiry-status-form>
        @csrf
        @method('PATCH')
        <label class="form-label" for="inquiry-status">{{ __('Status') }}</label>
        <select class="form-select" id="inquiry-status" name="status">
            @foreach (['new', 'in_progress', 'resolved'] as $status)
                <option value="{{ $status }}">{{ __(str($status)->replace('_', ' ')->title()->toString()) }}</option>
            @endforeach
        </select>
        <div class="modal-footer px-0 pb-0 mt-4">
            <button class="btn btn-light" type="button" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
        </div>
    </form>
</x-admin.modal>
@endsection
