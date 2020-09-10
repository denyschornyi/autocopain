@extends('admin.layout.base')

@section('title')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">Nom de la catégorie</h5>
                <a href="{{ route('admin.category.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Ajouter une nouvelle catégorie</a>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom de la catégorie</th>
                            <th>N° d'emplacement</th>
                             <th>Image de la catégorie</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $index => $service)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$service->name}}</td>
                            <td>{{$service->orderNu}}</td>
                            <td>
                                @if($service->image) 
                                    <img src="{{ img($service->image) }}" style="height: 50px" >
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.category.destroy', $service->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{ route('admin.category.edit', $service->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Modifié</a>
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
                            <th>N° d'emplacement</th>
                             <th>Image de la catégorie</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection