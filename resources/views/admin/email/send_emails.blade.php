@extends('admin.layout.base')

@section('title')

@section('content')
@include('admin.setting.tinymceSetting')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.user.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Retour</a>

            <h5 style="margin-bottom: 2em;">Envoyer des courriels aux utilisateurs</h5>

            <form class="form-horizontal" action="{{route('admin.emailToUsers')}}" method="POST" enctype="multipart/form-data" role="form" novalidate>
                {{csrf_field()}}
                <div class="form-group">
                    <label>To</label>
                    <select id="email_to" name="email_to" class="form-control" required="">
                        <option value="1">Providers</option>
                        <option value="2">Users</option>
                        <option value="3">Both</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Type de dépannage</label>
                    <select id="provider_type" name="provider_type" class="form-control">
                        <option value="5">All Dépanneurs</option>
                        <option value="1">Refuse</option>
                        <option value="2">Aucun</option>
                        <option value="3">En attente</option>
                        <option value="4">Valide</option>
                    </select>
                </div>

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

<script>
$(document).ready(function(){
  $('#email_to').on('change', function() {
      if (this.value == 1 || this.value == 3) {
          $('#provider_type').prop('disabled', false);
      } else {
          $('#provider_type').prop('disabled', 'disabled');
      }
  });
});    
</script>
@endsection
