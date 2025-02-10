<!doctype html>
<html lang="en">
    <head>
        <title>@yield('title')</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Fonts -->
        <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <!-- Styles -->
        <style>
        body {
            background: #dedede;
        }
        .page-wrap {
            min-height: 100vh;
        }
        </style>
    </head>
    <body>
    <div class="page-wrap d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <span class="display-1 d-block">403</span>
                <div class="mb-4 lead">Forbidden. You can't access this page.</div>
                <a href="{{ app('router')->has('home') ? route('home') : url('/') }}">
                    <button class="btn btn-info">
                        {{ __('Go Home') }}
                    </button>
                </a>
                </div>
            </div>
        </div>
    </div>            
    </body>
</html>
