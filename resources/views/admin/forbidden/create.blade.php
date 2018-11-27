@extends('master')

@section('title')
    Admin - Forbidden - {{isset($forbidden) ? "Edit" : "Create"}}
@endsection

@section('content')
    <div class="container">
        <form action="{{ isset($forbidden) ? route("forbidden.update", [$forbidden]) : route('forbidden.create') }}" method="{{isset($forbidden) ? "POST" : "PUT"}}">
            @csrf
            <label>Keyword:</label>
            <input type="text" name="keyword" placeholder="Keyword" class="form-control" value="{{ $forbidden->contains ?? "" }}" required>

            <div class="mt-3"></div>

            <button type="submit" class="btn btn-primary w-100">{{isset($forbidden) ? "Update" : "Create"}}</button>
        </form>
    </div>
@endsection
