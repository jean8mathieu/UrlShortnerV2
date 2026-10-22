<div class="d-inline-flex gap-1">
    <button type="button" class="btn btn-sm btn-outline-secondary deleteUrl"
            data-id="{{ $id }}" data-href="{{ route('api.destroy', [$id]) }}"
            title="Delete url" aria-label="Delete url {{ $id }}">
        <i class="bi bi-trash3"></i>
    </button>
    <div class="urlBans" data-ip="{{ $ip }}">
        @if($bannedIps->has($ip))
            <button type="button" class="btn btn-sm btn-outline-success deleteBan"
                    data-id="{{ $bannedIps[$ip]->id }}" data-ip="{{ $ip }}"
                    data-href="{{ route('bans.destroy', [$bannedIps[$ip]->id]) }}"
                    title="Unban {{ $ip }}" aria-label="Unban {{ $ip }}">
                <i class="bi bi-person-check"></i>
            </button>
        @else
            <a href="{{ route('bans.create', ['ip' => $ip]) }}" class="btn btn-sm btn-outline-danger"
               title="Ban {{ $ip }}" aria-label="Ban {{ $ip }}">
                <i class="bi bi-person-slash"></i>
            </a>
        @endif
    </div>
</div>
