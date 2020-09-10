@extends('admin.layout.base')

@section('title')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">Code promo</h5>
                <a href="{{ route('admin.promocode.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Ajouter un code promo</a>

                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code promo </th>
                            <th>Remise </th>
                            <th>Expiration</th>
                            <th>Statut</th>
                            <th>Nombre d'utilisation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($promocodes as $index => $promo)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$promo->promo_code}}</td>
                            <td>{{$promo->discount}}</td>
                            <td>
                                {{date('d-m-Y',strtotime($promo->expiration))}}
                            </td>
                            <td>
                                @if(date("Y-m-d") <= $promo->expiration)
                                    <span class="tag tag-success">Valide</span>
                                @else
                                    <span class="tag tag-danger">Expiration</span>
                                @endif
                            </td>
                            <td>
                                {{promo_used_count($promo->id)}}
                            </td>
                            <td>
                                <form action="{{ route('admin.promocode.destroy', $promo->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{ route('admin.promocode.edit', $promo->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Modifié</a>
                                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Effacer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Code promo </th>
                            <th>Remise </th>
                            <th>Expiration</th>
                            <th>Statut</th>
                            <th>Nombre d'utilisation</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection