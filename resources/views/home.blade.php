@extends('master')

@section('title')
    Home
@endsection

@section('content')
    <nav class="navbar navbar-light bg-dark text-white">
        <div class="container">
            <a class="navbar-brand">UrlShortner </a>
            <div class="form-inline">
                <input class="form-control mr-sm-2" id="search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-primary my-2 my-sm-0" id="searchBtn" type="button">Search</button>
            </div>
        </div>
    </nav>

    <div class="container">

        <div class="alert"></div>
        <div class="row">
            <div class="col-md-10">
                <input type="url" name="url" id="url" class="form-control" placeholder="Url">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary w-100" id="generate">Generate</button>
            </div>
        </div>

        <div class="mt-3"></div>
        <div id="tableResult"></div>

        <div class="push"></div>

        <footer class="footer">
            <div id="troll" style="position:absolute;top: 95.75%; left: 100%;">
                <img src="/img/run.gif">
            </div>
        </footer>
    </div>

    @include("modals.generatedLink")
    @include("modals.searchResults")

    <script src="{{ mix("/js/shortner.js") }}"></script>
@endsection
