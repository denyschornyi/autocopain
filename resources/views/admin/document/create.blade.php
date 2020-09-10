@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.document.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

            <h5 style="margin-bottom: 2em;">Ajouter un document</h5>

            <form class="form-horizontal" action="{{route('admin.document.store')}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">Nom du document</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="Nom du document">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">Type du document</label>
                    <div class="col-xs-10">
                        <select name="type">
                            <option value="DOCUMENT">Revision du document</option>
                            <option value="DRIVER">DRIVER</option>
                            <option value="VEHICLE">VEHICLE</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="zipcode" class="col-xs-12 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">Ajouter le document</button>
                        <a href="{{route('admin.document.index')}}" class="btn btn-default">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
