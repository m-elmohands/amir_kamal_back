<span class="status-badge availability-{{ $status }}">
    {{ __(str($status)->replace('_', ' ')->title()->toString()) }}
    @if ($quantity !== null)
        <span class="badge-count">{{ number_format($quantity) }}</span>
    @endif
</span>
