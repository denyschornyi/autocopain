@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h3>Comptabilité des dépanneurs</h3>

            <div class="row">

                <div class="row row-md mb-2" style="padding: 15px;">
                    <div class="col-md-12">
                        <div class="box bg-white">
                            <div class="box-block clearfix">
                                <h5 class="float-xs-left">Bénéfices</h5>
                                <div class="float-xs-right">
                                </div>
                            </div>

                            @if(count($Providers) != 0)
                            <table class="table table-striped table-bordered dataTable" id="table-2">
                                <thead>
                                    <tr>
                                        <td>Nom du dépanneur</td>
                                        <td>N° de Mobile</td>
                                        <td>Statut</td>
                                        <td>Total des dépannages</td>
                                        <td>Total des bénéfices</td>
                                        <td>Commission</td>
                                        <td>Rejoint le</td>
                                        <td>Détails</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $diff = ['-success', '-info', '-warning', '-danger']; ?>
                                    @foreach($Providers as $index => $provider)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin.provider.document.index', $provider->id )}}">{{$provider->first_name}} 
                                            {{$provider->last_name}}</a>
                                        </td>
                                        <td>
                                            {{$provider->mobile}}
                                        </td>
                                        <td>
                                            @if($provider->status == "approved")
                                            <span class="tag tag-success">Approuvé</span>
                                            @elseif($provider->status == "banned")
                                            <span class="tag tag-danger">Rejeté</span>
                                            @elseif($provider->status == "onboarding")
                                            <span class="tag tag-info">Vérification</span>
                                            @else
                                            <span class="tag tag-info">{{$provider->status}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($provider->rides_count)
                                            {{$provider->rides_count}}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if($provider->payment)
                                            {{($provider->payment[0]->overall)}}€
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if($provider->payment)
                                            {{($provider->payment[0]->commission)}}€
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if($provider->created_at)
                                            {{date('d/m/Y',strtotime($provider->created_at))}}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('admin.provider.statement', $provider->id)}}">Voir par dépannages</a>
                                        </td>
                                    </tr>
                                    @endforeach

                                <tfoot>
                                    <tr>
                                        <td>Nom du dépanneur</td>
                                        <td>N° de Mobile</td>
                                        <td>Statut</td>
                                        <td>Total des dépannages</td>
                                        <td>Total des bénéfices</td>
                                        <td>Commission</td>
                                        <td>Rejoint le</td>
                                        <td>Détails</td>
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
