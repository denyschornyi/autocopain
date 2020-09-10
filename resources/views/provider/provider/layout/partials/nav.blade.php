<!--<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
    <ul class="nav sidebar-nav">
        <li>
            <a href="{{ route('provider.earnings') }}">Partner Earnings</a>
        </li>
        <li>
            <a href="{{ route('provider.upcoming') }}">Upcoming Services</a>
        </li>
        <li>
            <a href="{{ route('provider.profile.index') }}">Profile</a>
        </li>

        <li>
            <a href="{{ url('/provider/logout') }}"
               onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
                Logout
            </a>
        </li>
    </ul>
</nav>-->

<!-- Navbar -->
<nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/provider') }}" class="nav-link">Accueil</a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('provider.earnings') }}" class="brand-link" style="background-color: #fff;">
        <img src="{{ Setting::get('site_logo', asset('asset/img/logo.png')) }}" alt="Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">&nbsp;</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar1">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="javascript::void(0);" class="d-block">
                    {{ Auth::guard('provider')->user()->first_name }} {{ Auth::guard('provider')->user()->last_name }}
                </a>
                <div class="rating-outer new-pro-rating">
                    <input type="hidden" class="rating" value="{{ Auth::guard('provider')->user()->rating }}" readonly>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{ route('provider.earnings') }}" class="nav-link">
                        <i class="nav-icon fa fa-pie-chart"></i>
                        <p>
                            Gains
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('provider.upcoming') }}" class="nav-link">
                        <i class="nav-icon fa fa-tree"></i>
                        <p>
                            Dépannage à venir
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('provider.profile.index') }}" class="nav-link">
                        <i class="nav-icon fa fa-edit"></i>
                        <p>
                            Profil
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('provider.change.password')}}" class="nav-link">
                        <i class="nav-icon fa fa-table"></i>
                        <p>
                            Changer som mot de passe
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/provider/logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fa fa-circle-o text-warning"></i>
                        <p>
                            Déconnexion
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>