@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h3>Comptabilité des dépanneurs</h3>

            <div class="row">

                <div class="row row-md mb-2" style="padding: 15px;">
                    <div class="col-md-12">
                        <div class="box bg-white">
                            <div class="box-block clearfix">
                                <h5 class="float-xs-left">Bénéfices</h5>
                                <div class="float-xs-right">
                                    <button id="clear_search" type="button" class="btn btn-primary">Clear</button>
                                </div>
                                <div class="float-xs-right">
                                    <input type="text" id="datepicker">
                                </div>
                            </div>

                            @if(count($payouts) != 0)
                            <table class="table table-striped table-bordered dataTable" id="table-2">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Nom du dépanneur</td>
                                        <td>N° de Mobile</td>
                                        <td>RIB</td>
                                        <td>Total des dépannages</td>
                                        <td>CB Paid</td>
                                        <td>Cash Received</td>
                                        <td>Commission</td>
                                        <td>Resultat</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payouts as $index => $payout)
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td>
                                            {{$payout->provider->first_name}} 
                                            {{$payout->provider->last_name}}
                                        </td>
                                        <td>
                                            {{$payout->provider->mobile}}
                                        </td>
                                        <td>
                                            @if($payout->rib)
                                            {{$payout->rib}}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if($payout->troubleShooting)
                                            {{$payout->troubleShooting}}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if($payout->cb)
                                            {{($payout->cb)}}€
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if($payout->cashReceived)
                                            {{($payout->cashReceived)}}€
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if($payout->commission)
                                            {{($payout->commission)}}€
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            @if($payout->result)
                                            {{($payout->result)}}€
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>#</td>
                                        <td>Nom du dépanneur</td>
                                        <td>N° de Mobile</td>
                                        <td>RIB</td>
                                        <td>Total des dépannages</td>
                                        <td>CB Paid</td>
                                        <td>Cash Received</td>
                                        <td>Commission</td>
                                        <td>Resultat</td>
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
