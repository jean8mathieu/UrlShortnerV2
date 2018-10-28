@extends('master')

@section('title')
    Home
@endsection

@section('content')
    <div class="container">
        <h1 class="text-center">Generate your unique URL!</h1>
        <div class="alert"></div>
        <div class="row">
            <div class="col-md-8">
                <input type="url" name="url" id="url" class="form-control" placeholder="Url">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-primary w-100" id="generate">Generate</button>
            </div>
        </div>

        <div class="mt-3"></div>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Url</th>
                <th>Short</th>
                <th>Click</th>
            </tr>
            </thead>
            <tbody id="table"></tbody>
        </table>
    </div>

    <div class="modal fade" id="popup" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Your Generated URL</h4>
                </div>
                <div class="modal-body">
                    <p id="short"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <script src="{{ asset("/js/shortner.js") }}"></script>
@endsection
