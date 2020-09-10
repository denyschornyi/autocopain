@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h3>NOTE BASSE</h3>

            <div class="row">

                <div class="row row-md mb-2" style="padding: 15px;">
                    <div class="col-md-12">
                        <div class="box bg-white">

                            @if(count($score) != 0)
                            <table class="table table-striped table-bordered dataTable" id="table-2">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>N° de Mobile</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($score as $index => $provider)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$provider->first_name}} {{$provider->last_name}}</td>
                                        @if(Setting::get('demo_mode', 0) == 1)
                                        <td>{{ substr($provider->email, 0, 3).'****'.substr($provider->email, strpos($provider->email, "@")) }}</td>
                                        @else
                                        <td>{{$provider->email}}</td>
                                        @endif
                                        <td>{{$provider->mobile}}</td>
                                        <td>{{$provider->rating}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>N° de Mobile</th>
                                        <th>Rating</th>
                                    </tr>
                                </tfoot>
                            </table>
                            @else
                            <h6 class="no-result">Aucun résultat trouvé</h6>
                            @endif 

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection
