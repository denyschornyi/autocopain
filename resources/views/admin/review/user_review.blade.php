@extends('admin.layout.base')

@section('title')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">Avis des utilisateurs</h5>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Dépannage ID</th>
                            <th>Nom de l'utilisateur</th>
                            <th>Nom du dépanneur</th>
                            <th>Évaluation</th>
                            <th>Date & Heure</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($Reviews as $index => $review)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$review->request_id}}</td>
                            @if($review->user)
                            <td><a href="{{ route('admin.user.show', $review->user->id) }}">{{$review->user->first_name}} {{$review->user->last_name}}</a></td>
                            @else
                                <td>-</td>
                            @endif
                            @if($review->provider)
                            <td><a href="{{route('admin.provider.document.index', $review->provider->id )}}">{{$review->provider->first_name}} {{$review->provider->last_name}}</a></td>
                            @else
                                <td>-</td>
                            @endif

                            <td>
                                <div className="rating-outer">
                                    <input type="hidden" value="{{$review->user_rating}}" name="rating" class="rating"/>
                                </div>
                            </td>
                            <td>{{date('d/m/Y H:i:s',strtotime($review->created_at))}}</td>
                            <td>{{$review->user_comment}}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Dépannage ID</th>
                            <th>Nom de l'utilisateur</th>
                            <th>Nom du dépanneur</th>
                            <th>Évaluation</th>
                            <th>Date & Heure</th>
                            <th>Commentaire</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection