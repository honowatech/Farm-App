@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Utilisateurs</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    {{-- Barre de filtres (recherche AJAX, sans bouton de soumission) --}}
                    <div class="filters-bar mb-1">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-12 form-group mb-lg-0">
                                <label class="text-muted small mb-50" for="filter-nom">Nom</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather="search" class="text-muted" style="height:14px;width:14px;"></i></span>
                                    </div>
                                    <input type="text" id="filter-nom" class="form-control filter-input" placeholder="Nom ou email…" autocomplete="off" value="{{ request('nom') }}">
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-12 form-group mb-lg-0">
                                <label class="text-muted small mb-50" for="filter-type">Profil</label>
                                <select id="filter-type" class="form-control filter-input">
                                    <option value="">Tous les profils</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ ucfirst($type->libelle) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-6 col-12 form-group mb-md-0">
                                <label class="text-muted small mb-50" for="filter-statut">Statut</label>
                                <select id="filter-statut" class="form-control filter-input">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" {{ request('statut') === '1' ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ request('statut') === '0' ? 'selected' : '' }}>Désactivé</option>
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-6 col-12 form-group mb-0">
                                <label class="text-muted small mb-50" for="filter-entreprise">Entreprise</label>
                                <select id="filter-entreprise" class="form-control filter-input">
                                    <option value="">Toutes les entreprises</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ request('entreprise') == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-50" id="filter-reset-row" @if(!request()->hasAny(['nom', 'type', 'statut', 'entreprise'])) style="display:none;" @endif>
                            <div class="col-12">
                                <a href="#" id="btn-reset" class="btn btn-sm btn-outline-secondary"><i data-feather="x" class="mr-50" style="height:14px;width:14px;"></i> Réinitialiser</a>
                            </div>
                        </div>
                    </div>

                    {{-- Tableau chargé en AJAX, avec loader --}}
                    <div class="position-relative">
                        <div id="users-table-loader" style="display:none; position:absolute; inset:0; background:rgba(255,255,255,.75); z-index:20; border-radius:.358rem;">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <span class="spinner-border text-primary" role="status" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div id="users-table">
                            @include('superadmin.pages.users._table', compact('users', 'sort', 'direction'))
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    var currentSort = '{{ $sort }}';
    var currentDirection = '{{ $direction }}';
    var usersAjaxUrl = '{{ route('admin.users.ajax') }}';

    function debounce(fn, wait) {
        var timer;
        return function () {
            var ctx = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () { fn.apply(ctx, args); }, wait);
        };
    }

    function loadUsers(page) {
        var params = {
            nom: $('#filter-nom').val(),
            type: $('#filter-type').val(),
            statut: $('#filter-statut').val(),
            entreprise: $('#filter-entreprise').val(),
            sort: currentSort,
            direction: currentDirection,
            page: page || 1
        };

        $('#users-table-loader').fadeIn(150);

        $.ajax({
            url: usersAjaxUrl,
            method: 'GET',
            data: params,
            success: function (html) {
                $('#users-table').html(html);
                if (window.feather) {
                    feather.replace({ width: 14, height: 14 });
                }
                toggleResetVisibility();
            },
            error: function () {
                toastr.error("Impossible de charger les utilisateurs.", "Erreur");
            },
            complete: function () {
                $('#users-table-loader').fadeOut(150);
            }
        });
    }

    function toggleResetVisibility() {
        var hasFilters = $('#filter-nom').val()
            || $('#filter-type').val()
            || $('#filter-statut').val()
            || $('#filter-entreprise').val();
        $('#filter-reset-row').toggle(!!hasFilters);
    }

    var triggerLoad = debounce(function () { loadUsers(1); }, 300);

    $(function () {
        $('.filter-input').on('input change', function () {
            triggerLoad();
        });

        // Tri sur les colonnes
        $(document).on('click', '.sort-link', function (e) {
            e.preventDefault();
            var col = $(this).data('sort');
            if (currentSort === col) {
                currentDirection = (currentDirection === 'asc') ? 'desc' : 'asc';
            } else {
                currentSort = col;
                currentDirection = 'asc';
            }
            loadUsers(1);
        });

        // Pagination en AJAX (reste sur la page)
        $(document).on('click', '#users-table .pagination a', function (e) {
            e.preventDefault();
            var match = $(this).attr('href').match(/page=(\d+)/);
            loadUsers(match ? match[1] : 1);
        });

        // Réinitialisation des filtres
        $('#btn-reset').on('click', function (e) {
            e.preventDefault();
            $('#filter-nom').val('');
            $('#filter-type').val('');
            $('#filter-statut').val('');
            $('#filter-entreprise').val('');
            currentSort = 'name';
            currentDirection = 'asc';
            loadUsers(1);
        });
    });
</script>
@endsection
