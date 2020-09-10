@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h4>Détails des dépannages</h4>
            <a href="{{ route('admin.request.history') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

            <br>
            <br>

            <div class="row">

                <div class="col-md-6">

                    <dl class="row">

                        <dt class="col-sm-4">Dépannage ID :</dt>
                        <dd class="col-sm-8">{{$request->booking_id ? $request->booking_id : '-' }}</dd>

                        <dt class="col-sm-4">Nom de l'utilisateur :</dt>
                        <dd class="col-sm-8">{{ $request->user ? $request->user->first_name : "User Deleted"}}</dd>

                        <dt class="col-sm-4">Non du dépanneur :</dt>
                        <dd class="col-sm-8">{{ $request->provider ? $request->provider->first_name : "Not Assigned" }}</dd>

                        @if($request->status == 'SCHEDULED')

                        <dt class="col-sm-4">Dépannage planifié :</dt>
                        <dd class="col-sm-8">
                            @if($request->schedule_at != "0000-00-00 00:00:00")
                            {{date('d/m/Y H:i:s',strtotime($request->schedule_at))}}
                            @else
                            - 
                            @endif
                        </dd>


                        @else

                        <dt class="col-sm-4">Heure de début :</dt>
                        <dd class="col-sm-8">
                            @if($request->started_at != "0000-00-00 00:00:00")
                            {{date('d/m/Y H:i:s',strtotime($request->started_at))}}
                            @else
                            - 
                            @endif
                        </dd>

                        <dt class="col-sm-4">Heure de fin :</dt>
                        <dd class="col-sm-8">
                            @if($request->finished_at != "0000-00-00 00:00:00") 
                            {{date('d/m/Y H:i:s',strtotime($request->finished_at))}}
                            @else
                            - 
                            @endif
                        </dd>

                        @endif


                        <dt class="col-sm-4">Lieu du dépannage :</dt>
                        <dd class="col-sm-8">{{$request->s_address ? $request->s_address : '-' }}</dd>

                        @if($request->payment != "")
                        <dt class="col-sm-4">Prix de base :</dt>
                        <dd class="col-sm-8">{{$request->payment->fixed ? ($request->payment->fixed) : (' 0.00')}}€</dd>

                        <dt class="col-sm-4">Prix horaire :</dt>
                        <dd class="col-sm-8">{{$request->payment->time_price ? ($request->payment->time_price) : (' 0.00')}}€</dd>


                        <dt class="col-sm-4">Taxe :</dt>
                        <dd class="col-sm-8">{{$request->payment->tax ? ($request->payment->tax) : (' 0.00')}}€</dd>

                        <dt class="col-sm-4">Montant total :</dt>
                        <dd class="col-sm-8">
                            {{$request->payment->total ? ($request->payment->total) : (' 0.00')}}€
                        </dd>
                        @endif

                        <dt class="col-sm-4">@lang('main.service') Statut : </dt>
                        <dd class="col-sm-8">
                            @if($request->status == "COMPLETED")
                            COMPLETÉ
                            @elseif($request->status == "CANCELLED")
                            ANNULÉ
                            @else
                            PLANNIFIÉ
                            @endif
                        </dd>

                    </dl>
                </div>
                <?php
                $map_icon = asset('asset/marker.png');
                $static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=1000x400&maptype=terrian&format=png&visual_refresh=true&markers=icon:" . $map_icon . "%7C" . $request->s_latitude . "," . $request->s_longitude . "&key=" . env('GOOGLE_MAP_KEY');
                ?>
                <div class="col-md-6">
                    <div id="map" style="background-image: url({{$static_map}}) ;background-repeat: no-repeat;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('styles')
<style type="text/css">

    #map {
        height: 100%;
        min-height: 400px; 
    }

</style>
@endsection

