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
                    <td><a href="{{ route('bans.destroy', [$url['id']]) }}" class="btn btn-warning">Delete</a></td>
                    <td><a href="{{ route('bans.create', ['ip' => $url['ip']]) }}" class="btn btn-danger">Ban</a></td>
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
                <h3>Bans <a href="{{ route('bans.create') }}" class="btn btn-primary float-right">Add +</a></h3>
                <table class="table table-hover text-center">
                    <thead>
                    <tr>
                        <th>IP</th>
                        <th>Notes</th>
                        <th>Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($bans as $ban)
                        <tr>
                            <td>{{ $ban->ip }}</td>
                            <td>{{ $ban->notes }}</td>
                            <td>{{ $ban->updated_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100">There's current nobody banned</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h3>Forbidden keywords <a href="{{ route('forbidden.create') }}" class="btn btn-primary float-right">Add +</a></h3>
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
                        <td><a href="{{ route('forbidden.show', [$forbidden]) }}">Edit</a></td>
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
@endsection
