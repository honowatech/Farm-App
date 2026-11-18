@extends('layouts.app')

@section('title', 'Rôles et permissions')

@section('css')
<style>
    .roles-matrix { border-collapse: separate; border-spacing: 0; }

    .roles-matrix-wrapper {
        overflow: auto;
        max-height: 70vh;
    }

    .roles-matrix thead th {
        position: sticky;
        top: 0;
        background-color: #fff !important;
        z-index: 3;
        border-bottom: 2px solid #ebe9f1;
        white-space: nowrap;
    }

    /* En-tête de la colonne "Permission" : sticky vertical + horizontal */
    .roles-matrix thead th:first-child {
        left: 0;
        z-index: 6;
        min-width: 240px;
        background-color: #fff !important;
        border-right: 1px solid #ebe9f1;
        box-shadow: 6px 0 10px -4px rgba(34, 41, 47, 0.18);
    }

    /* Première colonne (libellé/clé de permission) : sticky horizontal,
       fond opaque + ombre pour séparer nettement du contenu qui défile */
    .roles-matrix tbody td:first-child {
        position: sticky;
        left: 0;
        z-index: 2;
        min-width: 240px;
        background-color: #fff !important;
        border-right: 1px solid #ebe9f1;
        box-shadow: 6px 0 10px -4px rgba(34, 41, 47, 0.18);
    }

    .roles-matrix .group-row td {
        background-color: #f8f7fb !important;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        font-size: .78rem;
        color: #5e5873;
        padding: .6rem 1rem;
    }

    /* Libellé de groupe : sticky horizontal, fond opaque + ombre */
    .roles-matrix .group-row td:first-child {
        position: sticky;
        left: 0;
        z-index: 2;
        min-width: 240px;
        background-color: #f8f7fb !important;
        border-left: 3px solid #7367f0;
        border-right: 1px solid #ebe9f1;
        box-shadow: 6px 0 10px -4px rgba(34, 41, 47, 0.18);
    }

    .roles-matrix td { vertical-align: middle; }
    .perm-label { font-weight: 500; color: #333; }
    .role-head { text-transform: capitalize; }
    .switch-pending { opacity: .55; pointer-events: none; }
</style>
@endsection

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Rôles et permissions</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 text-right">
                <form class="form-inline justify-content-end" action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-merge">
                        <input type="text" name="libelle" class="form-control form-control-sm" placeholder="Nouveau profil (ex : controleur)" required pattern="[a-z_]+">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success btn-sm"><i data-feather="plus" class="mr-50"></i> Ajouter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-body p-0">
                    <div class="roles-matrix-wrapper">
                        <table class="table table-hover roles-matrix mb-0">
                            <thead>
                                <tr>
                                    <th>Permission</th>
                                    @foreach ($roles as $role)
                                        <th class="text-center role-head" style="min-width: 92px;">
                                            {{ $role->libelle }}
                                            @if (! in_array($role->libelle, ['admin', 'superadmin'], true))
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline role-delete-form" data-name="{{ $role->libelle }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm text-danger p-0 ml-25" title="Supprimer"><i data-feather="trash-2" class="font-medium-2"></i></button>
                                                </form>
                                            @endif
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $group => $permissions)
                                    <tr class="group-row">
                                        <td class="group-label">{{ $group }}</td>
                                        @foreach ($roles as $role)
                                            <td></td>
                                        @endforeach
                                    </tr>
                                    @foreach ($permissions as $name => $meta)
                                        <tr>
                                            <td>
                                                <div class="perm-label">{{ $meta['label'] }}</div>
                                            </td>
                                            @foreach ($roles as $role)
                                                <td class="text-center">
                                                    @if ($role->libelle === 'superadmin')
                                                        <div class="custom-control custom-switch custom-switch-primary">
                                                            <input type="checkbox" class="custom-control-input" checked disabled>
                                                            <label class="custom-control-label"></label>
                                                        </div>
                                                    @else
                                                        <div class="custom-control custom-switch custom-switch-success">
                                                            <input type="checkbox"
                                                                   class="custom-control-input perm-toggle"
                                                                   id="perm-{{ $role->id }}-{{ md5($name) }}"
                                                                   data-url="{{ route('admin.roles.toggle', $role->id) }}"
                                                                   data-permission="{{ $name }}"
                                                                   {{ $role->permissions->contains('name', $name) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm-{{ $role->id }}-{{ md5($name) }}"></label>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        <i data-feather="info" class="mr-50"></i> Basculez un interrupteur pour accorder ou retirer une permission.
                    </span>
                    <span class="text-muted small">Super admin : toutes les permissions</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).on('change', '.perm-toggle', function () {
        var $switch = $(this);
        var url = $switch.data('url');
        var permission = $switch.data('permission');
        var before = $switch.prop('checked');

        $switch.closest('.custom-switch').addClass('switch-pending');

        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: {
                permission: permission,
                _token: token
            },
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).done(function (res) {
            if (res.ok) {
                toastr.success(res.granted ? 'Permission accordée.' : 'Permission retirée.', 'Rôles');
            } else {
                $switch.prop('checked', !before);
                toastr.error(res.message || 'Erreur lors de la mise à jour.', 'Rôles');
            }
        }).fail(function (xhr) {
            $switch.prop('checked', !before);

            var msg = 'Mise à jour impossible. Réessayez.';
            if (xhr.status === 419) {
                msg = 'Session expirée. Rechargez la page puis réessayez.';
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }

            console.error('[roles.toggle] statut ' + xhr.status, xhr.responseText);
            toastr.error(msg, 'Rôles');
        }).always(function () {
            $switch.closest('.custom-switch').removeClass('switch-pending');
        });
    });

    $(document).on('click', '.role-delete-form button', function (e) {
        e.preventDefault();
        var $form = $(this).closest('form');
        var name = $form.data('name');

        Swal.fire({
            title: 'Supprimer ce profil ?',
            text: 'Le profil « ' + name + ' » sera supprimé définitivement.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#ea5455',
            cancelButtonColor: '#82868b',
        }).then(function (result) {
            if (result.isConfirmed) {
                $form.submit();
            }
        });
    });
</script>
@endsection
