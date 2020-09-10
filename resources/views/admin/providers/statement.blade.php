@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">

            <div style="text-align: center;padding: 20px;color: blue;font-size: 24px;">
                <p><strong>
                        <span>Sur tous les bénéfices : {{($revenue[0]->overall)}}€</span>
                        <br>
                        <span>Sur toutes les Commission : {{($revenue[0]->commission)}}€</span>
                    </strong></p>
            </div>

            <div class="row">

                <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2">
                        <div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">Nbre total des dépannages</h6>
                            <h1 class="mb-1">{{$rides->count()}}</h1>
                            <span class="text-muted font-90">% de dépannages annulés en baisse</span>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2">
                        <div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">Revenu</h6>
                            <h1 class="mb-1">{{($revenue[0]->overall)}}€</h1>
                            <i class="fa fa-caret-up text-success mr-0-5"></i><span>Pour {{$rides->count()}} dépannages</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2">
                        <div class="t-icon right"><span class="bg-warning"></span><i class="ti-archive"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">Dépannages annulés</h6>
                            <h1 class="mb-1">{{$cancel_rides}}</h1>
                            <i class="fa fa-caret-down text-danger mr-0-5"></i><span>pour @if($cancel_rides == 0) 0.00 @else {{round($cancel_rides/$rides->count(),2)}}% @endif dépannages</span>
                        </div>
                    </div>
                </div>

                <div class="row row-md mb-2" style="padding: 15px;">
                    <div class="col-md-12">
                        <div class="box bg-white">
                            <div class="box-block clearfix">
                                <h5 class="float-xs-left">Gains</h5>
                                <div class="float-xs-right">
                                </div>
                            </div>

                            @if(count($rides) != 0)
                            <table class="table table-striped table-bordered dataTable" id="table-2">
                                <thead>
                                    <tr>
                                        <td>ID</td>
                                        <td>Demande ID</td>
                                        <td>Lieu du dépannage</td>
                                        <td>Détail du dépannage</td>
                                        <td>Commission</td>
                                        <td>En date du</td>
                                        <td>Statut</td>
                                        <td>Gagné</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $diff = ['-success', '-info', '-warning', '-danger']; ?>
                                    @foreach($rides as $index => $ride)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$ride->booking_id}}</td>
                                        <td>
                                            @if($ride->s_address != '')
                                            {{$ride->s_address}}
                                            @else
                                            Not Provided
                                            @endif
                                        </td>

                                        <td>
                                            @if($ride->status != "CANCELLED")
                                            <a class="text-primary" href="{{route('admin.request.details',$ride->id)}}"><span class="underline">Voir le détail des dépannages</span></a>
                                            @else
                                            <span>Aucun détail trouvé </span>
                                            @endif									
                                        </td>
                                        <td>{{($ride->payment['commision'])}}€</td>
                                        <td>
                                            <span class="text-muted">{{date('d/m/Y',strtotime($ride->created_at))}}</span>
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
                                        <td>{{($ride->payment['fixed'] + $ride->payment['distance'])}}€</td>

                                    </tr>
                                    @endforeach

                                <tfoot>
                                    <tr>
                                        <td>ID</td>
                                        <td>Demande ID</td>
                                        <td>Lieu du dépannage</td>
                                        <td>Détail du dépannage</td>
                                        <td>Commission</td>
                                        <td>En date du</td>
                                        <td>Statut</td>
                                        <td>Gagné</td>
                                    </tr>
                                </tfoot>
                                </table>
                                @else
                                <h6 class="no-result">Aucun résultat trouvé</h6>
                            @endif 

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection
