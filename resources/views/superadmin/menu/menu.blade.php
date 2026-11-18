<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow shadow" data-scroll-to-active="true">
    <div class="navbar-header pl-0">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand mx-0" href="{{ route('admin.dashboard') }}">
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

            <li class="navigation-header"><span>Plateforme</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item" id="AdminDashboard" data-route="admin.dashboard">
                <a class="d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">Tableau de bord central</span>
                </a>
            </li>

            <li class="navigation-header"><span>Gestion</span><i data-feather="more-horizontal"></i></li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="grid"></i>
                    <span class="menu-title text-truncate">Entreprises</span>
                </a>
                <ul class="menu-content">
                    <li id="AdminTenants" data-route="admin.tenants.index,admin.tenants.create"><a class="d-flex align-items-center" href="{{ route('admin.tenants.index') }}"><i data-feather="circle"></i><span class="menu-item">Liste</span></a></li>
                    <li id="AdminTenantsCreate" data-route="admin.tenants.create"><a class="d-flex align-items-center" href="{{ route('admin.tenants.create') }}"><i data-feather="circle"></i><span class="menu-item">Ajouter</span></a></li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="layers"></i>
                    <span class="menu-title text-truncate">Forfaits</span>
                </a>
                <ul class="menu-content">
                    <li id="AdminPlans" data-route="admin.plans.index,admin.plans.create"><a class="d-flex align-items-center" href="{{ route('admin.plans.index') }}"><i data-feather="circle"></i><span class="menu-item">Liste</span></a></li>
                    <li id="AdminPlansCreate" data-route="admin.plans.create"><a class="d-flex align-items-center" href="{{ route('admin.plans.create') }}"><i data-feather="circle"></i><span class="menu-item">Ajouter</span></a></li>
                </ul>
            </li>

        </ul>
    </div>
</div>
<!-- END: Main Menu-->
