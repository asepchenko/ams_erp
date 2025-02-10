@extends('layouts.app')
@section('content')
<div class="login-box">
    <div class="login-logo">
        <div class="login-logo">
            <a href="#">
                <b>{{ trans('global.site_title') }}</b> ERP
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <img src="{{ asset('/img/amsfull.png') }}" alt="logo" class="center" width="300px" height="90px"/>
            <hr>
            <p class="login-box-msg">Sign in to start your session</p>
            @if(\Session::has('message'))
                <p class="alert alert-info">
                    {{ \Session::get('message') }}
                </p>
            @endif
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group has-feedback">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="NIK" name="nik">
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="{{ trans('global.login_password') }}" id="password" name="password">
                    </div>
                    <!--<p id="capsWarning">Capslock is ON</p>-->
                </div>
                <div class="row">
                    <div class="col-8">
                        <input type="checkbox" name="remember"> {{ trans('global.remember_me') }}
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" id="btnLogin" class="btn btn-primary btn-block btn-flat">{{ trans('global.login') }}</button>
                        
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <p class="mb-1">
                <!--<a class="" href="{{ route('password.request') }}">
                    {{ trans('global.forgot_password') }}
                </a>-->
            </p>
            <p class="mb-0">
            </p>
            <p class="mb-1">
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
@endsection