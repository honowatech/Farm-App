<!-- BEGIN: Main Menu (Client)-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow shadow" data-scroll-to-active="true">
    <div class="navbar-header pl-0">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand mx-0" href="{{ route('client.dashboard') }}">
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

            <li class="navigation-header"><span>Mon espace</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item" id="ClientDashboard" data-route="client.dashboard">
                <a class="d-flex align-items-center" href="{{ route('client.dashboard') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">Tableau de bord</span>
                </a>
            </li>
            <li class="nav-item" id="ClientCatalogue" data-route="client.catalogue">
                <a class="d-flex align-items-center" href="{{ route('client.catalogue') }}">
                    <i data-feather="package"></i>
                    <span class="menu-title text-truncate">Catalogue</span>
                </a>
            </li>
            <li class="nav-item" id="ClientCommande" data-route="client.commande.create">
                <a class="d-flex align-items-center" href="{{ route('client.commande.create') }}">
                    <i data-feather="shopping-cart"></i>
                    <span class="menu-title text-truncate">Mon panier</span>
                    @php $cartCount = array_sum(array_map('intval', session('cart', []))); @endphp
                    @if($cartCount > 0)
                        <span class="badge badge-pill badge-success ml-25">{{ $cartCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item" id="ClientCommandes" data-route="client.commandes.index,client.commandes.show">
                <a class="d-flex align-items-center" href="{{ route('client.commandes.index') }}">
                    <i data-feather="shopping-bag"></i>
                    <span class="menu-title text-truncate">Mes commandes</span>
                </a>
            </li>
            <li class="nav-item" id="ClientFactures" data-route="client.factures.index,client.factures.show,client.factures.pdf">
                <a class="d-flex align-items-center" href="{{ route('client.factures.index') }}">
                    <i data-feather="file-text"></i>
                    <span class="menu-title text-truncate">Mes factures</span>
                </a>
            </li>

            <li class="navigation-header"><span>Compte</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item" id="ClientProfil" data-route="client.profil">
                <a class="d-flex align-items-center" href="{{ route('client.profil') }}">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate">Mon profil</span>
                </a>
            </li>

        </ul>
    </div>
</div>
<!-- END: Main Menu (Client)-->
