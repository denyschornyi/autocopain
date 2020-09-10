@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h3>{{$title}}</h3>

            <div class="row">

                <div class="row row-md mb-2" style="padding: 15px;">
                    <div class="col-md-12">
                        <div class="box bg-white">

                            @if(count($newUsers) != 0)
                            <table class="table table-striped table-bordered dataTable" id="table-2">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>N° de Mobile</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($newUsers as $index => $user)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$user->first_name}} {{$user->last_name}}</td>
                                        @if(Setting::get('demo_mode', 0) == 1)
                                        <td>{{ substr($user->email, 0, 3).'****'.substr($user->email, strpos($user->email, "@")) }}</td>
                                        @else
                                        <td>{{$user->email}}</td>
                                        @endif
                                        <td>{{$user->mobile}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>N° de Mobile</th>
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
