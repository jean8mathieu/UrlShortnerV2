@extends('master')

@section('title')
    Log in
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <span class="feature-icon mb-2"><i class="bi bi-shield-lock"></i></span>
                            <h1 class="h4 fw-semibold mb-0">Admin login</h1>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                                <i class="bi bi-exclamation-octagon-fill"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        <form id="loginForm" method="post" action="{{ route('postLogin') }}">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" name="username" id="username" class="form-control"
                                       placeholder="Username" value="{{ old('username') }}"
                                       autocomplete="username" required autofocus>
                                <label for="username">Username</label>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password" name="password" id="password" class="form-control"
                                       placeholder="Password" autocomplete="current-password" required>
                                <label for="password">Password</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100" id="validate">Log in</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
