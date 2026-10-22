@extends('master')

@section('title')
    Admin - Dashboard
@endsection

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <h1 class="h3 fw-semibold mb-0">Dashboard</h1>

            <form id="searchForm" class="flex-grow-1" style="max-width: 24rem" role="search">
                <div class="input-group">
                    <span class="input-group-text bg-body"><i class="bi bi-search"></i></span>
                    <input type="search" id="search" class="form-control" placeholder="Search url, short code or ID"
                           aria-label="Search urls" minlength="4">
                    <button type="submit" class="btn btn-primary" id="searchBtn">Search</button>
                </div>
            </form>
        </div>

        <div class="alert-message"></div>

        <div class="row g-3 mb-4">
            @foreach([
                ['Urls', $stats['urls'], 'bi-link-45deg'],
                ['Clicks', $stats['clicks'], 'bi-cursor'],
                ['Banned IPs', $stats['bans'], 'bi-person-slash'],
                ['Forbidden keywords', $stats['keywords'], 'bi-slash-circle'],
            ] as [$label, $value, $icon])
                <div class="col-6 col-lg-3">
                    <div class="card stat-card h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <span class="feature-icon flex-shrink-0"><i class="bi {{ $icon }}"></i></span>
                            <div>
                                <div class="stat-value">{{ number_format($value) }}</div>
                                <div class="small text-body-secondary">{{ $label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-body">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#recent" type="button"
                                role="tab">Recent urls</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#top" type="button"
                                role="tab">Top 50</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bans" type="button" role="tab">
                            Bans <span class="badge rounded-pill text-bg-secondary bansCount">{{ $bans->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#keywords" type="button"
                                role="tab">
                            Keywords <span class="badge rounded-pill text-bg-secondary forbiddenCount">{{ $forbiddens->count() }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                {{-- Recent urls --}}
                <div class="tab-pane fade show active" id="recent" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 urlListTable">
                            <thead>
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>URL</th>
                                <th>IP</th>
                                <th class="text-end">Clicks</th>
                                <th>Created</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($urlGenerated as $url)
                                <tr data-url-id="{{ $url->id }}">
                                    <td class="ps-3 text-body-secondary">{{ $url->id }}</td>
                                    <td>@include('admin.partials.url-link', ['url' => $url->url, 'short' => $url->short_url])</td>
                                    <td class="font-monospace small">{{ $url->ip }}</td>
                                    <td class="text-end">{{ number_format($url->views_count) }}</td>
                                    <td class="small text-body-secondary text-nowrap" title="{{ $url->created_at }}">
                                        {{ $url->created_at?->diffForHumans() }}
                                    </td>
                                    <td class="text-end pe-3">
                                        @include('admin.partials.url-actions', ['id' => $url->id, 'ip' => $url->ip])
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="100" class="text-center text-body-secondary py-4">There's currently no url</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($urlGenerated->hasPages())
                        <div class="d-flex justify-content-center pt-3 border-top">
                            {{ $urlGenerated->links() }}
                        </div>
                    @endif
                </div>

                {{-- Top 50 --}}
                <div class="tab-pane fade" id="top" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 urlTable">
                            <thead>
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>URL</th>
                                <th>IP</th>
                                <th class="text-end">Clicks</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($topUrl as $url)
                                <tr data-url-id="{{ $url['id'] }}">
                                    <td class="ps-3 text-body-secondary">{{ $url['id'] }}</td>
                                    <td>@include('admin.partials.url-link', ['url' => $url['url'], 'short' => $url['shortUrl']])</td>
                                    <td class="font-monospace small">{{ $url['ip'] }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($url['click']) }}</td>
                                    <td class="text-end pe-3">
                                        @include('admin.partials.url-actions', ['id' => $url['id'], 'ip' => $url['ip']])
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="100" class="text-center text-body-secondary py-4">There's no url yet</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Bans --}}
                <div class="tab-pane fade" id="bans" role="tabpanel">
                    <div class="d-flex justify-content-end p-3 border-bottom">
                        <a href="{{ route('bans.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Add ban
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 bansTable">
                            <thead>
                            <tr>
                                <th class="ps-3">IP</th>
                                <th>Notes</th>
                                <th>Updated</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($bans as $ban)
                                <tr data-ban-id="{{ $ban->id }}">
                                    <td class="ps-3 font-monospace">{{ $ban->ip }}</td>
                                    <td>{{ $ban->notes }}</td>
                                    <td class="small text-body-secondary text-nowrap" title="{{ $ban->updated_at }}">
                                        {{ $ban->updated_at?->diffForHumans() }}
                                    </td>
                                    <td class="text-end pe-3">
                                        <button type="button" class="btn btn-sm btn-outline-danger deleteBan"
                                                data-id="{{ $ban->id }}" data-ip="{{ $ban->ip }}"
                                                data-href="{{ route('bans.destroy', [$ban]) }}"
                                                title="Remove ban" aria-label="Remove ban for {{ $ban->ip }}">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="100" class="text-center text-body-secondary py-4">There's currently nobody banned</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Forbidden keywords --}}
                <div class="tab-pane fade" id="keywords" role="tabpanel">
                    <div class="d-flex justify-content-end p-3 border-bottom">
                        <a href="{{ route('forbidden.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Add keyword
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 forbiddenTable">
                            <thead>
                            <tr>
                                <th class="ps-3">Keyword</th>
                                <th>Updated</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($forbiddens as $forbidden)
                                <tr data-keyword-id="{{ $forbidden->id }}">
                                    <td class="ps-3"><code>{{ $forbidden->contains }}</code></td>
                                    <td class="small text-body-secondary text-nowrap" title="{{ $forbidden->updated_at }}">
                                        {{ $forbidden->updated_at?->diffForHumans() }}
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('forbidden.show', [$forbidden]) }}"
                                               class="btn btn-sm btn-outline-secondary" title="Edit keyword"
                                               aria-label="Edit keyword {{ $forbidden->contains }}">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger deleteForbidden"
                                                    data-keyword="{{ $forbidden->contains }}"
                                                    data-id="{{ $forbidden->id }}"
                                                    data-href="{{ route("forbidden.destroy", [$forbidden->id]) }}"
                                                    title="Delete keyword"
                                                    aria-label="Delete keyword {{ $forbidden->contains }}">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="100" class="text-center text-body-secondary py-4">There's currently no keyword</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.searchResults')
@endsection

@section('scripts')
    @vite('resources/js/admin.js')
@endsection
