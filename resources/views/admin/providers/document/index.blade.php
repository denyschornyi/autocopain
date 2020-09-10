@extends('admin.layout.base')

@section('title')

@section('content')
<style>
    .text_setting {
        font-size: 16px;
        padding: 10px;
        /*font-weight: bold;*/
    }
    .text_setting a {
        padding: 10px;
        width: 150px;
    }
    .center {
        text-align: center;
    }
    .btn-orange {
        background-color: #f0ad4e;
        border-color: #f0ad4e;
        color: #fff;
    }
    .btn-blue {
        background-color: #3e70c9;
        border-color: #3e70c9;
        color: #fff;
    }
    .col-space {
        padding: 10px;
    }
</style>
<div class="content-area py-1">
    <div class="container-fluid">


        <div class="row">
            <div class="col-xs-6">
                <div class="box box-block bg-white">
                    <div class="row text_setting">
                        <div class="col-xs-4">
                            <img width="100%" src="{{img($Provider->avatar)}}">
                        </div>
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-12 col-space">
                                    <div class="row">
                                        <div class="col-xs-10 col-space">
                                            <b>Nom complet:</b> {{$Provider->first_name}} {{$Provider->last_name}}
                                        </div>
                                        <div class="col-xs-2 col-space">
                                            <button type="submit" form="rib" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-space">
                                    <b>email:</b> {{$Provider->email}}
                                </div>
                                <div class="col-xs-12 col-space">
                                    <b>Mobile:</b> {{$Provider->mobile}}
                                </div>
                                <div class="col-xs-12 col-space">
                                    <b style="float: left; margin-right: 5px;">RIB:</b> 
                                    <form id="rib" name="rib" method="POST" action="{{ route('admin.provider.rib_store') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="providerId" value="{{$Provider->id}}">
                                        <input type="text" name="rib" required="" value="{{$Provider->rib}}">
                                    </form>
                                </div>
                                <!--                                <div class="col-xs-6">
                                                                    <a href="{{url('/')}}/admin/provider/{{$Provider->id}}/bank-details" class="btn btn-primary">Bank Details</a>
                                                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="box box-block bg-white">
                    <div class="row text_setting">
                        <div class="col-xs-6 offset-md-4">
                            <a href="{{url('/')}}/admin/provider/{{$Provider->id}}/statement" class="btn btn-primary">History</a>
                        </div>
                    </div>
                    <div class="row text_setting center">
                        <div class="col-xs-6">
                            @if($Provider->no_documents() == 0)
                            <a class="btn btn-danger label-right" href="javascript::void();">Aucun <span class="btn-label">0</span></a>
                            @elseif($Provider->need_validate_documents() > 0)
                            <a class="btn btn-orange label-right" href="javascript::void();">En attente <span class="btn-label">{{$Provider->need_validate_documents()}}</span></a>
                            @elseif($Provider->validated_documents() > 0)
                            <a class="btn btn-success label-right" href="javascript::void();">Valide <span class="btn-label">{{$Provider->validated_documents()}}</span></a>
                            @elseif($Provider->rejected_documents() > 0)
                            <a class="btn btn-blue label-right" href="javascript::void();">Refuse <span class="btn-label">{{$Provider->rejected_documents()}}</span></a>
                            @endif
                        </div>
                        <div class="col-xs-6">
                            <a href="{{url('/')}}/admin/provider/{{$Provider->id}}/send-email" class="btn btn-primary">Send Email</a>
                        </div>
                    </div>
                    <div class="row text_setting center">
                        <div class="col-xs-6">
                            <div class="row text_setting">
                                <div class="col-xs-6">
                                    <a style="width:120px;" href="{{url('/')}}/admin/provider/{{$Provider->id}}/validate" class="btn btn-success">Valide</a>
                                </div>
                                <div class="col-xs-6">
                                    <a style="width:120px;" href="{{url('/')}}/admin/provider/{{$Provider->id}}/reject" class="btn btn-danger">No Valide</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row text_setting">
                                <a href="{{url('/')}}/admin/provider/{{$Provider->id}}/email-history" class="btn btn-primary">Email History</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-block bg-white">
            <form action="{{ route('admin.provider.document.store', $Provider->id) }}" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-xs-12">
                        @if($ProviderService->count() > 0)

                        <br>
                        <h6>Dépannages selectionnés :  </h6>
                        <table class="table table-striped table-bordered dataTable">
                            <thead>
                                <tr>
                                    <th>Nom du dépannage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ProviderService as $service)
                                <tr>
                                    <td>{{ $service->service_type->name }}</td>
                                    <td>
                                        <a href="{{route('admin.destory.service',$service->id)}}" class="btn btn-danger btn-large" form="form-delete">Effacer</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nom du dépannage</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        @endif
                        <hr>
                    </div>

                    <div class="col-xs-3">
                        <select class="form-control input" name="service_type" required>
                            @forelse($ServiceTypes as $Type)
                            <option value="{{ $Type->id }}">{{ $Type->name }}</option>
                            @empty
                            <option>- Veuiller créer un type de dépannage -</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-xs-3">
                        <button class="btn btn-primary btn-block" type="submit">Ajouter un dépannage</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="box box-block bg-white">
            <h5 class="mb-1">Documents du dépanneur</h5>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type de document</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($Provider->documents))
                    @foreach($Provider->documents as $Index => $Document)
                    <tr>
                        <td>{{ $Index + 1 }}</td>
                        <td>
                            @if(isset($Document->document->name))
                            {{ $Document->document->name }}
                            @else
                            --
                            @endif
                        </td>
                        <td>{{ $Document->status }}</td>
                        <td>
                            <div class="input-group-btn">
                                <a href="{{ route('admin.provider.document.edit', [$Provider->id, $Document->id]) }}"><span class="btn btn-success btn-large">Voir</span></a>
                                <button class="btn btn-danger btn-large" form="form-delete">Effacer</button>
                                <form action="{{ route('admin.provider.document.update_document', [$Provider->id, $Document->id]) }}" method="POST" enctype="multipart/form-data" style="display:inline-block">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <button type="submit" class="btn btn-primary btn-large" style="margin-left:1em">Mettre à jours</button>
                                    <input type="file" class="form-control" name="document" required="" style="border:none;background:rgba(0,0,0,0);">
                                </form>
                                <form action="{{ route('admin.provider.document.destroy', [$Provider->id, $Document->id]) }}" method="POST" id="form-delete">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <th colspan="4">No record found</th>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Type de document</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>
@endsection