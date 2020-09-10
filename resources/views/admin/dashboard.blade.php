@extends('admin.layout.base')

@section('title')

@section('styles')
<link rel="stylesheet" href="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.css')}}">
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="row row-md">
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box box-block bg-white tile tile-1 mb-2">
                    <div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
                    <div class="t-content">
                        <h6 class="text-uppercase mb-1">NOTE BASSE</h6>
                        <h1 class="mb-1"><a href="{{route('admin.provider_score')}}">{{$latest_rides}}</a></h1>
<!--                        <span class="tag tag-danger mr-0-5">@if($cancel_rides == 0) 0.00 @else {{round($cancel_rides/$rides->count(),2)}}% @endif</span>
                        <span class="text-muted font-90">% de dépannages annulés en baisse</span>-->
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box box-block bg-white tile tile-1 mb-2">
                    <div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
                    <div class="t-content">
                        <h6 class="text-uppercase mb-1">Revenu</h6>
                        <h1 class="mb-1">{{($revenue)}}€</h1>
                        <i class="fa fa-caret-up text-success mr-0-5"></i><span>pour {{$rides->count()}} Dépannages</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box box-block bg-white tile tile-1 mb-2">
                    <div class="t-icon right"><span class="bg-primary"></span><i class="ti-view-grid"></i></div>
                    <div class="t-content">
                        <h6 class="text-uppercase mb-1">COMMISSION</h6>
                        <h1 class="mb-1">{{$total_commission}}€</h1>
                        <i class="fa fa-caret-up text-success mr-0-5"></i><span>Pour {{$rides->count()}} dépannages</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box box-block bg-white tile tile-1 mb-2">
                    <div class="t-icon right"><span class="bg-warning"></span><i class="ti-archive"></i></div>
                    <div class="t-content">
                        <h6 class="text-uppercase mb-1">Dépannages annulés</h6>
                        <h1 class="mb-1">{{$cancel_rides}}</h1>
                        <i class="fa fa-caret-down text-danger mr-0-5"></i><span>Pour @if($cancel_rides == 0) 0.00 @else {{round($cancel_rides/$rides->count(),2)}}% @endif Dépannages</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-md">
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box box-block bg-white tile tile-1 mb-2">
                    <div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
                    <div class="t-content">
                        <h6 class="text-uppercase mb-1">Nbre total d'utilisateurs</h6>
                        <h1 class="mb-1"><a href="{{route('admin.list', 1)}}">{{$newUsers}}</a></h1>
                        <span class="text-muted font-90">24h derniers nouveaux utilisateurs</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box box-block bg-white tile tile-1 mb-2">
                    <div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
                    <div class="t-content">
                        <h6 class="text-uppercase mb-1">Nbre total fournisseur</h6>
                        <h1 class="mb-1"><a href="{{route('admin.list', 2)}}">{{$newProviders}}</a></h1>
                        <i class="fa fa-caret-up text-success mr-0-5"></i><span>24h derniers nouveaux fournisseur</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box box-block bg-white tile tile-1 mb-2">
                    <div class="t-icon right"><span class="bg-primary"></span><i class="ti-view-grid"></i></div>
                    <div class="t-content">
                        <h6 class="text-uppercase mb-1">DEMANDE DE PAIEMENT</h6>
                        <h1 class="mb-1"><a href="{{route('admin.ride.statement.providersettlements')}}">{{$Pendinglist}}</a></h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
                <div class="box box-block bg-white tile tile-1 mb-2">
                    <div class="t-icon right"><span class="bg-warning"></span><i class="ti-archive"></i></div>
                    <div class="t-content">
                        <h6 class="text-uppercase mb-1">Alerte</h6>
                        <h1 class="mb-1"><a href="{{route('admin.cancel_report')}}">{{$alerts}}</a></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-md mb-2">
            <div class="col-md-8">
                <div class="box bg-white">
                    <div class="box-block clearfix">
                        <h5 class="float-xs-left">Dépannages récents</h5>
                        <div class="float-xs-right">
                            <!-- <button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-close"></i></button> -->
                        </div>
                    </div>
                    <table class="table mb-md-0">
                        <tbody>
                            <?php $diff = ['-success', '-info', '-warning', '-danger']; ?>
                            @foreach($rides as $index => $ride)
                            <tr>
                                <th scope="row">{{$index + 1}}</th>
                                <td>{{$ride->user->first_name}} {{$ride->user->last_name}}</td>
                                <td>
                                    @if($ride->status != "CANCELLED")
                                    <a class="text-primary" href="{{route('admin.request.details',$ride->id)}}"><span class="underline">Voir les détails des dépannages</span></a>
                                    @else
                                    <span>Aucun détail trouvé </span>
                                    @endif									
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ appDate($ride->created_at) }}
                                        <?php
                                        // $dateForHuman = $ride->created_at->diffForHumans();
                                        // $dateForHuman = str_replace('ago', 'passée', $dateForHuman);
                                        // $dateForHuman = str_replace('days', 'jour', $dateForHuman);
                                        // $dateForHuman = str_replace('day', 'jour', $dateForHuman);
                                        // $dateForHuman = str_replace('weeks', 'semaine', $dateForHuman);
                                        // $dateForHuman = str_replace('week', 'semaine', $dateForHuman);
                                        // $dateForHuman = str_replace('months', 'mois', $dateForHuman);
                                        // $dateForHuman = str_replace('month', 'mois', $dateForHuman);
                                        // $dateForHuman = str_replace('years', 'an', $dateForHuman);
                                        // $dateForHuman = str_replace('year', 'an', $dateForHuman);
                                        // $dateForHuman = str_replace('hours', 'heure', $dateForHuman);
                                        // $dateForHuman = str_replace('hour', 'heure', $dateForHuman);
                                        // echo $dateForHuman;
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    @if($ride->status == "COMPLETED")
                                    <span class="tag tag-success">TERMINÉ</span>
                                    @elseif($ride->status == "CANCELLED")
                                    <span class="tag tag-danger">ANNULÉ</span>
                                    @else
                                    <span class="tag tag-info">PLANNIFIÉ</span>
                                    @endif
                                </td>
                            </tr>
                            @php if ($index == 10) break; @endphp
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box bg-white">
                    <div class="box-block clearfix">
                        <h5 class="float-xs-left">Nos finances</h5>
                        <div class="float-xs-right">
                            <!-- <button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-close"></i></button> -->
                        </div>
                    </div>
                    <table class="table mb-md-0">
                        <tbody>
                            <tr>
                                <td >Chiffre d'affaire</td>
                                <td>{{currency($admin_credit)}}</td>
                            </tr>
                            <tr>
                                <td >Crédit dépanneur</td>
                                <td>{{currency($provider_credit)}}</td>
                            </tr>
                            <tr>
                                <td >Débit dépanneur</td>
                                <td>{{currency($provider_debit)}}</td>
                            </tr>
                            <tr>
                                <td >Demande de réglement</td>
                                <td>{{currency($discount)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box box-block bg-white">
            <div class="clearfix mb-1">
                <h5 class="float-xs-left">Statistiques des dépannages</h5>
                <div class="float-xs-right">
                    <button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-close"></i></button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div id="world" style="height: 400px;"></div>
                </div>
                <div class="col-md-4 demo-progress">
                    <h5 class="mb-2">Évaluation des dépanneurs</h5>
                    @if($providers->count() > 0)
                    @foreach($providers as $provider)
                    <p class="mb-0-5">{{$provider->first_name}} {{$provider->last_name}} <span class="float-xs-right">{{($provider->rating/5)*100}}%</span></p>
                    <progress class="progress progress{{$diff[array_rand($diff)]}} progress-sm" value="{{$provider->rating}}" max="5"></progress>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('main/vendor/jvectormap/jquery-jvectormap-world-mill.js')}}"></script>


<script type="text/javascript">
$(document).ready(function(){

/* Vector Map */
$('#world').vectorMap({
zoomOnScroll: false,
        map: 'world_mill',
        markers: [
                @foreach($rides as $ride)
                @if ($ride->status != "CANCELLED")
        {latLng: [{{$ride-> s_latitude}}, {{$ride-> s_longitude}}], name: '{{$ride->user->first_name}}'},
                @endif
                @endforeach

        ],
        normalizeFunction: 'polynomial',
        backgroundColor: 'transparent',
        regionsSelectable: true,
        markersSelectable: true,
        regionStyle: {
        initial: {
        fill: 'rgba(0,0,0,0.15)'
        },
                hover: {
                fill: 'rgba(0,0,0,0.15)',
                        stroke: '#fff'
                },
        },
        markerStyle: {
        initial: {
        fill: '#43b968',
                stroke: '#fff'
        },
                hover: {
                fill: '#3e70c9',
                        stroke: '#fff'
                }
        },
        series: {
        markers: [{
        attribute: 'fill',
                scale: ['#43b968', '#a567e2', '#f44236'],
                values: [200, 300, 600, 1000, 150, 250, 450, 500, 800, 900, 750, 650]
        }, {
        attribute: 'r',
                scale: [5, 15],
                values: [200, 300, 600, 1000, 150, 250, 450, 500, 800, 900, 750, 650]
        }]
        }
});
});
</script>
@endsection