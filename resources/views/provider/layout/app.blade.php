<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title'){{ Setting::get('site_title', 'Tranxit') }}</title>
        <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>
        <!--new style-->
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('newassets/plugins/font-awesome/css/font-awesome.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('newassets/dist/css/adminlte.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset('newassets/plugins/iCheck/flat/blue.css') }}">
        <!-- Morris chart -->
        <link rel="stylesheet" href="{{ asset('newassets/plugins/morris/morris.css') }}">
        <!-- jvectormap -->
        <link rel="stylesheet" href="{{ asset('newassets/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
        <!-- Date Picker -->
        <link rel="stylesheet" href="{{ asset('newassets/plugins/datepicker/datepicker3.css') }}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('newassets/plugins/daterangepicker/daterangepicker-bs3.css') }}">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="{{ asset('newassets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <!--new style end-->
        <!-- Styles -->
        <style>
            .glyphicon {
                position: relative;
                top: 1px;
                display: inline-block;
                font-family: 'Glyphicons Halflings';
                font-style: normal;
                font-weight: 400;
                line-height: 1;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
        </style>
        <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('asset/css/dashboard-style-new.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('asset/css/rating.css') }}" rel="stylesheet" type="text/css">
        <!-- jQuery -->
        <script src="{{ asset('newassets/plugins/jquery/jquery.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <!-- Scripts -->
        <script>
window.Laravel = <?php
echo json_encode([
    'csrfToken' => csrf_token(),
]);
?>
        </script>
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <div class="overlay" id="overlayer" data-toggle="offcanvas"></div>
            <form id="logout-form" action="{{ url('/provider/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            @include('provider.layout.partials.nav')
            @yield('content')
            @include('provider.layout.partials.footer')
            <div id="modal-incoming"></div>
        </div>
        <!-- Scripts -->
        <script type="text/javascript" src="{{ asset('asset/js/rating.js') }}"></script>
        <script type="text/javascript" src="{{ asset('asset/js/dashboard-scripts.js') }}"></script>
        <script type="text/babel" src="{{ asset('asset/js/incoming.js') }}"></script>
        @yield('scripts')
        <!--new script-->
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
$.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('newassets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Morris.js charts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="{{ asset('newassets/plugins/morris/morris.min.js') }}"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="{{ asset('newassets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('newassets/dist/js/adminlte.js') }}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{ asset('newassets/dist/js/pages/dashboard.js') }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset('newassets/dist/js/demo.js') }}"></script>
        <!--new script end-->
    </body>
</html>