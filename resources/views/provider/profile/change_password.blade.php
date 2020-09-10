@extends('provider.layout.app')

@section('title', 'Change Password ')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Change Password</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Changer votre mot de passe</h3>
                        </div>
                        <!-- /.card-header -->
                        @include('common.notify')
                        <!-- form start -->
                        <form role="form" action="{{route('provider.password.update')}}" method="post">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="oldPassword">Ancien mot de passe</label>
                                    <input id="oldPassword" type="password" name="old_password" class="form-control" placeholder="Ancien mot de passe">
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">Mot de passe</label>
                                    <input id="newPassword" type="password" name="password" class="form-control" placeholder="Mot de passe">
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">Confirmer le mote de passe</label>
                                    <input id="confirmPassword" type="password" name="password_confirmation" class="form-control" placeholder="Confirmer le mote de passe">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Changer le mote de passe</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection