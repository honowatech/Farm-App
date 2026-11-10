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
            <li class="nav-item" id="Acceuil">
                <a class="d-flex align-items-center" href="{{ route('home') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">{{ __('message.Tableau de bord') }}</span>
                </a>
            </li>

            {{-- Élevage --}}
            <li class="navigation-header"><span>{{ __('message.Élevage') }}</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="columns"></i>
                    <span class="menu-title text-truncate">{{ __('message.Bande') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="BandeListe"><a class="d-flex align-items-center" href="{{ route('bande.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="BandeAjouter"><a class="d-flex align-items-center" href="{{ route('bande.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="box"></i>
                    <span class="menu-title text-truncate">{{ __('message.Bâtiment') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="BatimentListe"><a class="d-flex align-items-center" href="{{ route('batiment.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="BatimentAjouter"><a class="d-flex align-items-center" href="{{ route('batiment.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="activity"></i>
                    <span class="menu-title text-truncate">{{ __('message.Nutrition') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="NutritionListe"><a class="d-flex align-items-center" href="{{ route('nutrition.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="NutritionAjouter"><a class="d-flex align-items-center" href="{{ route('nutrition.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <img src="{{ asset('app-assets/images/feather/vaccination.png') }}" style="height: 1.45rem; width: 1.45rem; display: inline;">
                    <span class="menu-title text-truncate pl-1">{{ __('message.Vaccination') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="SeanceVaccinationListe"><a class="d-flex align-items-center" href="{{ route('seanceVaccination.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="SeanceVaccinationAjouter"><a class="d-flex align-items-center" href="{{ route('seanceVaccination.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="droplet"></i>
                    <span class="menu-title text-truncate">{{ __('message.Nettoyage') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="NettoyageListe"><a class="d-flex align-items-center" href="{{ route('nettoyage.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="NettoyageAjouter"><a class="d-flex align-items-center" href="{{ route('nettoyage.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>

            {{-- Production --}}
            <li class="navigation-header"><span>{{ __('message.Production') }}</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="inbox"></i>
                    <span class="menu-title text-truncate">{{ __('message.Ramassage') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="RamassageListe"><a class="d-flex align-items-center" href="{{ route('ramassage.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="RamassageAjouter"><a class="d-flex align-items-center" href="{{ route('ramassage.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="package"></i>
                    <span class="menu-title text-truncate">{{ __('message.Produits') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="ProduitListe"><a class="d-flex align-items-center" href="{{ route('produit.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="ProduitAjouter"><a class="d-flex align-items-center" href="{{ route('produit.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>

            {{-- Commerce --}}
            <li class="navigation-header"><span>{{ __('message.Commerce') }}</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="shopping-cart"></i>
                    <span class="menu-title text-truncate">{{ __('message.Vente') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="venteListe"><a class="d-flex align-items-center" href="{{ route('vente.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="venteAjouter"><a class="d-flex align-items-center" href="{{ route('vente.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="users"></i>
                    <span class="menu-title text-truncate">{{ __('message.Clients') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="ClientListe"><a class="d-flex align-items-center" href="{{ route('client.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="ClientAjouter"><a class="d-flex align-items-center" href="{{ route('client.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>

            {{-- Achats --}}
            <li class="navigation-header"><span>{{ __('message.Achats') }}</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="user-minus"></i>
                    <span class="menu-title text-truncate">{{ __('message.Fournisseurs') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="FournisseurListe"><a class="d-flex align-items-center" href="{{ route('fournisseur.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="FournisseurAjouter"><a class="d-flex align-items-center" href="{{ route('fournisseur.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="coffee"></i>
                    <span class="menu-title text-truncate">{{ __('message.Aliment') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="matierePremieresListe"><a class="d-flex align-items-center" href="{{ route('matiere_premiere.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="matierePremieresAjouter"><a class="d-flex align-items-center" href="{{ route('matiere_premiere.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="command"></i>
                    <span class="menu-title text-truncate">{{ __('message.Achats') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="AchatListe"><a class="d-flex align-items-center" href="{{ route('achat.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="AchatAjouter"><a class="d-flex align-items-center" href="{{ route('achat.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>

            {{-- Finances --}}
            <li class="navigation-header"><span>{{ __('message.Finances') }}</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i class="fa fa-university" aria-hidden="true"></i>
                    <span class="menu-title text-truncate">{{ __('message.Caisse') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="CaisseListe"><a class="d-flex align-items-center" href="{{ route('compte.index') }}"><i data-feather="box"></i><span class="menu-item">{{ __('message.Liste des caisses') }}</span></a></li>
                    <li id="DeviseListe"><a class="d-flex align-items-center" href="{{ route('devise.index') }}"><i class="fa fa-eur" aria-hidden="true"></i><span class="menu-item">{{ __('message.Devise') }}</span></a></li>
                    <li class="has-sub">
                        <a class="d-flex align-items-center" href="#"><i data-feather="refresh-ccw"></i><span class="menu-item">{{ __('message.Transaction') }}</span></a>
                        <ul class="menu-content">
                            <li id="TransfertListe"><a class="d-flex align-items-center" href="{{ route('transfert.index') }}"><i class="fas fa-hand-holding-usd" style="font-size: 16px;"></i><span class="menu-item">{{ __('message.Transfert') }}</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="dollar-sign"></i>
                    <span class="menu-title text-truncate">{{ __('message.Dépenses') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="CategorieListe"><a class="d-flex align-items-center" href="{{ route('categorie.index') }}"><i data-feather="plus"></i><span class="menu-item">{{ __('message.Catégories') }}</span></a></li>
                    <li id="DepenseListe"><a class="d-flex align-items-center" href="{{ route('depenses.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="DepenseAjouter"><a class="d-flex align-items-center" href="{{ route('depenses.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="award"></i>
                    <span class="menu-title text-truncate">{{ __('message.Salariés') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="SalarieListe"><a class="d-flex align-items-center" href="{{ route('salarie.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="SalarieAjouter"><a class="d-flex align-items-center" href="{{ route('salarie.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>

            {{-- Paramètres --}}
            <li class="navigation-header"><span>{{ __('message.Paramètres') }}</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="user-check"></i>
                    <span class="menu-title text-truncate">{{ __('message.Utilisateur') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="UserListe"><a class="d-flex align-items-center" href="{{ route('user.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="UserAjouter"><a class="d-flex align-items-center" href="{{ route('user.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate">{{ __('message.Agent') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="AgentListe"><a class="d-flex align-items-center" href="{{ route('agent.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="AgentAjouter"><a class="d-flex align-items-center" href="{{ route('agent.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <img src="{{ asset('app-assets/images/feather/veterinaire.png') }}" style="height: 1.45rem; width: 1.45rem; display: inline;">
                    <span class="menu-title text-truncate ml-1">{{ __('message.Vétérinaires') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="VeterinaireListe"><a class="d-flex align-items-center" href="{{ route('veterinaire.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="VeterinaireAjouter"><a class="d-flex align-items-center" href="{{ route('veterinaire.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="layers"></i>
                    <span class="menu-title text-truncate">{{ __('message.Type d\'animaux') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="Type_animalListe"><a class="d-flex align-items-center" href="{{ route('type_animal.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="Type_animalAjouter"><a class="d-flex align-items-center" href="{{ route('type_animal.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item" id="Settings">
                <a class="d-flex align-items-center" href="{{ route('setting.index') }}">
                    <i data-feather="settings"></i>
                    <span class="menu-title text-truncate">{{ __('message.Réglages') }}</span>
                </a>
            </li>

        </ul>
    </div>
</div>
<!-- END: Main Menu-->
