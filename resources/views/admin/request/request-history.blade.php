@extends('admin.layout.base')

@section('title')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">Historique des dépannages</h5>
                @if(count($requests) != 0)
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Dépannage Id</th>
                            <th>Nom de l'utilisateur</th>
                            <th>Nom du dépanneur</th>
                            <th>Date & Heure</th>
                            <th>Statut</th>
                            <th>Montant</th>
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
                            <a href="{{ route('admin.user.show', $request->user->id) }}">{{$request->user->first_name}} </a>
                            @endif
                            @if(isset($request->user->last_name))
                            <a href="{{ route('admin.user.show', $request->user->id) }}">{{$request->user->last_name}}</a>
                            @endif
                        </td>
                        <td>
                            @if(isset($request->provider_id) && $request->provider_id > 0)
                            @if(isset($request->provider->first_name))
                            <a href="{{route('admin.provider.document.index', $request->provider->id )}}">{{$request->provider->first_name}} </a>
                            @endif
                            @if(isset($request->provider->last_name))
                            <a href="{{route('admin.provider.document.index', $request->provider->id )}}">{{$request->provider->last_name}}</a>
                            @endif
                            @else
                            N/A
                            @endif
                        </td>
                        <td>{{date('d/m/Y H:i:s',strtotime($request->created_at))}}</td>
                        <td>
                            @if($request->status == "COMPLETED")
                            <span class="tag tag-success">TERMINÉ</span>
                            @elseif($request->status == "CANCELLED")
                            <span class="tag tag-danger">ANNULÉ</span>
                            @else
                            <span class="tag tag-info">PLANNIFIÉ</span>
                            @endif
                        </td>
                        <td>
                            @if($request->payment != "")
                            {{($request->payment->total)}}€
                            @else
                            N/A
                            @endif
                        </td>
                        <td>{{$request->payment_mode}}</td>
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
                            <th>Nom du dépanneur</th>
                            <th>Date & Heure</th>
                            <th>Statut</th>
                            <th>Montant</th>
                            <th>Mode de paiement</th>
                            <th>Statut de paiement</th>
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