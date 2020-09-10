@extends('provider.layout.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profil</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ img(Auth::guard('provider')->user()->avatar) }}"
                                     alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ Auth::guard('provider')->user()->first_name }} {{ Auth::guard('provider')->user()->last_name }}</h3>

                            <p class="text-muted text-center">{{ strtoupper(Auth::guard('provider')->user()->status) }}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Profil</a></li>
                                <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Documents</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    @include('common.notify')
                                    <form action="{{route('provider.profile.update')}}" method="POST" enctype="multipart/form-data" role="form">
                                        {{csrf_field()}}
                                        <!-- Prof-form-sub-sec -->
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Prénom</label>
                                                <input type="text" class="form-control" placeholder="Prénom" name="first_name" value="{{ Auth::guard('provider')->user()->first_name }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Nom</label>
                                                <input type="text" class="form-control" placeholder="Nom" name="last_name" value="{{ Auth::guard('provider')->user()->last_name }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Avatar</label>
                                                <input type="file" class="form-control" name="avatar">
                                            </div>
                                            <div class="form-group">
                                                <label>N° de mobile</label>
                                                <input type="text" class="form-control" placeholder="N° de mobile" name="mobile" value="{{ Auth::guard('provider')->user()->mobile }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Adresse</label>
                                                <input type="text" class="form-control" placeholder="Entrer votre adresse" name="address" value="{{ Auth::guard('provider')->user()->profile ? Auth::guard('provider')->user()->profile->address : "" }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Dépannage proposés : </label>
                                                @foreach($Provider as $service)
                                                @if($service->service_type)
                                                <p>{{$service->service_type->name}}</p>
                                                @endif
                                                @endforeach
                                            </div>
                                            <div class="form-group">
                                                <label>Bio</label>
                                                <input type="text" class="form-control" placeholder="Enter Description" name="description" value="{{ Auth::guard('provider')->user()->description ? Auth::guard('provider')->user()->description : "" }}">
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Mettre à jours</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="timeline">
                                    @if(count($DriverDocuments) > 0)
                                    @foreach($DriverDocuments as $Document)
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="manage-doc-box-left">
                                                <p class="manage-txt">{{ $Document->name }}</p>
                                                <p class="license">Expire : {{ $Document->expires_at ? $Document->expires_at : 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="manage-doc-box-center text-center">
                                                <p class="manage-badge {{ $Document->status == 'ASSESSING' ? 'yellow-badge' : 'green-badge' }}">
                                                    {{ $Document->status ? $Document->status : 'MISSING' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <form action="{{ route('provider.documents.update', $Document->id) }}" method="POST" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                {{ method_field('PATCH') }}
                                                <div class="form-group">
                                                        <label>Document</label>
                                                        <input type="file" class="form-control" name="document" required="">
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-primary">Mettre à jours</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                            <form role="form" action="{{ route('provider.documents.store') }}" method="POST" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="document">Document</label>
                                                        <select style="height: 35px;" class="form-control" name="documentType">
                                                            @foreach ($allDocuments as $docTypes)
                                                            <option value="{{$docTypes->id}}">{{$docTypes->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Document</label>
                                                        <input type="file" class="form-control" name="document" required="">
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-primary">Mettre à jours</button>
                                                </div>
                                            </form>
                                    @endif
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="settings">
                                    <form action="{{ route('provider.location.update') }}" id="location_update_form" method="POST" role="form">
                                        {{ csrf_field() }}
                                        <div class="card-body">
                                            <input type="hidden" name="latitude" id="latitude">
                                            <input type="hidden" name="longitude" id="longitude">
                                            <input type="hidden" name="address" id="address">
                                            <div class="form-group">
                                                <input tabindex="2" id="pac-input" class="form-control" type="text" placeholder="Entrer un lieu" name="s_address">
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Mettre à jours</button>
                                        </div>
                                    </form>
                                    <div id="map"></div>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@endsection

@section('scripts')
<script>
    var map;
    var input = document.getElementById('pac-input');
    var s_latitude = document.getElementById('latitude');
    var s_longitude = document.getElementById('longitude');
    var s_address = document.getElementById('address');

    function initMap() {

        var userLocation = new google.maps.LatLng(
                @if(Auth::guard('provider')->user()->latitude) {{ Auth::guard('provider')->user()->latitude }} @else 11.8508117 @endif, 
                @if(Auth::guard('provider')->user()->longitude) {{ Auth::guard('provider')->user()->longitude }} @else 79.7854668 @endif
            );

        map = new google.maps.Map(document.getElementById('map'), {
            center: userLocation,
            zoom: 15
        });

        var service = new google.maps.places.PlacesService(map);
        var autocomplete = new google.maps.places.Autocomplete(input);
        var infowindow = new google.maps.InfoWindow();

        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow({
            content: "Your Location",
        });

        var marker = new google.maps.Marker({
            map: map,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29)
        });
        marker.setVisible(true);
        marker.setPosition(userLocation);
        infowindow.open(map, marker);

        google.maps.event.addListener(map, 'click', updateMarker);
        google.maps.event.addListener(marker, 'dragend', updateMarker);

        function updateMarker(event) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': event.latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        input.value = results[0].formatted_address;
                        updateForm(event.latLng.lat(), event.latLng.lng(), results[0].formatted_address);
                    } else {
                        alert('No Address Found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });

            marker.setPosition(event.latLng);
            map.setCenter(event.latLng);
        }

        autocomplete.addListener('place_changed', function(event) {
            marker.setVisible(false);
            var place = autocomplete.getPlace();

            if (place.hasOwnProperty('place_id')) {
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }
                updateLocation(place.geometry.location);
            } else {
                service.textSearch({
                    query: place.name
                }, function(results, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        updateLocation(results[0].geometry.location, results[0].formatted_address);
                        input.value = results[0].formatted_address;
                    }
                });
            }
        });

        function updateLocation(location) {
            map.setCenter(location);
            marker.setPosition(location);
            marker.setVisible(true);
            infowindow.open(map, marker);
            updateForm(location.lat(), location.lng(), input.value);
        }

        function updateForm(lat, lng, addr) {
            s_latitude.value = lat;
            s_longitude.value = lng;
            s_address.value = addr;
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALHyNTDk1K_lmcFoeDRsrCgeMGJW6mGsY&libraries=places&callback=initMap" async defer></script>
@endsection