<span class="status-badge status-{{ $inquiry->status }}">
    {{ __(str($inquiry->status)->replace('_', ' ')->title()->toString()) }}
    <small>{{ __(str($inquiry->priority)->title()->toString()) }}</small>
</span>
