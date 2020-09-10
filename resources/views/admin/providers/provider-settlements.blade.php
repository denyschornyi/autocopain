@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h3>Demande de paiement</h3>

            <div class="row">

                <div class="row row-md mb-2" style="padding: 15px;">
                    <div class="col-md-12">
                        <div class="box bg-white">
                            <a href="{{route('admin.ride.statement.providersettlements.view')}}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Faire un règlement</a>
                            <table class="table table-striped table-bordered dataTable" id="table-2">
                                <thead>
                                    <tr>
                                        <td>Sno</td>
                                        <td>Transaction ID</td>
                                        <td>Date</td>
                                        <td>Dépanneur</td>
                                        <td>RIB</td>
                                        <td>Montant</td>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Pendinglist as $index => $list)
                                    <tr>
                                        <td>{{$list->id}}</td>
                                        <td>{{$list->alias_id}}</td>
                                        <td>{{appDate($list->updated_at)}}</td>
                                        <td>{{$list->first_name}} {{$list->last_name}}</td>
                                        <td>{{$list->rib}}</td>
                                        <td>{{currency(abs($list->amount))}}</td>
                                        <td> 
                                            <button type="button" class="btn btn-success btn-block transferClass" data-toggle="modal" data-target="#transferModal" data-id="send" data-href="{{ route('admin.approve', $list->id) }}" data-rid="{{$list->id}}">Envoyé</button>
                                            <button type="button" class="btn btn-danger btn-block transferClass" data-toggle="modal" data-target="#transferModal" data-id="cancel" data-href="{{ route('admin.cancel') }}?id={{$list->id}}" data-rid="{{$list->id}}">Annulé</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="transferModal" class="modal fade" role="dialog" data-backdrop="static" aria-hidden="true" data-keyboard="false">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title" id="settitle"></h4>
          </div>
          <form action="" method="Get" id="transurl">
          <div class="modal-body">
            <div id="sendbody" style="display:none">
                <div class="alert alert-warning alert-dismissible" style="display:block">
                    <strong></strong> <span id="setbody">Confirmez le paiement ?</span>
                </div>
                <!-- <div class="form-group row">
                    <label for="send_by" class="col-xs-3 col-form-label" required>Payment Mode</label>
                    <div class="col-xs-5">
                        <select class="form-control" name="send_by" id="send_by">
                            <option value="online">Stripe</option>
                            <option value="offline">Cash</option>
                        </select>
                    </div>
                </div> -->
                <div id="show_alert_text_cash" class="alert alert-warning alert-dismissible" style="display:none">
                    <strong>Warning!</strong> <span id="setbody">Are you sure want to complete this transaction on cash mode.</span>
                </div>
            </div>
            <div id="cancelbody" style="display:none">
                <input type="hidden" value="" name="id" id="transfer_id">
                <div class="alert alert-warning alert-dismissible">
                    <strong>Warning!</strong> <span id="setbody">Are you sure want to cancel this transaction.</span>
                </div>
            </div>    
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Confirmer</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
          </div>
        </form>
        </div>

      </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(function () {
        $(".transferClass").click(function () {
            var curl = $(this).attr('data-href');
            var page = $(this).attr('data-id');
            $("#transurl").attr('action',curl);
            if(page=='send'){
                $("#settitle").text('Attention');
                $("#cancelbody").hide();
                $("#sendbody").show();
                $("#send_by").on('change', function(){
                    var ddval=$("#send_by").val();
                    if(ddval=="offline"){
                        $("#show_alert_text_cash").show();
                    }
                    else{
                        $("#show_alert_text_cash").hide();
                    }
                })
                
            }
            else{
                $("#transfer_id").val($(this).attr('data-rid'));
                $("#settitle").text('Attention');
                $("#sendbody").hide();
                $("#cancelbody").show();
            }
            
        })
    });
</script>
@endsection