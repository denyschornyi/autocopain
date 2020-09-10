@extends('admin.layout.base')

@section('title')

@section('content')
<style>
    .btn-orange {
        background-color: #f0ad4e;
        border-color: #f0ad4e;
        color: #fff;
    }

    .btn-orange:active {
        background-color: #f9a226;
    }

    .btn-blue {
        background-color: #3e70c9;
        border-color: #3e70c9;
        color: #fff;
    }

    .btn-blue:active {
        background-color: #1a57c5;
    }

    .btn_doc_type_filter {
        position: absolute;
    }
</style>
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">Dépanneurs</h5>
            <label style="margin-left: 19em;" class="btn btn-blue btn_doc_type_filter" value='Refuse'> Refuse</label>
            <label style="margin-left: 25em;" class="btn btn-danger btn_doc_type_filter" value='Aucun'> Aucun</label>
            <label style="margin-left: 31em;" class="btn btn-orange btn_doc_type_filter" value='En attente'> En attente</label>
            <label style="margin-left: 39em;" class="btn btn-success btn_doc_type_filter" value='Valide'> Valide</label>
            <!-- <label style="margin-left: 45em;" class="btn btn-blue btn_doc_type_filter value='Tout'"> Tout</label> -->
            <a href="{{ route('admin.provider.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Ajouter un nouveau dépanneur</a>
            <table class="table table-striped table-bordered dataTable" id="table-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>N° de Mobile</th>
                        <th>Dépannages acceptées</th>
                        <th>Dépannages annulées</th>
                        <th>Documents / Type de dépannage</th>
                        <th>En ligne</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($providers as $index => $provider)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td>{{$provider->first_name}} {{$provider->last_name}}</td>
                        @if(Setting::get('demo_mode', 0) == 1)
                        <td>{{ substr($provider->email, 0, 3).'****'.substr($provider->email, strpos($provider->email, "@")) }}</td>
                        @else
                        <td>{{$provider->email}}</td>
                        @endif
                        <td>{{$provider->mobile}}</td>
                        <td>{{count($provider->accepted)}}</td>
                        <td>{{ count($provider->cancelled) }}</td>
                        <td>
                            @if($provider->no_documents() == 0)
                            <a class="btn btn-danger label-right" href="{{route('admin.provider.document.index', $provider->id )}}">Aucun <span class="btn-label">0</span></a>
                            @elseif($provider->need_validate_documents() > 0)
                            <a class="btn btn-orange label-right" href="{{route('admin.provider.document.index', $provider->id )}}">En attente <span class="btn-label">{{$provider->need_validate_documents()}}</span></a>
                            @elseif($provider->validated_documents() > 0)
                            <a class="btn btn-success label-right" href="{{route('admin.provider.document.index', $provider->id )}}">Valide <span class="btn-label">{{$provider->validated_documents()}}</span></a>
                            @elseif($provider->rejected_documents() > 0)
                            <a class="btn btn-blue label-right" href="{{route('admin.provider.document.index', $provider->id )}}">Refuse <span class="btn-label">{{$provider->rejected_documents()}}</span></a>
                            @endif
                        </td>
                        <td>
                            @if($provider->service)
                            @if($provider->service->status == 'active')
                            <label class="btn btn-primary">Oui</label>
                            @else
                            <label class="btn btn-warning">Non</label>
                            @endif
                            @else
                            <label class="btn btn-danger">N/A</label>
                            @endif
                        </td>
                        <td>
                            <div class="input-group-btn">
                                @if($provider->status == 'approved')
                                <a class="btn btn-danger" href="{{route('admin.provider.disapprove', $provider->id )}}">Désactiver</a>
                                @else
                                <a class="btn btn-success" href="{{route('admin.provider.approve', $provider->id )}}">Activer</a>
                                @endif
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Détails
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.provider.request', $provider->id) }}" class="btn btn-default"><i class="fa fa-search"></i> Historique</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.provider.statement', $provider->id) }}" class="btn btn-default"><i class="fa fa-document"></i> Déclarations</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.provider.edit', $provider->id) }}" class="btn btn-default"><i class="fa fa-pencil"></i> Modifié</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.provider.destroy', $provider->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-default look-a-like" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Effacer</button>
                                        </form>
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
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>N° de Mobile</th>
                        <th>Dépannages acceptées</th>
                        <th>Dépannages annulées</th>
                        <th>Documents / Type de dépannage</th>
                        <th>En ligne</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {

        var filterByType = function(column, type) {
            $.fn.dataTableExt.afnFiltering.push(
                function(oSettings, aData, iDataIndex) {
                    // console.log(aData[column]);
                    return aData[column].indexOf(type) >= 0;
                }
            );
        };

        var saved_type = localStorage.getItem('DataTables-filter-name');
        if (saved_type != null) {
            filterByType(6, saved_type);
        }

        var table_4 = $('#table-4').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            stateSave: true
        } );

        $('.btn_doc_type_filter').on('click', function(e) {
            e.preventDefault();
            var type = $(this).attr('value');
            $.fn.dataTableExt.afnFiltering.length = 0;
            filterByType(6, type);
            $('#table-4').dataTable().fnDraw(); 
            localStorage.setItem('DataTables-filter-name', type);
        });

    });
</script>
@endsection