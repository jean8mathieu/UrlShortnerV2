@extends('master')

@section('title')
    Admin - Forbidden - {{isset($forbidden) ? "Edit" : "Create"}}
@endsection

@section('content')
    <div class="container">
        <div class="alert"></div>

        <form action="{{ isset($forbidden) ? route("forbidden.update", [$forbidden]) : route('forbidden.store') }}" method="{{isset($forbidden) ? "PUT" : "POST"}}">
            @csrf
            <label>Keyword:</label>
            <input type="text" name="keyword" placeholder="Keyword" class="form-control" value="{{ $forbidden->contains ?? "" }}" required>

            <div class="mt-3"></div>

            <button type="button" class="btn btn-primary w-100 submitForm">{{isset($forbidden) ? "Update" : "Create"}}</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ mix("/js/admin.js") }}"></script>
@endsection