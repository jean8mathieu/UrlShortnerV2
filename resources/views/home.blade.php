@extends('master')

@section('title', 'UrlShortner - Free & fast link shortener')

@section('content')
    <section class="hero py-5">
        <div class="container py-lg-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-5 fw-bold mb-3">Shorten your links</h1>
                    <p class="lead text-body-secondary mb-4">
                        Paste a long URL and get a short link you can share anywhere. It's free and fast!
                    </p>

                    <div class="alert-message text-start"></div>

                    <form id="shortenForm" class="shorten-form" novalidate>
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-body"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" name="url" id="url" class="form-control"
                                   placeholder="https://example.com/a/very/long/link"
                                   aria-label="URL to shorten" autocomplete="off" autofocus>
                            <button type="submit" class="btn btn-primary px-4" id="generate">
                                <span class="spinner-border spinner-border-sm d-none me-1" aria-hidden="true"></span>
                                Shorten
                            </button>
                        </div>
                    </form>

                    <div id="result" class="card result-card shadow-sm mt-4 text-start d-none" aria-live="polite">
                        <div class="card-body d-flex flex-wrap align-items-center gap-3">
                            <div class="flex-grow-1" style="min-width: 0">
                                <div class="small text-body-secondary">Your short link</div>
                                <a id="shortLink" class="fs-5 fw-semibold text-break" target="_blank" rel="noopener"></a>
                            </div>
                            <button type="button" class="btn btn-outline-primary" id="copyLink">
                                <i class="bi bi-clipboard"></i> <span>Copy</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.ad', ['class' => 'my-4'])

    <section class="container py-4">
        <div class="row g-4 text-center text-md-start">
            <div class="col-md-4">
                <span class="feature-icon mb-3"><i class="bi bi-lightning-charge"></i></span>
                <h2 class="h6 fw-semibold">Instant</h2>
                <p class="text-body-secondary small mb-0">Your short link is ready the moment you hit Shorten.</p>
            </div>
            <div class="col-md-4">
                <span class="feature-icon mb-3"><i class="bi bi-gift"></i></span>
                <h2 class="h6 fw-semibold">Free</h2>
                <p class="text-body-secondary small mb-0">No account, no sign-up. Just paste and share.</p>
            </div>
            <div class="col-md-4">
                <span class="feature-icon mb-3"><i class="bi bi-shield-check"></i></span>
                <h2 class="h6 fw-semibold">Screened</h2>
                <p class="text-body-secondary small mb-0">Links are checked against a blocklist to keep spam and abuse out.</p>
            </div>
        </div>
    </section>

    @include('partials.ad', ['class' => 'my-4'])
@endsection

@section('scripts')
    @vite('resources/js/shortner.js')
@endsection
