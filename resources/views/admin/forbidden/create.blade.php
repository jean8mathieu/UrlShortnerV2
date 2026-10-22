@extends('master')

@section('title')
    Admin - Forbidden - {{isset($forbidden) ? "Edit" : "Create"}}
@endsection

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <a href="{{ route('dashboard') }}#keywords" class="small d-inline-block mb-3">
                    <i class="bi bi-arrow-left"></i> Back to dashboard
                </a>

                <div class="alert-message"></div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 fw-semibold mb-4">{{isset($forbidden) ? "Edit keyword" : "Add a forbidden keyword"}}</h1>

                        <form class="ajax-form" action="{{ isset($forbidden) ? route("forbidden.update", [$forbidden]) : route('forbidden.store') }}" method="{{isset($forbidden) ? "PUT" : "POST"}}">
                            @csrf
                            <div class="mb-4">
                                <label for="keyword" class="form-label">Keyword</label>
                                <input type="text" name="keyword" id="keyword" placeholder="e.g. spam-domain" class="form-control"
                                       value="{{ $forbidden->contains ?? "" }}" required>
                                <div class="form-text">Public urls containing this text are refused, and existing ones are deleted.</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 submitForm">{{isset($forbidden) ? "Update" : "Create"}}</button>
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
