@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

        <div class="box box-block bg-white">
            <h5 class="mb-1">Dépannages planifiés</h5>
            @if(count($requests) != 0)
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dépannage Id</th>
                        <th>Nom de l'utilisateur</th>
                        <th>Nom du dépannage</th>
                        <th>Date et heure planifiées</th>
                        <th>Statut</th>
                        <th>Mode de paiement</th>
                        <th>Statut de paiement</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $index => $request)
                    <tr>
                        <td>{{$index + 1}}</td>

                        <td>{{$request->booking_id}}</td>
                        <td>
                            @if(isset($request->user->first_name))
                            {{$request->user->first_name}} 
                            @endif
                            @if(isset($request->user->last_name))
                            {{$request->user->last_name}}
                            @endif
                        </td>
                        <td>
                            @if(isset($request->provider_id) && $request->provider_id > 0)
                            @if(isset($request->provider->first_name))
                            {{$request->provider->first_name}} 
                            @endif
                            @if(isset($request->provider->last_name))
                            {{$request->provider->last_name}}
                            @endif
                            @else
                            N/A
                            @endif
                        </td>
                        <td>
                            @if($request->schedule_at != "0000-00-00 00:00:00")
                            {{date('Y D, M d - H:i A',strtotime($request->schedule_at))}}
                            @else
                            - 
                            @endif
                        </td>
                        <td>
                            @if($request->status == "SCHEDULED")
                            PLANNIFIÉ
                            @else
                            {{$request->status}}
                            @endif
                        </td>
                        <td>
                            @if($request->payment_mode == "CARD")
                            CB
                            @else
                            {{$request->payment_mode}}
                            @endif
                        </td>
                        <td>
                            @if($request->paid)
                            Payé
                            @else
                            Non payé
                            @endif
                        </td>
                        <td>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Action
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.request.details', $request->id) }}" class="btn btn-default"><i class="fa fa-search"></i> Plus de détails</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Dépannage Id</th>
                        <th>Nom de l'utilisateur</th>
                        <th>Nom du dépannage</th>
                        <th>Date et heure planifiées</th>
                        <th>Statut</th>
                        <th>Mode de paiement</th>
                        <th>Statut du paiement</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
            @else
            <h6 class="no-result">Aucun résultat trouvé</h6>
            @endif 
        </div>

    </div>
</div>
@endsection