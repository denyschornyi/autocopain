@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">Nom de la catégorie
    	    <a href="{{ route('admin.category.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

			<h5 style="margin-bottom: 2em;">Mettre à jours une catégorie</h5>

            <form class="form-horizontal" action="{{route('admin.category.update', $service->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Nom de la catégorie</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $service->name }}" name="name" required id="name" placeholder="Nom de la catégorie">
					</div>
				</div>

				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">N° d'emplacement</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{  $service->orderNu }}" name="orderNu" id="order" placeholder="N° d'emplacement">
					</div>
				</div>

				
				<div class="form-group row">
					
					<label for="image" class="col-xs-2 col-form-label">Image de la catégorie</label>
					<div class="col-xs-10">
					@if(isset($service->image))
                    	<img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{img($service->image)}}">
                    @endif
						<input type="file" accept="image/*" name="image" class="dropify form-control-file" id="image" aria-describedby="fileHelp">
					</div>
				</div>
				

				


				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Mettre à jours</button>
						<a href="{{route('admin.category.index')}}" class="btn btn-default">Annuler</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection