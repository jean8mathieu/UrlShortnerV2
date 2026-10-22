@extends('master')

@section('title', (isset($ban) ? 'Edit ban' : 'Ban an IP') . ' - UrlShortner')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <a href="{{ route('dashboard') }}#bans" class="small d-inline-block mb-3">
                    <i class="bi bi-arrow-left"></i> Back to dashboard
                </a>

                <div class="alert-message"></div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 fw-semibold mb-4">{{ isset($ban) ? "Edit ban" : "Ban an IP" }}</h1>

                        <form class="ajax-form" action="{{ isset($ban) ? route('bans.update') : route('bans.store') }}" method="{{isset($ban) ? "PUT" : "POST"}}">
                            @csrf
                            <div class="mb-3">
                                <label for="ip" class="form-label">IP address</label>
                                <input type="text" name="ip" id="ip" placeholder="203.0.113.7" class="form-control font-monospace"
                                       value="{{ $ban->ip ?? (Request::get('ip') ?? "") }}" required>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">Notes</label>
                                <input type="text" name="notes" id="notes" placeholder="Why is this IP banned?"
                                       class="form-control" value="{{ $ban->notes ?? "" }}" required>
                                <div class="form-text">Every url created from this IP will be deleted.</div>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 submitForm">
                                <i class="bi bi-person-slash"></i> Ban IP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/admin.js')
@endsection
