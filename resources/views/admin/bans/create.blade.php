@extends('master')

@section('title')
    Admin - Bans - {{ isset($ban) ? "Edit" : "Create" }}
@endsection

@section('content')
    <div class="container">
        <form action="{{ isset($ban) ? route('bans.update') : route('bans.store') }}" method="{{isset($ban) ? "PUT" : "POST"}}">
            @csrf
            <label>IP:</label>
            <input type="text" name="ip" placeholder="IP" class="form-control" value="{{ $ban->ip ?? (Request::get('ip') ?? "") }}" required>

            <div class="mt-3"></div>

            <label>Notes:</label>
            <input type="text" name="notes" placeholder="Notes" class="form-control" {{ $ban->notes ?? "" }} required>

            <div class="mt-3"></div>

            <button type="submit" class="btn btn-primary w-100">Create</button>
        </form>
    </div>
@endsection
