@extends('admin.layout.base')

@section('title')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">Historique Email</h5>
                {{--<a href="{{ route('admin.user.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Ajouter un utilisateur</a>--}}
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Envoyer à</th>
                            <th>Sujet du courriel</th>
                            <th>Corps de l'e-mail</th>
                            <th>Date et l'heure</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>
                                @if($user->emailTo == 1)
                                Providers
                                @elseif($user->emailTo == 2)
                                User
                                @else
                                Both
                                @endif
                            </td>
                            <td>{{$user->emailSubject}}</td>
                            <td>{!!$user->emailBody!!}</td>
                            <td>{{date('d/m/Y H:i:s',strtotime($user->created_at))}}</td>


                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Envoyer à</th>
                        <th>Sujet du courriel</th>
                        <th>Corps de l'e-mail</th>
                        <th>Date et l'heure</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection