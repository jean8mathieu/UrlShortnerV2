<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>JMDev - @yield('title')</title>
    <script>
        //Follow the OS light/dark preference before the CSS paints
        document.documentElement.setAttribute('data-bs-theme',
            window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    </script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <meta content="{{ route("home") }}" name="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Come generate your short url here! It's free and fast!">
    <meta name="author" content="Jean-Mathieu">


    <link rel="icon" href="/img/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon"/>
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar border-bottom bg-body">
    <div class="container">
        <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="{{ route("home") }}">
            <i class="bi bi-link-45deg fs-3 lh-1"></i> UrlShortner
        </a>

        @if(Auth::user())
            <ul class="navbar-nav flex-row gap-3 me-auto ms-2">
                <li class="nav-item">
                    <a href="{{ route("home") }}" @class(['nav-link', 'active' => Request::routeIs('home')])
                       @if(Request::routeIs('home')) aria-current="page" @endif>
                        <i class="bi bi-scissors"></i> <span class="d-none d-sm-inline">Shorten</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("dashboard") }}" @class(['nav-link', 'active' => Request::is('admin/*')])
                       @if(Request::is('admin/*')) aria-current="page" @endif>
                        <i class="bi bi-speedometer2"></i> <span class="d-none d-sm-inline">Dashboard</span>
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <span class="text-body-secondary small d-none d-md-inline">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->username }}
                </span>
                <a href="{{ route("logout") }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Log out
                </a>
            </div>
        @endif
    </div>
</nav>

<main class="flex-grow-1">
    @yield('content')
</main>

<footer class="site-footer border-top bg-body py-3">
    <div class="container text-center text-body-secondary">
        Copyright © JMDev - {{ date("Y") }}
    </div>
</footer>
@yield('scripts')
</body>
</html>
