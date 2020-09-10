@extends('admin.layout.base')

@section('title')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

            <h5 style="margin-bottom: 2em;">Ajouter des coordonnées bancaires</h5>

            <form class="form-horizontal" action="{{route('admin.provider.add_bank_details')}}" method="POST" role="form">
                {{csrf_field()}}
                <input type="hidden" name="providerId" value="{{$Provider}}">
                <div class="form-group row">
                    <label for="rib" class="col-xs-12 col-form-label">RIB</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('rib') }}" name="rib" required id="rib" placeholder="RIB">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="troubleShooting" class="col-xs-12 col-form-label">Total des dépannages</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('troubleShooting') }}" name="troubleShooting" required id="troubleShooting" placeholder="Total des dépannages">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cashReceived" class="col-xs-12 col-form-label">Cash Received</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" required name="cashReceived" value="{{old('cashReceived')}}" id="cashReceived" placeholder="Cash Received">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cb" class="col-xs-12 col-form-label">CB Paid</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" name="cb" id="cb" value="{{old('cb')}}" placeholder="CB Paid">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="commission" class="col-xs-12 col-form-label">Commission</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" name="commission" id="commission" value="{{old('commission')}}" placeholder="Commission">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="result" class="col-xs-12 col-form-label">Resultat</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ old('result') }}" name="result" required id="result" placeholder="Resultat">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="zipcode" class="col-xs-12 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">Ajouter des coordonnées bancaires</button>
                        <a href="{{route('admin.provider.index')}}" class="btn btn-default">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
