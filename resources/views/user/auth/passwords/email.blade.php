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
                        <a class="log-blk-btn" href="{{url('login')}}">DÉJÀ ENREGISTRÉ?</a>
                        <h3>Réinitialiser le mot de passe</h3>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}

                        <div class="col-md-12">
                            <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif                        
                        </div>

                        
                        <div class="col-md-12">
                            <button class="log-teal-btn" type="submit">ENVOYER NOUVEAU MOT DE PASSE</button>
                        </div>
                    </form>     

                    <div class="col-md-12">
                        <p class="helper">Ou <a href="{{route('login')}}">Connectez-vous</a> avec votre compte d'utilisateur.</p>   
                    </div>

                </div>


                <div class="log-copy"><p class="no-margin">&copy;{{date('Y')}} {{ Setting::get('site_title', 'Tranxit')  }}</p></div>
                </div>
            </div>
        </div>
    </div>
@endsection
