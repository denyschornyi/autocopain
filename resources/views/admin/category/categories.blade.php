@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.category.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

			<h5 style="margin-bottom: 2em;">Ajouter une catégorie</h5>

            <form class="form-horizontal" action="{{route('admin.category.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">Nom de la catégorie</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="Nom de la catégorie">
					</div>
				</div>

				

				

				

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Ajouter une catégorie</button>
						<a href="{{route('admin.category.index')}}" class="btn btn-default">Annuler</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
