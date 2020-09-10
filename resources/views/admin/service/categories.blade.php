@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.service.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

			<h5 style="margin-bottom: 2em;">Ajouter un type de dépannage</h5>

            <form class="form-horizontal" action="{{route('admin.service.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">Nom du dépannage</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="Nom du dépannage">
					</div>
				</div>

				<div class="form-group row">
					<label for="provider_name" class="col-xs-12 col-form-label">Nom du dépanneur</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('provider_name') }}" name="provider_name" required id="provider_name" placeholder="Nom du dépanneur">
					</div>
				</div>

				<div class="form-group row">
					<label for="picture" class="col-xs-12 col-form-label">Icône de dépannage</label>
					<div class="col-xs-10">
						<input type="file" accept="image/*" name="image" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="fixed" class="col-xs-12 col-form-label">Prix de base</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('fixed') }}" name="fixed" required id="fixed" placeholder="Prix de base">
					</div>
				</div>

				<div class="form-group row">
					<label for="price" class="col-xs-12 col-form-label">Prix ​​horaire</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('price') }}" name="price" required id="price" placeholder="Prix ​​horaire">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Ajouter le type de dépannage</button>
						<a href="{{route('admin.service.index')}}" class="btn btn-default">Annuler</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
