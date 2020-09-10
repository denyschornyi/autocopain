@extends('admin.layout.base')

@section('title')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">Historique de paiement</h5>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Dépannage ID</th>
                            <th>Transaction ID</th>
                            <th>De</th>
                            <th>À</th>
                            <th>Montant total</th>
                            <th>Mode de paiement</th>
                            <th>Statut du paiement</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $index => $payment)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$payment->booking_id}}</td>
                            @if($payment->payment)
                                <td>{{$payment->payment->payment_id}}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if($payment->user)
                                <td>{{$payment->user->first_name}} {{$payment->user->last_name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if($payment->provider)
                                <td>{{$payment->provider->first_name}} {{$payment->provider->last_name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if($payment->payment)
                                <td>{{($payment->payment->total)}}€</td>
                            @else
                                <td>-</td>
                            @endif
                            <td>{{$payment->payment_mode}}</td>
                            <td>
                                @if($payment->paid)
                                    Payé
                                @else
                                    Non payé
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Dépannage ID</th>
                            <th>Transaction ID</th>
                            <th>De</th>
                            <th>À</th>
                            <th>Montant total</th>
                            <th>Mode de paiement</th>
                            <th>Statut du paiement</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection