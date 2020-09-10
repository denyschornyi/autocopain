@extends('provider.layout.app')

@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('newassets/plugins/datatables/dataTables.bootstrap4.css') }}">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dépannages à venir</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date & Heure </th>
                                    <th>Type du dépannage</th>
                                    <th>Lieu</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $fully_sum = 0; ?>
                                @foreach($fully as $each)
                                <tr>
                                    <td>{{date('d/m/Y H:i:s',strtotime($each->schedule_at))}}</td>
                                    <td>
                                        @if($each->service_type)
                                        {{$each->service_type->name}}
                                        @endif
                                    </td>
                                    <td>
                                        {{$each->s_address}}
                                    </td>

                                    <td>
                                        @if($each->status == "SCHEDULED")
                                        A venir
                                        @else
                                        {{$each->status}}
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('provider.cancel') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$each->id}}">
                                            <button class="btn-primary" onclick="return confirm('Etes-vous sure?')"> Annulé</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Date & Heure </th>
                                    <th>Type du dépannage</th>
                                    <th>Lieu</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{ asset('newassets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('newassets/plugins/datatables/dataTables.bootstrap4.js') }}"></script>
<script type="text/javascript">
                                                $(function () {
                                                    $("#example1").DataTable({
        "language": {
            "url": "{{ asset('French.json') }}"
        }
    });
                                                });
                                                document.getElementById('set_fully_sum').textContent = "{{currency($fully_sum)}}";
</script>
@endsection