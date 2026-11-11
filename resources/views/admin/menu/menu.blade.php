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
            <li class="nav-item" id="Acceuil" data-route="home">
                <a class="d-flex align-items-center" href="{{ route('home') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">{{ __('message.Tableau de bord') }}</span>
                </a>
            </li>
            <li class="nav-item" id="AlerteListe" data-route="alerte.index">
                <a class="d-flex align-items-center" href="{{ route('alerte.index') }}">
                    <i data-feather="bell"></i>
                    <span class="menu-title text-truncate">Alertes</span>
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
                    <li id="BandeListe" data-route="bande.index,bande.show,bande.edit,bande.search,gestion_bande,get-form-bande,terminer_bande"><a class="d-flex align-items-center" href="{{ route('bande.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="BandeAjouter" data-route="bande.create"><a class="d-flex align-items-center" href="{{ route('bande.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="box"></i>
                    <span class="menu-title text-truncate">{{ __('message.Bâtiment') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="BatimentListe" data-route="batiment.index,batiment.show,batiment.edit"><a class="d-flex align-items-center" href="{{ route('batiment.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="BatimentAjouter" data-route="batiment.create"><a class="d-flex align-items-center" href="{{ route('batiment.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="activity"></i>
                    <span class="menu-title text-truncate">{{ __('message.Nutrition') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="NutritionListe" data-route="nutrition.index,nutrition.show,nutrition.edit,nutrition.check,nutrition.search,nutrition.calendar,nutrition.calendar.data,load_form"><a class="d-flex align-items-center" href="{{ route('nutrition.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="NutritionAjouter" data-route="nutrition.create"><a class="d-flex align-items-center" href="{{ route('nutrition.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <img src="{{ asset('app-assets/images/feather/vaccination.png') }}" style="height: 1.45rem; width: 1.45rem; display: inline;">
                    <span class="menu-title text-truncate pl-1">{{ __('message.Vaccination') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="SeanceVaccinationListe" data-route="seanceVaccination.index,seanceVaccination.show,seanceVaccination.edit"><a class="d-flex align-items-center" href="{{ route('seanceVaccination.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="SeanceVaccinationAjouter" data-route="seanceVaccination.create"><a class="d-flex align-items-center" href="{{ route('seanceVaccination.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="droplet"></i>
                    <span class="menu-title text-truncate">{{ __('message.Nettoyage') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="NettoyageListe" data-route="nettoyage.index,nettoyage.show,nettoyage.edit"><a class="d-flex align-items-center" href="{{ route('nettoyage.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="NettoyageAjouter" data-route="nettoyage.create"><a class="d-flex align-items-center" href="{{ route('nettoyage.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="alert-triangle"></i>
                    <span class="menu-title text-truncate">Mortalité</span>
                </a>
                <ul class="menu-content">
                    <li id="MortaliteListe" data-route="mortalite.index,mortalite.show,mortalite.edit"><a class="d-flex align-items-center" href="{{ route('mortalite.index') }}"><i data-feather="circle"></i><span class="menu-item">Liste</span></a></li>
                    <li id="MortaliteAjouter" data-route="mortalite.create"><a class="d-flex align-items-center" href="{{ route('mortalite.create') }}"><i data-feather="circle"></i><span class="menu-item">Ajouter</span></a></li>
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
                    <li id="RamassageListe" data-route="ramassage.index,ramassage.show,ramassage.edit,ramassage.search"><a class="d-flex align-items-center" href="{{ route('ramassage.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="RamassageAjouter" data-route="ramassage.create"><a class="d-flex align-items-center" href="{{ route('ramassage.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="package"></i>
                    <span class="menu-title text-truncate">{{ __('message.Produits') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="ProduitListe" data-route="produit.index,produit.show,produit.edit,produit.export,produit.export.pdf,derive_produit.index,derive_produit.create,derive_produit.show,derive_produit.edit"><a class="d-flex align-items-center" href="{{ route('produit.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="ProduitAjouter" data-route="produit.create"><a class="d-flex align-items-center" href="{{ route('produit.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
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
                    <li id="venteListe" data-route="vente.index,vente.show,vente.edit,vente.search,vente.export,vente.export.pdf,vente.facture.pdf,facturation"><a class="d-flex align-items-center" href="{{ route('vente.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="venteAjouter" data-route="vente.create,formulaire"><a class="d-flex align-items-center" href="{{ route('vente.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="users"></i>
                    <span class="menu-title text-truncate">{{ __('message.Clients') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="ClientListe" data-route="client.index,client.show,client.edit,client.export,client.export.pdf"><a class="d-flex align-items-center" href="{{ route('client.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="ClientAjouter" data-route="client.create"><a class="d-flex align-items-center" href="{{ route('client.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
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
                    <li id="FournisseurListe" data-route="fournisseur.index,fournisseur.show,fournisseur.edit,fournisseur.export,fournisseur.export.pdf"><a class="d-flex align-items-center" href="{{ route('fournisseur.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="FournisseurAjouter" data-route="fournisseur.create"><a class="d-flex align-items-center" href="{{ route('fournisseur.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="coffee"></i>
                    <span class="menu-title text-truncate">{{ __('message.Aliment') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="matierePremieresListe" data-route="matiere_premiere.index,matiere_premiere.show,matiere_premiere.edit"><a class="d-flex align-items-center" href="{{ route('matiere_premiere.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="matierePremieresAjouter" data-route="matiere_premiere.create"><a class="d-flex align-items-center" href="{{ route('matiere_premiere.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="command"></i>
                    <span class="menu-title text-truncate">{{ __('message.Achats') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="AchatListe" data-route="achat.index,achat.show,achat.edit,achat.search,achat.export,achat.export.pdf"><a class="d-flex align-items-center" href="{{ route('achat.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="AchatAjouter" data-route="achat.create"><a class="d-flex align-items-center" href="{{ route('achat.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>

            {{-- Finances --}}
            <li class="navigation-header"><span>{{ __('message.Finances') }}</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item" id="RentabiliteListe" data-route="rentabilite.index">
                <a class="d-flex align-items-center" href="{{ route('rentabilite.index') }}">
                    <i data-feather="trending-up"></i>
                    <span class="menu-title text-truncate">Rentabilité</span>
                </a>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="smartphone"></i>
                    <span class="menu-title text-truncate">Paiements</span>
                </a>
                <ul class="menu-content">
                    <li id="PaiementIndex" data-route="paiement.index"><a class="d-flex align-items-center" href="{{ route('paiement.index') }}"><i data-feather="circle"></i><span class="menu-item">Transactions</span></a></li>
                    <li id="PaiementTest" data-route="paiement.test"><a class="d-flex align-items-center" href="{{ route('paiement.test') }}"><i data-feather="play"></i><span class="menu-item">Tester un paiement</span></a></li>
                    <li id="PaiementConfig" data-route="paiement.settings,paiement.settings.update,paiement.settings.test"><a class="d-flex align-items-center" href="{{ route('paiement.settings') }}"><i data-feather="settings"></i><span class="menu-item">Configuration</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i class="fa fa-university" aria-hidden="true"></i>
                    <span class="menu-title text-truncate">{{ __('message.Caisse') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="CaisseListe" data-route="compte.index,compte.create,compte.show,compte.edit"><a class="d-flex align-items-center" href="{{ route('compte.index') }}"><i data-feather="box"></i><span class="menu-item">{{ __('message.Liste des caisses') }}</span></a></li>
                    <li id="DeviseListe" data-route="devise.index,devise.create,devise.show,devise.edit"><a class="d-flex align-items-center" href="{{ route('devise.index') }}"><i class="fa fa-eur" aria-hidden="true"></i><span class="menu-item">{{ __('message.Devise') }}</span></a></li>
                    <li class="has-sub">
                        <a class="d-flex align-items-center" href="#"><i data-feather="refresh-ccw"></i><span class="menu-item">{{ __('message.Transaction') }}</span></a>
                        <ul class="menu-content">
                            <li id="TransfertListe" data-route="transfert.index,transfert.create,transfert.show,transfert.edit"><a class="d-flex align-items-center" href="{{ route('transfert.index') }}"><i class="fas fa-hand-holding-usd" style="font-size: 16px;"></i><span class="menu-item">{{ __('message.Transfert') }}</span></a></li>
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
                    <li id="CategorieListe" data-route="categorie.index,categorie.create,categorie.show,categorie.edit"><a class="d-flex align-items-center" href="{{ route('categorie.index') }}"><i data-feather="plus"></i><span class="menu-item">{{ __('message.Catégories') }}</span></a></li>
                    <li id="DepenseListe" data-route="depenses.index,depenses.show,depenses.edit"><a class="d-flex align-items-center" href="{{ route('depenses.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="DepenseAjouter" data-route="depenses.create"><a class="d-flex align-items-center" href="{{ route('depenses.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="award"></i>
                    <span class="menu-title text-truncate">{{ __('message.Salariés') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="SalarieListe" data-route="salarie.index,salarie.show,salarie.edit,salarie.search"><a class="d-flex align-items-center" href="{{ route('salarie.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="SalarieAjouter" data-route="salarie.create"><a class="d-flex align-items-center" href="{{ route('salarie.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
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
                    <li id="UserListe" data-route="user.index,user.show,user.edit"><a class="d-flex align-items-center" href="{{ route('user.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="UserAjouter" data-route="user.create"><a class="d-flex align-items-center" href="{{ route('user.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item" id="AuditListe" data-route="audit.index">
                <a class="d-flex align-items-center" href="{{ route('audit.index') }}">
                    <i data-feather="clock"></i>
                    <span class="menu-title text-truncate">Historique</span>
                </a>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate">{{ __('message.Agent') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="AgentListe" data-route="agent.index,agent.show,agent.edit"><a class="d-flex align-items-center" href="{{ route('agent.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="AgentAjouter" data-route="agent.create"><a class="d-flex align-items-center" href="{{ route('agent.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <img src="{{ asset('app-assets/images/feather/veterinaire.png') }}" style="height: 1.45rem; width: 1.45rem; display: inline;">
                    <span class="menu-title text-truncate ml-1">{{ __('message.Vétérinaires') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="VeterinaireListe" data-route="veterinaire.index,veterinaire.show,veterinaire.edit"><a class="d-flex align-items-center" href="{{ route('veterinaire.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="VeterinaireAjouter" data-route="veterinaire.create"><a class="d-flex align-items-center" href="{{ route('veterinaire.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="layers"></i>
                    <span class="menu-title text-truncate">{{ __('message.Type d\'animaux') }}</span>
                </a>
                <ul class="menu-content">
                    <li id="Type_animalListe" data-route="type_animal.index,type_animal.show,type_animal.edit"><a class="d-flex align-items-center" href="{{ route('type_animal.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    <li id="Type_animalAjouter" data-route="type_animal.create"><a class="d-flex align-items-center" href="{{ route('type_animal.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                </ul>
            </li>
            <li class="nav-item" id="Settings" data-route="setting.index,setting.show,setting.edit,foods-setting.index,foods-setting.create,foods-setting.show,foods-setting.edit,foods-setting.get-foods">
                <a class="d-flex align-items-center" href="{{ route('setting.index') }}">
                    <i data-feather="settings"></i>
                    <span class="menu-title text-truncate">{{ __('message.Réglages') }}</span>
                </a>
            </li>

        </ul>
    </div>
</div>
<!-- END: Main Menu-->
