@extends('admin.layout.base')

@section('title')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">Types de dépannages</h5>
                <a href="{{ route('admin.service.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Ajouter un dépannage</a>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom de la catégorie</th>
                            <th>Nom du service</th>
                            <th>Nom du dépanneur</th>
                            <th>Prix de base</th>
                            <th>Prix ​​horaire</th>
                            <th>Icône du dépannage</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $index => $service)
                    
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>@if($service->category_id > 0)
                               {{$service->category->name}}
                                @else
                                NA
                                @endif
                            </td>
                            <td>{{$service->name}}</td>
                            <td>{{$service->provider_name}}</td>
                            <td>{{($service->fixed)}}€</td>
                            <td>{{($service->price)}}€</td>
                            <td>
                                @if($service->image) 
                                    <img src="{{img($service->image)}}" style="height: 50px" >
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.service.destroy', $service->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{ route('admin.service.edit', $service->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Modifié</a>
                                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Effacer</button>
                                </form>
                            </td>
                        </tr>
                       
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Nom de la catégorie</th>
                            <th>Nom du service</th>
                            <th>Nom du dépanneur</th>
                            <th>Prix de base</th>
                            <th>Prix ​​horaire</th>
                            <th>Icône du dépannage</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection