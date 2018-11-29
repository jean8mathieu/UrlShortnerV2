@extends('master')

@section('title')
    Home
@endsection

@section('content')
    <div class="container">
        <div class="alert-message"></div>
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
    </div>

    @include("modals.generatedLink")
    @include("modals.searchResults")
@endsection

@section('scripts')
    <script src="{{ mix("/js/custom.js") }}"></script>
@endsection
