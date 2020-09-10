@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h4>Détails utilisateur</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="box bg-white user-1">
                        <?php $background = asset('admin/assets/img/photos-1/4.jpg'); ?>
                        <!--<div class="u-img img-cover" style="background-image: url({{$background}});"></div>-->
                        <div class="u-content">
                            <div class="avatar box-64" style="height: auto; margin-top: 10px;">
                                <img style="height: 100%;" class="b-a-radius-circle shadow-white" src="{{img($user->picture)}}" alt="">
                                <i class="status bg-success bottom right"></i>
                            </div>
                            <h5><a class="text-black" href="#">{{$user->first_name}} {{$user->last_name}}</a></h5>
                            <p class="text-muted">Note : {{$user->rating}}</p>
                            <p class="text-muted">Email : {{$user->email}}</p>
                            <p class="text-muted">N° de Mobile : {{$user->mobile}}</p>
                            <!--<p class="text-muted">Sexe : {{$user->gender}}</p>-->
                            <p class="text-muted">Crédit restant : {{currency($user->wallet_balance)}}</p>
                        </div>
                        <div class="row" style="text-align: center; margin: 10px;">
                            <div class="col-md-2">
                                <a style="display: block; width: 100%;" href="{{ route('admin.user.request', $user->id) }}" class="btn btn-info"><i class="fa fa-search"></i> Historique</a>
                            </div>
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-2">
                                <a style="width: 100%;" href="{{url('/')}}/admin/user/{{$user->id}}/send-email" class="btn btn-primary"><i class="fa fa-envelope"></i> Send Email</a>
                            </div>
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-2">
                                <a style="width: 100%;" href="{{url('/')}}/admin/user/{{$user->id}}/email-history" class="btn btn-primary"><i class="fa fa-envelope"></i> Email History</a>
                            </div>
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-2">
                                <a style="display: block; width: 100%;" href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Modifié</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
