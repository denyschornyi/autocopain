@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.service.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

            <h5 style="margin-bottom: 2em;">Mettre à jours une catégorie</h5>

            <form class="form-horizontal" action="{{route('admin.service.update', $service->id )}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">



                <div class="form-group row">
                    <label for="name" class="col-xs-2 col-form-label">Choisir une catégorie</label>
                    <div class="col-xs-10">
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Choisir une catégorie</option>
                            @foreach($category as $category)
                            @if($category['id'] == $service->category_id)
                            <option value="{!! $category['id'] !!}" selected="selected">{!! $category['name'] !!}</option>
                            @else
                            <option value="{!! $category['id'] !!}">{!! $category['name'] !!}</option>
                            @endif

                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="name" class="col-xs-2 col-form-label">Nom du dépannage</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->name }}" name="name" required id="name" placeholder="Nom du dépannage">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="provider_name" class="col-xs-2 col-form-label">Nom du dépanneur</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->provider_name }}" name="provider_name" required id="provider_name" placeholder="Nom du dépanneur">
                    </div>
                </div>

                <div class="form-group row">

                    <label for="image" class="col-xs-2 col-form-label">Image</label>
                    <div class="col-xs-10">
                        @if(isset($service->image))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{img($service->image)}}">
                        @endif
                        <input type="file" accept="image/*" name="image" class="dropify form-control-file" id="image" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fixed" class="col-xs-2 col-form-label">Prix de base</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->fixed }}" name="fixed" required id="fixed" placeholder="Prix de base">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="price" class="col-xs-2 col-form-label">Prix ​​horaire</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->price }}" name="price" required id="price" placeholder="Prix ​​horaire">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="notice" class="col-xs-2 col-form-label">Notice</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->notice }}" name="notice" id="notice" placeholder="Notice">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">Mettre à jours</button>
                        <a href="{{route('admin.service.index')}}" class="btn btn-default">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection