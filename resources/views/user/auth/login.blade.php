@extends('user.layout.auth')

@section('content')

<?php $login_user = asset('asset/img/login-user-bg.jpg'); ?>
<div class="full-page-bg" style="background-image: url({{$login_user}});">
    <div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
            </div>
            <div class="col-md-6 log-right">
                <div class="login-box-outer">
                    <div class="login-box row no-margin">
                        <div class="col-md-12">
                            <a class="log-blk-btn" href="{{url('register')}}">CRÉER UN NOUVEAU COMPTE</a>
                            <h3>Se connecter</h3>
                        </div>
                        @if (session()->has('message'))
                        <div class="col-md-12">
                            <div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <em> {{ session()->get('message') }}</em>
                            </div>
                        </div>
                        @endif
                        <form  role="form" method="POST" action="{{ url('/login') }}"> 
                            {{ csrf_field() }}                      
                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control" placeholder="Mot de passe" name="password" required>

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}><span> Se souvenir de moi</span>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="log-teal-btn">Connexion</button>
                            </div>
                        </form>     

                        <div class="col-md-12">
                            <p class="helper"> <a href="{{ url('/password/reset') }}">Mot de passe oublié?</a></p>   
                        </div>
                    </div>


                    <div class="log-copy"><p class="no-margin">&copy; {{date('Y')}} {{Setting::get('site_title')}}</p></div></div>
            </div>
        </div>
    </div>
</div>
@endsection