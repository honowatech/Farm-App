    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow shadow" data-scroll-to-active="true">
        <div class="navbar-header pl-0">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand mx-0" href="{{route('home')}}"><span class="brand-logo">

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
            <ul class="navigation navigation-main mt-1" id="main-menu-navigation" data-menu="menu-navigation" >
                <li class=" navigation-header">{{ __('message.Accueil')}}<i data-feather="more-horizontal"></i>
                    <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="user">{{ __('message.Tableau de bord')}}</span></a>
                        <ul class="menu-content">
                            <li id="Acceuil"><a class="d-flex align-items-center" href="{{route('home')}}"><i data-feather="circle"></i><span class="menu-item">{{ __('message.Statistique')}}</span></a>
                            </li>
                        </ul>
                    </li>
                </li>
                <li class=" navigation-header">{{ __('message.Administration')}}<i data-feather="more-horizontal"></i>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather="user-check"></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Utilisateur')}}</span></a>
                    <ul class="menu-content">
                        <li id="UserListe"><a class="d-flex align-items-center" href="{{route('user.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="UserAjouter"><a class="d-flex align-items-center" href="{{route('user.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Agent')}}</span></a>
                    <ul class="menu-content">
                        <li id="AgentListe"><a class="d-flex align-items-center" href="{{route('agent.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="AgentAjouter"><a class="d-flex align-items-center" href="{{route('agent.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                 <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='award'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Salariés')}}</span></a>
                    <ul class="menu-content">
                        <li id="SalarieListe"><a class="d-flex align-items-center" href="{{route('salarie.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="SalarieAjouter"><a class="d-flex align-items-center" href="{{route('salarie.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li id="Settings"><a class="d-flex align-items-center" href="{{route('setting.index')}}"><i data-feather="settings"></i><span class="menu-item">{{__('message.Paramètres')}}</span></a>
                {{-- <li id="SuiviSalarieListe"><a class="d-flex align-items-center" href="{{route('paiement_salaire.index')}}"><i data-feather='calendar'></i><span class="menu-item">Suivi des Salaires</span></a>
                </li> --}}

                <li class=" navigation-header">{{__('message.Utilitaires')}}<i data-feather="more-horizontal"></i>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='columns'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Bande')}}</span></a>
                    <ul class="menu-content">
                        <li id="BandeListe"><a class="d-flex align-items-center" href="{{route('bande.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="BandeAjouter"><a class="d-flex align-items-center" href="{{route('bande.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather="box"></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Bâtiment')}}</span></a>
                    <ul class="menu-content">
                        <li id="BatimentListe"><a class="d-flex align-items-center" href="{{route('batiment.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="BatimentAjouter"><a class="d-flex align-items-center" href="{{route('batiment.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='layers'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Type d\'animaux')}}</span></a>
                    <ul class="menu-content">
                        <li id="Type_animalListe"><a class="d-flex align-items-center" href="{{route('type_animal.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="Type_animalAjouter"><a class="d-flex align-items-center" href="{{route('type_animal.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='inbox'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Ramassage')}}</span></a>
                    <ul class="menu-content">
                        <li id="RamassageListe"><a class="d-flex align-items-center" href="{{route('ramassage.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="RamassageAjouter"><a class="d-flex align-items-center" href="{{route('ramassage.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='inbox'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Nutrition')}}</span></a>
                    <ul class="menu-content">
                        <li id="NutritionListe"><a class="d-flex align-items-center" href="{{route('nutrition.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="NutritionAjouter"><a class="d-flex align-items-center" href="{{route('nutrition.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='wind'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Nettoyage')}}</span></a>
                    <ul class="menu-content">
                        <li id="NettoyageListe"><a class="d-flex align-items-center" href="{{route('nettoyage.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="NettoyageAjouter"><a class="d-flex align-items-center" href="{{route('nettoyage.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"> <img style="height: 1.45rem; width: 1.45rem; display: inline;" src="{{asset('app-assets/images/feather/veterinaire.png ')}}"> <span class="ml-1 menu-title text-truncate">{{__('message.Vétérinaires')}}</span> </a>
                    <ul class="menu-content">
                        <li id="VeterinaireListe"><a class="d-flex align-items-center" href="{{route('veterinaire.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="VeterinaireAjouter"><a class="d-flex align-items-center" href="{{route('veterinaire.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>

                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><img src="{{asset('app-assets/images/feather/vaccination.png ')}}" style="height: 1.45rem; width: 1.45rem; display: inline;"><span class="menu-title text-truncate pl-1" data-i18n="user">{{__('message.Vaccination')}}</span></a>
                    <ul class="menu-content">
                        <li id="SeanceVaccinationListe"><a class="d-flex align-items-center" href="{{route('seanceVaccination.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="SeanceVaccinationAjouter"><a class="d-flex align-items-center" href="{{route('seanceVaccination.create')}}"><i data-feather="circle"></i><span class="menu-item">Ajouter</span></a>
                        </li>

                    </ul>
                </li>

                <li class=" navigation-header">{{__('message.Besoins')}}<i data-feather="more-horizontal"></i>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='user-minus'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Fournisseurs')}}</span></a>
                    <ul class="menu-content">
                        <li id="FournisseurListe"><a class="d-flex align-items-center" href="{{route('fournisseur.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="FournisseurAjouter"><a class="d-flex align-items-center" href="{{route('fournisseur.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='coffee'></i><span class="menu-title text-truncate" data-i18n="user">Aliment</span></a>
                    <ul class="menu-content">
                        <li id="matierePremieresListe"><a class="d-flex align-items-center" href="{{route('matiere_premiere.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="matierePremieresAjouter"><a class="d-flex align-items-center" href="{{route('matiere_premiere.create')}}"><i data-feather="circle"></i><span class="menu-item">Ajouter</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='command'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Achats')}}</span></a>
                    <ul class="menu-content">
                        <li id="AchatListe"><a class="d-flex align-items-center" href="{{route('achat.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="AchatAjouter"><a class="d-flex align-items-center" href="{{route('achat.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>

                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                    <i class="fa fa-university" aria-hidden="true"></i><span class="menu-item" style="font-size: 1rem"  data-i18n="Preview" style="font-size: 1rem">Caisse</span></a>
                    <ul class="menu-content">

                        </li>
                        <li id="CaisseListe"><a class="d-flex align-items-center" href="{{route('compte.index')}}"><i data-feather='box'></i><span class="menu-item" style="font-size: 1rem" data-i18n="Preview">Liste des caisses</span></a>

                        <li  class=" nav-item" id="DeviseListe"><a class="d-flex align-items-center" href="{{route('devise.index')}}"> <i class="fa fa-eur" aria-hidden="true"></i><span class="menu-item" style="font-size: 1rem" data-i18n="Preview">Devise</span></a>
                        </li>
                        <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                            <i data-feather='refresh-ccw'></i><span class="menu-item" style="font-size: 1rem" data-i18n="Preview">Transaction</span></a>
                            <ul class="menu-content">
                                <li id="TransfertListe"><a class="d-flex align-items-center" href="{{route('transfert.index')}}"><i class="fas fa-hand-holding-usd" style="font-size: 16px;"></i> <span class="menu-item" style="font-size: 1rem" data-i18n="Preview">transfert</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><i class="fa-brands fa-shopify"></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Dépenses')}}</span></a>
                    <ul class="menu-content">
                        <li class=" nav-item" id ="CategorieListe"><a class="d-flex align-items-center" href="{{route('categorie.index')}}"><i data-feather='plus'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Catégories')}}</span></a>
                         </li>
                        <li id="DepenseListe"><a class="d-flex align-items-center" href="{{route('depenses.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="DepenseAjouter"><a class="d-flex align-items-center" href="{{route('depenses.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>

                    </ul>
                </li>
                <li class=" navigation-header">{{__('message.Commerces')}}<i data-feather="more-horizontal"></i>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='shopping-cart'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Produits')}}</span></a>
                    <ul class="menu-content">
                        <li id="ProduitListe"><a class="d-flex align-items-center" href="{{route('produit.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="ProduitAjouter"><a class="d-flex align-items-center" href="{{route('produit.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather='shopping-cart'></i><span class="menu-title text-truncate" data-i18n="user">{{__('message.Vente')}}</span></a>
                    <ul class="menu-content">
                        <li id="venteListe"><a class="d-flex align-items-center" href="{{route('vente.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="venteAjouter"><a class="d-flex align-items-center" href="{{route('vente.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>


                <li class=" nav-item"><a class="d-flex align-items-center"><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="user-check">{{__('message.Clients')}}</span></a>
                    <ul class="menu-content">
                        <li id="ClientListe"><a class="d-flex align-items-center" href="{{route('client.index')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Liste')}}</span></a>
                        </li>
                        <li id="ClientAjouter"><a class="d-flex align-items-center" href="{{route('client.create')}}"><i data-feather="circle"></i><span class="menu-item">{{__('message.Ajouter')}}</span></a>
                        </li>
                    </ul>
                </li>
                </li>
                <li class=" navigation-header"><i data-feather="more-horizontal"></i>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->
