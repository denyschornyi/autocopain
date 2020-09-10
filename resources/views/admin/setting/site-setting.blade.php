@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">

			<h5 style="margin-bottom: 2em;">Paramètres du site</h5>

            <form class="form-horizontal" action="{{route('admin.setting.store')}}" method="POST" enctype="multipart/form-data" role="form">
            
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">Nom du site</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('site_title', 'Tranxit')  }}" name="site_title" required id="site_title" placeholder="Site Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_logo" class="col-xs-2 col-form-label">Logo du site</label>
					<div class="col-xs-10">
						@if(Setting::get('site_logo')!='')
	                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{Setting::get('site_logo')}}">
	                    @endif
						<input type="file" accept="image/*" name="site_logo" class="dropify form-control-file" id="site_logo" aria-describedby="fileHelp">
					</div>
				</div>


				<div class="form-group row">
					<label for="site_icon" class="col-xs-2 col-form-label">Icône du site</label>
					<div class="col-xs-10">
						@if(Setting::get('site_icon')!='')
	                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{Setting::get('site_icon')}}">
	                    @endif
						<input type="file" accept="image/*" name="site_icon" class="dropify form-control-file" id="site_icon" aria-describedby="fileHelp">
					</div>
				</div>

                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Contenu de copyright</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('site_copyright', '&copy; 2019 AutoCopain') }}" name="site_copyright" id="site_copyright" placeholder="Contenu de copyright">
                    </div>
                </div>

				<div class="form-group row">
					<label for="play_store_link" class="col-xs-2 col-form-label">Lien Play Store</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('play_store_link', '')  }}" name="play_store_link"  id="play_store_link" placeholder="Lien Play Store">
					</div>
				</div>

				<div class="form-group row">
					<label for="app_store_link" class="col-xs-2 col-form-label">Lien Appstore</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('app_store_link', '')  }}" name="app_store_link"  id="app_store_link" placeholder="Lien Appstore">
					</div>
				</div>

				<div class="form-group row">
					<label for="provider_select_timeout" class="col-xs-2 col-form-label">Temps libre du dépanneur</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{ Setting::get('provider_select_timeout', '')  }}" name="provider_select_timeout" required id="provider_select_timeout" placeholder="Temps libre du dépanneur">
					</div>
				</div>

				<div class="form-group row">
                    <label for="booking_prefix" class="col-xs-2 col-form-label">Préfixe d'ID du dépannage</label>
                    <div class="col-xs-10">
                        <input class="form-control"
                            type="text"
                            value="{{ Setting::get('booking_prefix', '0') }}"
                            id="booking_prefix"
                            name="booking_prefix"
                            min="0"
                            max="4"
                            placeholder="Préfixe d'ID du dépannage">
                    </div>
                </div>

				<div class="form-group row">
					<label for="search_radius" class="col-xs-2 col-form-label">Rayon de recherche</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{ Setting::get('search_radius', '')  }}" name="search_radius" required id="search_radius" placeholder="Recherche">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_email" class="col-xs-2 col-form-label">Email du contact</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('contact_email', '')  }}" name="contact_email"  id="contact_email" placeholder="Email">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_number" class="col-xs-2 col-form-label">Numéro de contact</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('contact_number', '')  }}" name="contact_number"  id="contact_number" placeholder="Numéro">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_text" class="col-xs-2 col-form-label">Texte du contact</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('contact_text', '')  }}" name="contact_text"  id="contact_text" placeholder="Texte">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_title" class="col-xs-2 col-form-label">Titre du contact</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('contact_title', '')  }}" name="contact_title"  id="contact_title" placeholder="Titre">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Mettre à jours</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@endsection
