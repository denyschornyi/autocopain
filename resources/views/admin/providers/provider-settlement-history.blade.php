@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h3>Total des transactions (Balance actuelle : {{currency($total_amount)}})</h3>

            <div class="row">

                <div class="row row-md mb-2" style="padding: 15px;">
                    <div class="col-md-12">
                        <div class="box bg-white">
                            <table class="table table-striped table-bordered dataTable" id="table-2">
                                <thead>
                                    <tr>
                                        <td>Sno</td>
                                        <td>Transaction ID</td>
                                        <td>Date</td>
                                        <td>Description</td>
                                        <td>status</td>
                                        <td>Montant</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach($transactions as $index => $list)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$list['transaction_alias']}}</td>
                                        <td>{{appDate($list['created_at'])}}</td>
                                        <td>{{$list['description']}}</td>
                                        <td>{{$list['status']}}</td>
                                        <td>{{currency($list['total_amount'])}}</td>
                                    </tr>
                                    @php($i++)
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection
