@extends('provider.layout.auth')

@section('content')
<div class="col-md-12">
    <a class="log-blk-btn" href="{{ url('/provider/register') }}">CRÉER UN NOUVEAU COMPTE</a>
    <h3>Sign In</h3>
</div>

<div class="col-md-12">
    <form role="form" method="POST" action="{{ url('/provider/login') }}">
        {{ csrf_field() }}

        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" autofocus>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <input id="password" type="password" class="form-control" name="password" placeholder="Mot de passe">

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif

        <div class="checkbox">           
            <input type="checkbox" name="remember"><span> Se souvenir de moi</span>
            
        </div>


        <button type="submit" class="log-teal-btn">
            Connexion
        </button>

        <p class="helper"><a href="{{ url('/provider/password/reset') }}">Mot de passe oublié?</a></p>   
    </form>
</div>
@endsection
