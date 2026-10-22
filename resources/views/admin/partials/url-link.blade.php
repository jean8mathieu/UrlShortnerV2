{{-- Only http(s) urls become links; anything else (javascript:, data:...) is shown as text --}}
<div class="url-cell">
    @if(preg_match('#^https?://#i', $url))
        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="d-block text-truncate"
           title="{{ $url }}">{{ $url }}</a>
    @else
        <span class="d-block text-truncate text-danger" title="{{ $url }}">{{ $url }}</span>
    @endif
    <span class="small text-body-secondary font-monospace">/{{ $short }}</span>
</div>
