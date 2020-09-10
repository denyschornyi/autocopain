@extends('admin.layout.base')

@section('title')

@section('content')
@include('admin.setting.tinymceSetting')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

            <h5 style="margin-bottom: 2em;">Envoyer des emails aux utilisateurs</h5>

            <form class="form-horizontal" action="{{URL::route('admin.emailToUser')}}" method="POST" enctype="multipart/form-data" role="form" novalidate>
                {{csrf_field()}}
                <input type="hidden" name="userId" value="{{$User}}">
                <div class="form-group">
                    <label >Assujettir</label>
                    <input class="form-control" type="text"  name="email_subject" required placeholder="Enter Subject Of Email">
                </div>

                <div class="form-group">
                    <label for="email_body">Corps du courrier électronique</label>
                    <textarea class="form-control" name="email_body" rows="10" required></textarea>
                </div>
                <input type="submit" class="btn btn-success" value="Send Emails">

            </form>

        </div>
    </div>
</div>


@endsection
