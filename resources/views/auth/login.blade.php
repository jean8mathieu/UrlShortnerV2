@extends('master')

@section('title')
    Log in
@endsection

@section('content')
    <div class="container">
        <div class="alert"></div>
        <form id="loginForm" method="post" action="{{ route('postLogin') }}">
            @csrf
            <div class="col-md-6 ml-auto mr-auto">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="col-md-12">
                        <input type="text" name="username" placeholder="Username" id="username" class="form-control">
                    </div>
                </div>
                <div class="mt-3"></div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="password" name="password" placeholder="Password" id="password" class="form-control">
                    </div>
                </div>
                <div class="mt-3"></div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary w-100" id="validate">Log in</button>
                    </div>
                </div>
            </div>


        </form>
    </div>
@endsection
