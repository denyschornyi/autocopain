@extends('provider.layout.auth')

@section('content')
<div class="col-md-12">
    <a class="log-blk-btn" href="{{ url('/provider/login') }}">DÉJÀ ENREGISTRÉ?</a>
    <h3>Créer un nouveau compte</h3>
</div>

<div class="col-md-12">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/provider/register') }}">
        {{ csrf_field() }}

        <input id="name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="Prénom" autofocus>

        @if ($errors->has('first_name'))
            <span class="help-block">
                <strong>{{ $errors->first('first_name') }}</strong>
            </span>
        @endif

        <input id="name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Nom">

        @if ($errors->has('last_name'))
            <span class="help-block">
                <strong>{{ $errors->first('last_name') }}</strong>
            </span>
        @endif

        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">

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

        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirmer mot de passe">

        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif

        <button type="submit" class="log-teal-btn">
            S'enregistrer
        </button>
    </form>
</div>
@endsection
