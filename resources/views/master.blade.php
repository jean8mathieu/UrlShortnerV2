<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>JMDev - @yield('title')</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ mix("/css/app.css") }}">
    <script src="/js/jquery.min.js"></script>
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.4.2/css/all.css"
          integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <meta content="{{ route("home") }}" name="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Come generate your short url here! It's free and fast!">
    <meta name="author" content="Jean-Mathieu">


    <link rel="icon" href="/img/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon"/>
</head>
<body>
<nav class="navbar navbar-light bg-dark text-white">
    <div class="container">
        <a class="navbar-brand text-white"
           href="{{ Auth::user() ? route("dashboard") : route("home") }}">UrlShortner </a>
        <div class="form-inline">
            {{--<input class="form-control mr-sm-2" id="search" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-primary my-2 my-sm-0" id="searchBtn" type="button">Search</button>--}}

            @if(Auth::user())
                <a href="{{ route("logout") }}" class="btn btn-primary ml-2">Log out</a>
            @endif
        </div>
    </div>
</nav>

@if(!Request::is('auth/*'))
    <div class="container">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- UrlShortner2 -->
        <ins class="adsbygoogle"
             style="display:inline-block;width:970px;height:90px"
             data-ad-client="ca-pub-5923775871016604"
             data-ad-slot="2627761713"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
@endif

@yield('content')

@if(!Request::is('auth/*'))
    <div class="container">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- UrlShortner2 -->
        <ins class="adsbygoogle"
             style="display:inline-block;width:970px;height:90px"
             data-ad-client="ca-pub-5923775871016604"
             data-ad-slot="2627761713"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
@endif

<footer class="footer bg-dark text-white">
    <div class="container">
        <p class="text-center">Copyright © JMDev - {{ date("Y") }}</p>
    </div>
</footer>
<script src="/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.4.2/js/all.js"
        integrity="sha384-wp96dIgDl5BLlOXb4VMinXPNiB32VYBSoXOoiARzSTXY+tsK8yDTYfvdTyqzdGGN"
        crossorigin="anonymous"></script>
@yield('scripts')
</body>
</html>
