@extends('master')

@section('title')
    Admin - Dashboard
@endsection

@section('content')
    <div class="container">
        <h2>Admin Dashboard</h2>
        <hr>
        <h3 class="text-center">Top 50 URL</h3>
        <table class="table table-hover">
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
                <tr>
                    <td>{{ $url['id'] }}</td>
                    <td>
                        <a href="{{ $url['url'] }}" target="_blank">{{ $url['url'] }}</a>
                    </td>
                    <td>{{ $url['click'] }}</td>
                    <td>{{ $url['ip'] }}</td>
                    <td><button type="button" data-href="{{ route('bans.destroy', [$url['id']]) }}" class="btn btn-warning"><i class="far fa-trash-alt"></i></button></td>
                    <td>
                        @if($url['banned'])
                            <a href="{{ route('bans.destroy', ['ip' => $url['ip']]) }}" class="btn btn-danger"><i class="fas fa-user"></i></a>
                        @else
                            <a href="{{ route('bans.create', ['ip' => $url['ip']]) }}" class="btn btn-danger"><i class="fas fa-user-slash"></i></a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100">There's no url yet</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="true" aria-controls="headingTwo">
                                    Bans
                                </button>
                                <a href="{{ route('bans.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus"></i></a>
                            </h5>
                        </div>

                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo">
                            <div class="card card-body">
                                <table class="table table-hover text-center">
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
                                        <tr>
                                            <td>{{ $ban->ip }}</td>
                                            <td>{{ $ban->notes }}</td>
                                            <td>{{ $ban->updated_at }}</td>
                                            <td><a href="{{ route('bans.destroy', ['ip' => $url['ip']]) }}" class="btn btn-danger"><i class="far fa-trash-alt"></i></a></td>
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
                                        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Forbidden keywords
                                </button>
                                <a href="{{ route('forbidden.create') }}" class="btn btn-primary float-right"><i class="fas fa-plus"></i></a>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne">
                            <div class="card card-body">
                                <table class="table table-hover text-center">
                                    <thead>
                                    <tr>
                                        <th>Keyword</th>
                                        <th>Updated</th>
                                        <th>Edit</th>
                                    </tr>
                                    @forelse($forbiddens as $forbidden)
                                        <tr>
                                            <td>{{ $forbidden->contains }}</td>
                                            <td>{{ $forbidden->updated_at }}</td>
                                            <td><a href="{{ route('forbidden.show', [$forbidden]) }}" class="btn btn-warning"><i class="fas fa-pen"></i></a>
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
@endsection
