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
            @canroute('alerte.index')
            <li class="nav-item" id="AlerteListe" data-route="alerte.index">
                <a class="d-flex align-items-center" href="{{ route('alerte.index') }}">
                    <i data-feather="bell"></i>
                    <span class="menu-title text-truncate">Alertes</span>
                </a>
            </li>
            @endcanroute

            {{-- Élevage --}}
            @cananypermission(['bande.view', 'batiment.view', 'nutrition.view', 'vaccination.view', 'nettoyage.view', 'mortalite.view'])
            <li class="navigation-header"><span>{{ __('message.Élevage') }}</span><i data-feather="more-horizontal"></i></li>
            @endcananypermission

            @cananypermission(['bande.view', 'bande.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="columns"></i>
                    <span class="menu-title text-truncate">{{ __('message.Bande') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('bande.index')
                    <li id="BandeListe" data-route="bande.index,bande.show,bande.edit,bande.search,gestion_bande,get-form-bande,terminer_bande"><a class="d-flex align-items-center" href="{{ route('bande.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('bande.create')
                    <li id="BandeAjouter" data-route="bande.create"><a class="d-flex align-items-center" href="{{ route('bande.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['batiment.view', 'batiment.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="box"></i>
                    <span class="menu-title text-truncate">{{ __('message.Bâtiment') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('batiment.index')
                    <li id="BatimentListe" data-route="batiment.index,batiment.show,batiment.edit"><a class="d-flex align-items-center" href="{{ route('batiment.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('batiment.create')
                    <li id="BatimentAjouter" data-route="batiment.create"><a class="d-flex align-items-center" href="{{ route('batiment.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['nutrition.view', 'nutrition.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="activity"></i>
                    <span class="menu-title text-truncate">{{ __('message.Nutrition') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('nutrition.index')
                    <li id="NutritionListe" data-route="nutrition.index,nutrition.show,nutrition.edit,nutrition.check,nutrition.search,nutrition.calendar,nutrition.calendar.data,load_form"><a class="d-flex align-items-center" href="{{ route('nutrition.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('nutrition.create')
                    <li id="NutritionAjouter" data-route="nutrition.create"><a class="d-flex align-items-center" href="{{ route('nutrition.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['vaccination.view', 'vaccination.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <img src="{{ asset('app-assets/images/feather/vaccination.png') }}" style="height: 1.45rem; width: 1.45rem; display: inline;">
                    <span class="menu-title text-truncate pl-1">{{ __('message.Vaccination') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('seanceVaccination.index')
                    <li id="SeanceVaccinationListe" data-route="seanceVaccination.index,seanceVaccination.show,seanceVaccination.edit"><a class="d-flex align-items-center" href="{{ route('seanceVaccination.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('seanceVaccination.create')
                    <li id="SeanceVaccinationAjouter" data-route="seanceVaccination.create"><a class="d-flex align-items-center" href="{{ route('seanceVaccination.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                    @canroute('programme_vaccination.index')
                    <li id="ProgrammeVaccinationListe" data-route="programme_vaccination.index"><a class="d-flex align-items-center" href="{{ route('programme_vaccination.index') }}"><i data-feather="calendar"></i><span class="menu-item">Programmes</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['nettoyage.view', 'nettoyage.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="droplet"></i>
                    <span class="menu-title text-truncate">{{ __('message.Nettoyage') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('nettoyage.index')
                    <li id="NettoyageListe" data-route="nettoyage.index,nettoyage.show,nettoyage.edit"><a class="d-flex align-items-center" href="{{ route('nettoyage.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('nettoyage.create')
                    <li id="NettoyageAjouter" data-route="nettoyage.create"><a class="d-flex align-items-center" href="{{ route('nettoyage.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['mortalite.view', 'mortalite.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="alert-triangle"></i>
                    <span class="menu-title text-truncate">Mortalité</span>
                </a>
                <ul class="menu-content">
                    @canroute('mortalite.index')
                    <li id="MortaliteListe" data-route="mortalite.index,mortalite.show,mortalite.edit"><a class="d-flex align-items-center" href="{{ route('mortalite.index') }}"><i data-feather="circle"></i><span class="menu-item">Liste</span></a></li>
                    @endcanroute
                    @canroute('mortalite.create')
                    <li id="MortaliteAjouter" data-route="mortalite.create"><a class="d-flex align-items-center" href="{{ route('mortalite.create') }}"><i data-feather="circle"></i><span class="menu-item">Ajouter</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            {{-- Production --}}
            @cananypermission(['ramassage.view', 'produit.view', 'stock_produit.view'])
            <li class="navigation-header"><span>{{ __('message.Production') }}</span><i data-feather="more-horizontal"></i></li>
            @endcananypermission

            @cananypermission(['ramassage.view', 'ramassage.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="inbox"></i>
                    <span class="menu-title text-truncate">{{ __('message.Ramassage') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('ramassage.index')
                    <li id="RamassageListe" data-route="ramassage.index,ramassage.show,ramassage.edit,ramassage.search"><a class="d-flex align-items-center" href="{{ route('ramassage.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('ramassage.create')
                    <li id="RamassageAjouter" data-route="ramassage.create"><a class="d-flex align-items-center" href="{{ route('ramassage.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['produit.view', 'derive_produit.view'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="package"></i>
                    <span class="menu-title text-truncate">{{ __('message.Produits') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('produit.index')
                    <li id="ProduitListe" data-route="produit.index,produit.show,produit.edit,produit.export,produit.export.pdf,derive_produit.index,derive_produit.create,derive_produit.show,derive_produit.edit"><a class="d-flex align-items-center" href="{{ route('produit.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('produit.create')
                    <li id="ProduitAjouter" data-route="produit.create"><a class="d-flex align-items-center" href="{{ route('produit.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @canroute('stock_produit.index')
            <li class="nav-item" id="StockProduitListe" data-route="stock_produit.index,stock_produit.show,stock_produit.edit">
                <a class="d-flex align-items-center" href="{{ route('stock_produit.index') }}">
                    <i data-feather="layers"></i>
                    <span class="menu-title text-truncate">Stock produits</span>
                </a>
            </li>
            @endcanroute

            {{-- Commerce --}}
            @cananypermission(['vente.view', 'client.view'])
            <li class="navigation-header"><span>{{ __('message.Commerce') }}</span><i data-feather="more-horizontal"></i></li>
            @endcananypermission

            @cananypermission(['vente.view', 'vente.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="shopping-cart"></i>
                    <span class="menu-title text-truncate">{{ __('message.Vente') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('vente.index')
                    <li id="venteListe" data-route="vente.index,vente.show,vente.edit,vente.search,vente.export,vente.export.pdf,vente.facture.pdf,facturation"><a class="d-flex align-items-center" href="{{ route('vente.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('vente.create')
                    <li id="venteAjouter" data-route="vente.create,formulaire"><a class="d-flex align-items-center" href="{{ route('vente.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['client.view', 'client.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="users"></i>
                    <span class="menu-title text-truncate">{{ __('message.Clients') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('client.index')
                    <li id="ClientListe" data-route="client.index,client.show,client.edit,client.export,client.export.pdf"><a class="d-flex align-items-center" href="{{ route('client.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('client.create')
                    <li id="ClientAjouter" data-route="client.create"><a class="d-flex align-items-center" href="{{ route('client.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            {{-- Achats --}}
            @cananypermission(['fournisseur.view', 'matiere_premiere.view', 'achat.view', 'stock_matiere_premiere.view', 'unite.view'])
            <li class="navigation-header"><span>{{ __('message.Achats') }}</span><i data-feather="more-horizontal"></i></li>
            @endcananypermission

            @cananypermission(['fournisseur.view', 'fournisseur.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="user-minus"></i>
                    <span class="menu-title text-truncate">{{ __('message.Fournisseurs') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('fournisseur.index')
                    <li id="FournisseurListe" data-route="fournisseur.index,fournisseur.show,fournisseur.edit,fournisseur.export,fournisseur.export.pdf"><a class="d-flex align-items-center" href="{{ route('fournisseur.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('fournisseur.create')
                    <li id="FournisseurAjouter" data-route="fournisseur.create"><a class="d-flex align-items-center" href="{{ route('fournisseur.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['matiere_premiere.view', 'matiere_premiere.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="coffee"></i>
                    <span class="menu-title text-truncate">{{ __('message.Aliment') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('matiere_premiere.index')
                    <li id="matierePremieresListe" data-route="matiere_premiere.index,matiere_premiere.show,matiere_premiere.edit"><a class="d-flex align-items-center" href="{{ route('matiere_premiere.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('matiere_premiere.create')
                    <li id="matierePremieresAjouter" data-route="matiere_premiere.create"><a class="d-flex align-items-center" href="{{ route('matiere_premiere.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['achat.view', 'achat.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="command"></i>
                    <span class="menu-title text-truncate">{{ __('message.Achats') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('achat.index')
                    <li id="AchatListe" data-route="achat.index,achat.show,achat.edit,achat.search,achat.export,achat.export.pdf"><a class="d-flex align-items-center" href="{{ route('achat.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('achat.create')
                    <li id="AchatAjouter" data-route="achat.create"><a class="d-flex align-items-center" href="{{ route('achat.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @canroute('stock_matiere_premiere.index')
            <li class="nav-item" id="StockMatiereListe" data-route="stock_matiere_premiere.index,stock_matiere_premiere.show,stock_matiere_premiere.edit">
                <a class="d-flex align-items-center" href="{{ route('stock_matiere_premiere.index') }}">
                    <i data-feather="archive"></i>
                    <span class="menu-title text-truncate">Stock aliments</span>
                </a>
            </li>
            @endcanroute

            @cananypermission(['unite.view', 'unite.manage'])
            <li class="nav-item" id="UniteListe" data-route="unite.index,unite.show,unite.edit">
                <a class="d-flex align-items-center" href="{{ route('unite.index') }}">
                    <i data-feather="maximize"></i>
                    <span class="menu-title text-truncate">{{ __('message.Unités') }}</span>
                </a>
            </li>
            @endcananypermission

            {{-- Finances --}}
            @cananypermission(['rentabilite.view', 'paiement.view', 'caisse.view', 'depense.view', 'salarie.view', 'paie.view'])
            <li class="navigation-header"><span>{{ __('message.Finances') }}</span><i data-feather="more-horizontal"></i></li>
            @endcananypermission

            @canroute('rentabilite.index')
            <li class="nav-item" id="RentabiliteListe" data-route="rentabilite.index">
                <a class="d-flex align-items-center" href="{{ route('rentabilite.index') }}">
                    <i data-feather="trending-up"></i>
                    <span class="menu-title text-truncate">Rentabilité</span>
                </a>
            </li>
            @endcanroute

            @cananypermission(['paiement.view', 'paiement.manage', 'paiement.settings'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="smartphone"></i>
                    <span class="menu-title text-truncate">Paiements</span>
                </a>
                <ul class="menu-content">
                    @canroute('paiement.index')
                    <li id="PaiementIndex" data-route="paiement.index"><a class="d-flex align-items-center" href="{{ route('paiement.index') }}"><i data-feather="circle"></i><span class="menu-item">Transactions</span></a></li>
                    @endcanroute
                    @canroute('paiement.test')
                    <li id="PaiementTest" data-route="paiement.test"><a class="d-flex align-items-center" href="{{ route('paiement.test') }}"><i data-feather="play"></i><span class="menu-item">Tester un paiement</span></a></li>
                    @endcanroute
                    @canroute('paiement.settings')
                    <li id="PaiementConfig" data-route="paiement.settings,paiement.settings.update,paiement.settings.test"><a class="d-flex align-items-center" href="{{ route('paiement.settings') }}"><i data-feather="settings"></i><span class="menu-item">Configuration</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['caisse.view', 'caisse.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i class="fa fa-university" aria-hidden="true"></i>
                    <span class="menu-title text-truncate">{{ __('message.Caisse') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('compte.index')
                    <li id="CaisseListe" data-route="compte.index,compte.create,compte.show,compte.edit"><a class="d-flex align-items-center" href="{{ route('compte.index') }}"><i data-feather="box"></i><span class="menu-item">{{ __('message.Liste des caisses') }}</span></a></li>
                    @endcanroute
                    @canroute('devise.index')
                    <li id="DeviseListe" data-route="devise.index,devise.create,devise.show,devise.edit"><a class="d-flex align-items-center" href="{{ route('devise.index') }}"><i class="fa fa-eur" aria-hidden="true"></i><span class="menu-item">{{ __('message.Devise') }}</span></a></li>
                    @endcanroute
                    @canroute('transfert.index')
                    <li id="TransfertListe" data-route="transfert.index,transfert.create,transfert.show,transfert.edit"><a class="d-flex align-items-center" href="{{ route('transfert.index') }}"><i class="fas fa-hand-holding-usd" style="font-size: 16px;"></i><span class="menu-item">{{ __('message.Transfert') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['depense.view', 'depense.manage', 'categorie_depense.view'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="dollar-sign"></i>
                    <span class="menu-title text-truncate">{{ __('message.Dépenses') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('categorie.index')
                    <li id="CategorieListe" data-route="categorie.index,categorie.create,categorie.show,categorie.edit"><a class="d-flex align-items-center" href="{{ route('categorie.index') }}"><i data-feather="plus"></i><span class="menu-item">{{ __('message.Catégories') }}</span></a></li>
                    @endcanroute
                    @canroute('depenses.index')
                    <li id="DepenseListe" data-route="depenses.index,depenses.show,depenses.edit"><a class="d-flex align-items-center" href="{{ route('depenses.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('depenses.create')
                    <li id="DepenseAjouter" data-route="depenses.create"><a class="d-flex align-items-center" href="{{ route('depenses.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['salarie.view', 'salarie.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="award"></i>
                    <span class="menu-title text-truncate">{{ __('message.Salariés') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('salarie.index')
                    <li id="SalarieListe" data-route="salarie.index,salarie.show,salarie.edit,salarie.search"><a class="d-flex align-items-center" href="{{ route('salarie.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('salarie.create')
                    <li id="SalarieAjouter" data-route="salarie.create"><a class="d-flex align-items-center" href="{{ route('salarie.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['paie.view', 'paie.manage'])
            <li class="nav-item" id="PaieListe" data-route="paiement_salaire.index,paiement_salaire.show,paiement_salaire.edit">
                <a class="d-flex align-items-center" href="{{ route('paiement_salaire.index') }}">
                    <i data-feather="credit-card"></i>
                    <span class="menu-title text-truncate">Paie</span>
                </a>
            </li>
            @endcananypermission

            {{-- Paramètres --}}
            @cananypermission(['user.view', 'agent.view', 'veterinaire.view', 'type_animal.view', 'setting.view', 'foods_setting.view', 'audit.view'])
            <li class="navigation-header"><span>{{ __('message.Paramètres') }}</span><i data-feather="more-horizontal"></i></li>
            @endcananypermission

            @cananypermission(['user.view', 'user.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="user-check"></i>
                    <span class="menu-title text-truncate">{{ __('message.Utilisateur') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('user.index')
                    <li id="UserListe" data-route="user.index,user.show,user.edit"><a class="d-flex align-items-center" href="{{ route('user.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('user.create')
                    <li id="UserAjouter" data-route="user.create"><a class="d-flex align-items-center" href="{{ route('user.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @canroute('audit.index')
            <li class="nav-item" id="AuditListe" data-route="audit.index">
                <a class="d-flex align-items-center" href="{{ route('audit.index') }}">
                    <i data-feather="clock"></i>
                    <span class="menu-title text-truncate">Historique</span>
                </a>
            </li>
            @endcanroute

            @cananypermission(['agent.view', 'agent.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="user"></i>
                    <span class="menu-title text-truncate">{{ __('message.Agent') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('agent.index')
                    <li id="AgentListe" data-route="agent.index,agent.show,agent.edit"><a class="d-flex align-items-center" href="{{ route('agent.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('agent.create')
                    <li id="AgentAjouter" data-route="agent.create"><a class="d-flex align-items-center" href="{{ route('agent.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['veterinaire.view', 'veterinaire.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <img src="{{ asset('app-assets/images/feather/veterinaire.png') }}" style="height: 1.45rem; width: 1.45rem; display: inline;">
                    <span class="menu-title text-truncate ml-1">{{ __('message.Vétérinaires') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('veterinaire.index')
                    <li id="VeterinaireListe" data-route="veterinaire.index,veterinaire.show,veterinaire.edit"><a class="d-flex align-items-center" href="{{ route('veterinaire.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('veterinaire.create')
                    <li id="VeterinaireAjouter" data-route="veterinaire.create"><a class="d-flex align-items-center" href="{{ route('veterinaire.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['type_animal.view', 'type_animal.manage'])
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="layers"></i>
                    <span class="menu-title text-truncate">{{ __('message.Type d\'animaux') }}</span>
                </a>
                <ul class="menu-content">
                    @canroute('type_animal.index')
                    <li id="Type_animalListe" data-route="type_animal.index,type_animal.show,type_animal.edit"><a class="d-flex align-items-center" href="{{ route('type_animal.index') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Liste') }}</span></a></li>
                    @endcanroute
                    @canroute('type_animal.create')
                    <li id="Type_animalAjouter" data-route="type_animal.create"><a class="d-flex align-items-center" href="{{ route('type_animal.create') }}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Ajouter') }}</span></a></li>
                    @endcanroute
                </ul>
            </li>
            @endcananypermission

            @cananypermission(['setting.view', 'setting.manage', 'foods_setting.view', 'foods_setting.manage'])
            <li class="nav-item" id="Settings" data-route="setting.index,setting.show,setting.edit,foods-setting.index,foods-setting.create,foods-setting.show,foods-setting.edit,foods-setting.get-foods">
                <a class="d-flex align-items-center" href="{{ route('setting.index') }}">
                    <i data-feather="settings"></i>
                    <span class="menu-title text-truncate">{{ __('message.Réglages') }}</span>
                </a>
            </li>
            @endcananypermission

        </ul>
    </div>
</div>
<!-- END: Main Menu-->
