<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow shadow" data-scroll-to-active="true">
    <div class="navbar-header pl-0">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand mx-0" href="{{ route('home') }}">
                    <span class="brand-logo">
                        <img src="{{ logo() }}" alt="Logo" class="mx-2" style="width: 50px !important;max-width: 80px !important; height: 50px;">
                    </span>
                    <h1 class="mx-auto" style="font-family: cursive;">
                        <span style="color: #42a045;">Farm</span><span style="color: #714126;">App</span>
                    </h1>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main mt-1" id="main-menu-navigation" data-menu="menu-navigation">

            {{-- Accueil --}}
            <li class="navigation-header"><span>{{ __('message.Accueil') }}</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item">
                <a class="d-flex align-items-center" href="{{ route('home') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">{{ __('message.Tableau de bord') }}</span>
                </a>
            </li>

            {{-- Élevage --}}
            <li class="navigation-header"><span>{{ __('message.Élevage') }}</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="activity"></i>
                    <span class="menu-title text-truncate">{{ __('message.Nutrition') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="NutritionListe"><a class="d-flex align-items-center" href="{{ route('nutrition.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="NutritionCalendrier"><a class="d-flex align-items-center" href="{{ route('nutrition.calendar', ['bande' => 1]) }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Calendrier') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="columns"></i>
                    <span class="menu-title text-truncate">{{ __('message.Bande') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="BandeListe"><a class="d-flex align-items-center" href="{{ route('bande.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="BandeGestion"><a class="d-flex align-items-center" href="{{ route('gestion_bande', ['id' => 1]) }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Gérer') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="droplet"></i>
                    <span class="menu-title text-truncate">{{ __('message.Nettoyage') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="NettoyageListe"><a class="d-flex align-items-center" href="{{ route('nettoyage.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <img src="{{ asset('app-assets/images/feather/vaccination.png') }}" style="height: 1.45rem; width: 1.45rem; display: inline;">
                    <span class="menu-title text-truncate pl-1">{{ __('message.Vaccination') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="SeanceVaccinationListe"><a class="d-flex align-items-center" href="{{ route('seanceVaccination.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                </ul>
            </li>

        </ul>
    </div>
</div>
<!-- END: Main Menu-->
