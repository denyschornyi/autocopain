@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

			<h5 style="margin-bottom: 2em;">Mettre à jours un dépanneur</h5>

            <form class="form-horizontal" action="{{route('admin.provider.update', $provider->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				<div class="form-group row">
					<label for="first_name" class="col-xs-2 col-form-label">Prénom</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $provider->first_name }}" name="first_name" required id="first_name" placeholder="Prénom">
					</div>
				</div>

				<div class="form-group row">
					<label for="last_name" class="col-xs-2 col-form-label">Nom</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $provider->last_name }}" name="last_name" required id="last_name" placeholder="Nom">
					</div>
				</div>


				<div class="form-group row">
					
					<label for="picture" class="col-xs-2 col-form-label">Avatar</label>
					<div class="col-xs-10">
					@if(isset($provider->picture))
                    	<img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{$provider->picture}}">
                    @endif
						<input type="file" accept="image/*" name="picture" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">N° de Mobile</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{ $provider->mobile }}" name="mobile" required id="mobile" placeholder="N° de Mobile">
					</div>
				</div>


				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Mettre à jours</button>
						<a href="{{route('admin.provider.index')}}" class="btn btn-default">Annuler</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
