@extends('layouts.app')

@section('title', 'Rôles et permissions')

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
                    <input type="text" name="libelle" class="form-control form-control-sm mr-50" placeholder="ex : controleur" required pattern="[a-z_]+">
                    <button type="submit" class="btn btn-success btn-sm"><i data-feather="plus" class="mr-50"></i> Ajouter</button>
                </form>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-body p-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="min-width: 220px;">Permission</th>
                                    @foreach ($roles as $role)
                                        <th class="text-center align-middle" style="min-width: 90px;">
                                            {{ $role->libelle }}
                                            @if (! in_array($role->libelle, ['admin', 'superadmin'], true))
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer le profil « {{ $role->libelle }} » ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm text-danger p-0 ml-25" title="Supprimer"><i data-feather="x" class="font-medium-2"></i></button>
                                                </form>
                                            @endif
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $group => $permissions)
                                    <tr class="table-active">
                                        <td colspan="{{ $roles->count() + 1 }}"><strong>{{ $group }}</strong></td>
                                    </tr>
                                    @foreach ($permissions as $name => $meta)
                                        <tr>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 300px;" title="{{ $name }}">{{ $meta['label'] }}</span>
                                                <code class="text-muted d-none d-md-inline">{{ $name }}</code>
                                            </td>
                                            @foreach ($roles as $role)
                                                <td class="text-center align-middle">
                                                    @if ($role->libelle === 'superadmin')
                                                        <input type="checkbox" checked disabled>
                                                    @else
                                                        <input type="checkbox" class="perm-toggle" data-role="{{ $role->id }}" data-permission="{{ $name }}" {{ $role->permissions->contains('name', $name) ? 'checked' : '' }}>
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
                <div class="card-footer text-muted small">
                    <i data-feather="info" class="mr-50"></i> Cochez ou décochez une case pour accorder ou retirer une permission. Le super administrateur possède toujours toutes les permissions.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).on('change', '.perm-toggle', function () {
        var $this = $(this);
        var roleId = $this.data('role');
        var permission = $this.data('permission');
        var before = $this.prop('checked');

        $.post('{{ url("/admin/roles") }}/' + roleId + '/toggle-permission', {
            _token: '{{ csrf_token() }}',
            permission: permission
        }).done(function (res) {
            if (res.ok) {
                toastr.success(res.granted ? 'Permission accordée.' : 'Permission retirée.', 'Rôles');
            } else {
                $this.prop('checked', !before);
                toastr.error(res.message || 'Erreur.', 'Rôles');
            }
        }).fail(function () {
            $this.prop('checked', !before);
            toastr.error('Erreur lors de la mise à jour.', 'Rôles');
        });
    });
</script>
@endsection
