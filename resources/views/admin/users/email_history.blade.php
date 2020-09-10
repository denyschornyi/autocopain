@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h3>Historique Email</h3>

            <div class="row">

                <div class="row row-md mb-2" style="padding: 15px;">
                    <div class="col-md-12">
                        <div class="box bg-white">

                            @if(count($history) != 0)
                            <table class="table table-striped table-bordered dataTable" id="table-2">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Assujettir</td>
                                        <td>Corps du courrier électronique</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $index => $hist)
                                    <tr>
                                        <td>{{$hist->id}}</td>
                                        <td>{{$hist->email_subject}}</td>
                                        <td>{{$hist->email_body}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>#</td>
                                        <td>Assujettir</td>
                                        <td>Corps du courrier électronique</td>
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
