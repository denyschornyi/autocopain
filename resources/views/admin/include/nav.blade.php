<div class="site-sidebar">
    <div class="custom-scroll custom-scroll-light">
        <ul class="sidebar-menu">
            <li class="menu-title">Accueil</li>
            <li>
                <a href="{{route('admin.dashboard')}}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-anchor"></i></span>
                    <span class="s-text">Tableau de bord</span>
                </a>
            </li>

            <li class="menu-title">Membres AutoCopain</li>
            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-crown"></i></span>
                    <span class="s-text">Utilisateurs</span>
                </a>
                <ul>
                    <li><a href="{{route('admin.user.index')}}">Liste des utilisateurs</a></li>
                    <li><a href="{{route('admin.user.create')}}">Ajouter un utilisateur</a></li>
                </ul>
            </li>
            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-car"></i></span>
                    <span class="s-text">Dépanneurs</span>
                </a>
                <ul>
                    <li><a href="{{route('admin.provider.index')}}" class="provider_list">Liste des dépanneurs</a></li>
                    <li><a href="{{route('admin.provider.create')}}">Ajouter un dépanneur</a></li>
                </ul>
            </li>
            <li class="menu-title">Détails</li>
            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-map-alt"></i></span>
                    <span class="s-text">Map</span>
                </a>
                <ul>
                    <li><a href="{{route('admin.user.map')}}">Localiser un utilisateur</a></li>
                    <li><a href="{{route('admin.provider.map')}}">Localiser un dépanneur</a></li>
                </ul>
            </li>
            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-view-grid"></i></span>
                    <span class="s-text">Notes et Avis</span>
                </a>
                <ul>
                    <li><a href="{{route('admin.user.review')}}">Notes des utilisateurs</a></li>
                    <li><a href="{{route('admin.provider.review')}}">Notes des dépanneurs</a></li>
                </ul>
            </li>
            <li class="menu-title">Dépannages</li>
            <li>
                <a href="{{route('admin.request.history')}}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-infinite"></i></span>
                    <span class="s-text">Historique</span>
                </a>
            </li>
            <li>
                <a href="{{route('admin.scheduled.request')}}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-palette"></i></span>
                    <span class="s-text">Dépannages planifiés</span>
                </a>
            </li>
            <li class="menu-title">Général</li>

            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-view-grid"></i></span>
                    <span class="s-text">Catégories</span>
                </a>
                <ul>
                    <li><a href="{{route('admin.category.index')}}">Liste des catégories</a></li>
                    <li><a href="{{route('admin.category.create')}}">Ajouter une catégorie</a></li>
                </ul>
            </li>

            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-view-grid"></i></span>
                    <span class="s-text">Types de dépannages</span>
                </a>
                <ul>
                    <li><a href="{{route('admin.service.index')}}">Liste des dépannages</a></li>
                    <li><a href="{{route('admin.service.create')}}">Ajouter un dépannage</a></li>
                </ul>
            </li>
            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-layout-tab"></i></span>
                    <span class="s-text">Documents</span>
                </a>
                <ul>
                    <li><a href="{{route('admin.document.index')}}">Liste des documents</a></li>
                    <li><a href="{{route('admin.document.create')}}">Ajouter un document</a></li>
                </ul>
            </li>

            <li class="menu-title">Comptes</li>
            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-crown"></i></span>
                    <span class="s-text">Comptabilité</span>
                </a>
                <ul>
                    <li><a href="{{ route('admin.ride.statement') }}">Global</a></li>
                    <li><a href="{{ route('admin.ride.statement.provider') }}">Dépanneurs</a></li>
                    <li><a href="{{ route('admin.ride.statement.today') }}">Journalier</a></li>
                    <li><a href="{{ route('admin.ride.statement.monthly') }}">Mensuel</a></li>
                    <li><a href="{{ route('admin.ride.statement.yearly') }}">Annuel</a></li>
                    <li><a href="{{ route('admin.ride.statement.providersettlements') }}">Demande de paiement</a></li>
                    <li><a href="{{ route('admin.transactions') }}">Transactions</a></li>
                    <!--<li><a href="{{ route('admin.ride.statement.payouts') }}">A payé</a></li>-->
                </ul>
            </li>

            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-layout-tab"></i></span>
                    <span class="s-text">Code de promo</span>
                </a>
                <ul>
                    <li><a href="{{route('admin.promocode.index')}}">Liste des code de promo</a></li>
                    <li><a href="{{route('admin.promocode.create')}}">Ajouter un code de promo</a></li>
                </ul>
            </li>

            {{--set send email menu and sub-menu--}}
            <li class="menu-title">Courriels</li>
            <li class="with-sub">
                <a href="#" class="waves-effect  waves-light">
                    <span class="s-caret"><i class="fa fa-angle-down"></i></span>
                    <span class="s-icon"><i class="ti-email"></i></span>
                    <span class="s-text">Gérer les emails</span>
                </a>
                <ul>
<!--                    <li><a href="{{url('admin/menu/email')}}">Envoyer des emails</a></li>
                    <li><a href="{{ url('admin/email/history') }}">Historique Email</a></li>-->
                    <li><a href="{{route('admin.email')}}">Envoyer des emails</a></li>
                    <li><a href="{{route('admin.email.history')}}">Historique Email</a></li>
                </ul>
            </li>


            <li class="menu-title">Détails de paiement</li>
            <li>
                <a href="{{route('admin.payment')}}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-infinite"></i></span>
                    <span class="s-text">Historique des paiements</span>
                </a>
            </li>
            <li>
                <a href="{{route('admin.payment.setting')}}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-money"></i></span>
                    <span class="s-text">Paramètres de paiement</span>
                </a>
            </li>
            <li class="menu-title">Paramètres</li>
            <li>
                <a href="{{route('admin.setting')}}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-settings"></i></span>
                    <span class="s-text">Paramètres du site</span>
                </a>
            </li>
            <li class="menu-title">Compte</li>
            <li>
                <a href="{{route('admin.profile')}}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-user"></i></span>
                    <span class="s-text">Paramètres du compte</span>
                </a>
            </li>
            <li>
                <a href="{{route('admin.password')}}" class="waves-effect  waves-light">
                    <span class="s-icon"><i class="ti-exchange-vertical"></i></span>
                    <span class="s-text">Changer le mot de passe</span>
                </a>
            </li>



            <li class="compact-hide">
                <a href="{{ url('/admin/logout') }}"
                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    <span class="s-icon"><i class="ti-power-off"></i></span>
                    <span class="s-text">Déconnexion</span>
                </a>

                <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>

        </ul>
    </div>
</div>