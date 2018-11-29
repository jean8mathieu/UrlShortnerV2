@extends('master')

@section('title')
    Admin - Dashboard
@endsection

@section('content')
    <div class="container">
        <div class="alert-message"></div>
        <h2>Admin Dashboard</h2>
        <hr>
        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header text-center" id="headingThree">
                    <button class="btn btn-link" type="button" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="true" aria-controls="headingThree">
                        <h2 class="mb-0">
                            Top 50 URL
                        </h2>
                    </button>
                </div>

                <div id="collapseThree" class="collapse" aria-labelledby="headingThree">
                    <div class="card card-body">

                        <table class="table table-hover urlTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>URL</th>
                                <th>Clicks</th>
                                <th>IP</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($topUrl as $url)
                                <tr data-id="{{ $url['id'] }}">
                                    <td>{{ $url['id'] }}</td>
                                    <td>
                                        <a href="{{ $url['url'] }}"
                                           target="_blank">{{ strlen($url['url']) > 75 ? substr($url['url'],0,75)."..." : $url['url'] }}</a>
                                    </td>
                                    <td>{{ $url['click'] }}</td>
                                    <td>{{ $url['ip'] }}</td>
                                    <td>
                                        <button type="button" data-id="{{ $url['id'] }}"
                                                data-href="{{ route('api.destroy', [$url['id']]) }}"
                                                class="btn btn-warning deleteUrl"><i class="far fa-trash-alt"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="urlBans" data-ip="{{ $url['ip'] }}">
                                            @if($url['banned'])
                                                <button data-id="{{ $url['id'] }}"
                                                        class="btn btn-success w-100 deleteBan">
                                                    <i class="fas fa-user"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('bans.create', ['ip' => $url['ip']]) }}"
                                                   class="btn btn-danger">
                                                    <i class="fas fa-user-slash"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100">There's no url yet</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <h3 class="text-center">Url Generated</h3>
        <table class="table table-hover urlListTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>URL</th>
                <th>IP</th>
                <th>Clicks</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($urlGenerated as $url)
                <tr data-id="{{ $url['id'] }}">
                    <td>{{ $url->id }}</td>
                    <td>
                        <a href="{{$url->url}}"
                           target="_blank">{{ strlen($url->url) > 75 ? substr($url->url,0,75)."..." : $url->url }}</a>
                    </td>
                    <td>{{ $url->ip }}</td>
                    <td>{{ $url->views->count() }}</td>
                    <td>
                        <button type="button" data-id="{{ $url->id }}"
                                data-href="{{ route('api.destroy', [$url->id]) }}"
                                class="btn btn-warning deleteUrl"><i class="far fa-trash-alt"></i></button>
                    </td>
                    <td>
                        <div class="urlBans" data-ip="{{ $url['ip'] }}">
                            @if($url['banned'])
                                <button data-id="{{ $url['id'] }}"
                                        class="btn btn-success w-100 deleteBan">
                                    <i class="fas fa-user"></i>
                                </button>
                            @else
                                <a href="{{ route('bans.create', ['ip' => $url['ip']]) }}"
                                   class="btn btn-danger">
                                    <i class="fas fa-user-slash"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100">There's currently no url</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="form-group">
            <div class="d-flex justify-content-center">
                {!! $urlGenerated->render() !!}
            </div>
        </div>


        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="true"
                                        aria-controls="headingTwo">
                                    Bans
                                </button>
                                <a href="{{ route('bans.create') }}" class="btn btn-primary float-right"><i
                                            class="fas fa-plus"></i></a>
                            </h5>
                        </div>

                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo">
                            <div class="card card-body">
                                <table class="table table-hover text-center bansTable">
                                    <thead>
                                    <tr>
                                        <th>IP</th>
                                        <th>Notes</th>
                                        <th>Updated</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($bans as $ban)
                                        <tr data-id="{{ $ban->id }}">
                                            <td>{{ $ban->ip }}</td>
                                            <td>{{ $ban->notes }}</td>
                                            <td>{{ $ban->updated_at }}</td>
                                            <td>
                                                <button class="btn btn-danger deleteBan"
                                                        data-id="{{ $ban->id }}"
                                                        data-href="{{ route('bans.destroy', [$ban]) }}">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100">There's current nobody banned</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                        data-target="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">
                                    Forbidden keywords
                                </button>
                                <a href="{{ route('forbidden.create') }}" class="btn btn-primary float-right"><i
                                            class="fas fa-plus"></i></a>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne">
                            <div class="card card-body">
                                <table class="table table-hover text-center forbiddenTable">
                                    <thead>
                                    <tr>
                                        <th>Keyword</th>
                                        <th>Updated</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    @forelse($forbiddens as $forbidden)
                                        <tr data-id="{{ $forbidden->id }}">
                                            <td>{{ $forbidden->contains }}</td>
                                            <td>{{ $forbidden->updated_at }}</td>
                                            <td><a href="{{ route('forbidden.show', [$forbidden]) }}"
                                                   class="btn btn-warning"><i class="fas fa-pen"></i></a>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger deleteForbidden"
                                                        data-keyword="{{ $forbidden->contains }}"
                                                        data-id="{{ $forbidden->id }}"
                                                        data-href="{{ route("forbidden.destroy", [$forbidden->id]) }}">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100">There's currently no keyword</td>
                                        </tr>
                                    @endforelse
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.searchResults')
@endsection

@section('scripts')
    <script src="{{ mix("/js/admin.js") }}"></script>
@endsection
