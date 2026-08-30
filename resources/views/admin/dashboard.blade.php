@extends('admin.layout')

@section('title', __('Overview'))

@section('content')
    <div class="grid">
        @foreach ($stats as $label => $value)
            <article class="card stat">
                <span class="muted">{{ __(str($label)->replace('_', ' ')->title()->toString()) }}</span>
                <strong>{{ number_format($value) }}</strong>
            </article>
        @endforeach
    </div>

    <section class="card table-card dashboard-table-card p-0 mt-4">
        <div class="table-section-header">
            <div>
                <span class="eyebrow">{{ __('Latest activity') }}</span>
                <h2>{{ __('Recent inquiries') }}</h2>
            </div>
            <a class="btn btn-sm btn-light" href="{{ route('admin.inquiries.index') }}">{{ __('View all') }}</a>
        </div>
        <div class="responsive-table">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Contact') }}</th>
                        <th>{{ __('Request') }}</th>
                        <th>{{ __('Product') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inquiries as $inquiry)
                        <tr>
                            <td data-label="{{ __('Contact') }}">
                                <strong>{{ $inquiry->name }}</strong>
                                <small class="d-block text-body-secondary">{{ $inquiry->email }}</small>
                            </td>
                            <td data-label="{{ __('Request') }}">{{ $inquiry->subject }}</td>
                            <td data-label="{{ __('Product') }}">{{ $inquiry->product?->name ?? __('General inquiry') }}</td>
                            <td data-label="{{ __('Status') }}">
                                <span class="status-badge status-{{ $inquiry->status }}">
                                    {{ __(str($inquiry->status)->replace('_', ' ')->title()->toString()) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="empty-state" colspan="4">{{ __('No inquiries yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
