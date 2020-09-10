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


    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div class="full-page-bg" style="background-image: url({{ asset('asset/img/login-bg.jpg') }});">
        <div class="log-overlay"></div>
            <div class="full-page-bg-inner">
                <div class="row no-margin">
                    <div class="col-md-6 log-left">
                        <span class="login-logo"><img src="{{ Setting::get('site_logo', asset('asset/img/logo.png')) }}"></span>
                        <h2>{{Setting::get('site_title','Tranxit')}} a besoin de partenaires comme vous.</h2>
                        <p>Dépannez avec {{Setting::get('site_title','Tranxit')}} et gagner beaucoup d'argent en tant qu'AutoCopain. Soyez payé chaque semaine pour aider notre communauté d'AutoCopain. Soyez votre propre patron et soyez payé dans les tarifs pour vos interventions selon votre propre horaire.</p>
                    </div>
                    <div class="col-md-6 log-right">
                        <div class="login-box-outer">
                            <div class="login-box row no-margin">
                                @yield('content')
                            </div>
                            <div class="log-copy"><p class="no-margin">&copy; {{date('Y')}} {{ Setting::get('site_title', '&copy; 2018 AutoCopain') }}</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('asset/js/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset/js/scripts.js') }}"></script>
</body>
</html>
