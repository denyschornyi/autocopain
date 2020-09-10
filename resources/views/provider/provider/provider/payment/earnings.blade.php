@extends('provider.layout.app')

@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('newassets/plugins/datatables/dataTables.bootstrap4.css') }}">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Gains</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <h1 class="earning-txt">TOTAL DES GAINS <span id="set_fully_sum">00.00</span></h1>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="set_fully_sum">{{$today}}</h3>
                            <p>Depannages du jours</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-2 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{Setting::get('daily_target',0)}}</h3>
                            <p>Objectif du jours</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-2 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{$provider[0]->accepted->count()}}</h3>
                            <p>Dépannages complétés</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-2 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>
                                @if($provider[0]->accepted->count() != 0)
                                {{$provider[0]->accepted->count()/$provider[0]->accepted->count()*100}}%
                                @else
                                0%
                                @endif
                            </h3>
                            <p>Taux de dépannages acceptés</p>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{$provider[0]->cancelled->count()}}</h3>
                            <p>Dépannages annulés</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <h1>Bénéfices</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Total des gains</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum_weekly = 0; ?>
                                @foreach($weekly as $day)
                                <tr>
                                    <td>
                                        @if($day->created_at)
                                        {{date('d-m-Y',strtotime($day->created_at))}} - {{$day->created_at->diffForHumans()}}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($day->payment != "")
                                        <?php
                                        $current_sum = 0;
                                        $current_sum = $day->payment->tax + $day->payment->fixed + $day->payment->distance + $day->payment->commision;
                                        $sum_weekly += $current_sum;
                                        ?>
                                        {{currency($current_sum)}}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td><b>Estimation des revenues</b></td>
                                    <td>{{currency($sum_weekly)}}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Total des gains</th>
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

    <section class="content">
        <h1>Gains journaliers</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Dépannage Id</th>
                                    <th>Type du dépannage</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                    <th>Distance(KM)</th>
                                    <th>Cash collectés</th>
                                    <th>Total des gains</th>
                                    <th>Factures</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $fully_sum = 0; ?>
                                @foreach($fully as $each)
                                <tr>
                                    <td>{{date('d/m/Y H:i:s',strtotime($each->created_at))}}</td>
                                    <td>{{ $each->booking_id }}</td>
                                    <td>
                                        @if($each->service_type)
                                        {{$each->service_type->name}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($each->finished_at != null && $each->started_at != null) 
                                        <?php
                                        $StartTime = \Carbon\Carbon::parse($each->started_at);
                                        $EndTime = \Carbon\Carbon::parse($each->finished_at);
                                        echo $StartTime->diffInHours($EndTime) . " Heures";
                                        echo " " . $StartTime->diffInMinutes($EndTime) . " Minutes";
                                        ?>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{$each->status}}</td>
                                    <td>{{$each->distance}}Km</td>
                                    <td>
                                        @if($each->payment != "")
                                        <?php
                                        $each_sum = 0;
                                        $each_sum = $each->payment->tax + $each->payment->fixed + $each->payment->distance + $each->payment->commision;
                                        $fully_sum += $each_sum;
                                        ?>

                                        {{currency($each_sum)}}
                                        @endif
                                    </td>
                                    <td>{{currency($fully_sum)}}</td>
                                    <td>
                                        <a target="_blank" href="{{ url('/provider/download') }}/{{$each->id}}">
                                            <img width="60px" src="{{ asset('newassets/pdf_icon.png') }}" alt="download">
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Dépannage Id</th>
                                    <th>Type du dépannage</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                    <th>Distance(KM)</th>
                                    <th>Cash collectés</th>
                                    <th>Total des gains</th>
                                    <th>Factures</th>
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
    $("#example1").DataTable();
    $("#example2").DataTable();
});
document.getElementById('set_fully_sum').textContent = "{{currency($fully_sum)}}";
</script>
@endsection