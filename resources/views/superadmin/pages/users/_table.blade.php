<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>
                    <a href="#" data-sort="name" class="d-inline-flex align-items-center text-body sort-link">
                        Nom
                        @if($sort === 'name')<i data-feather="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width:14px;height:14px;" class="ml-25"></i>@endif
                    </a>
                </th>
                <th>Email</th>
                <th>
                    <a href="#" data-sort="type" class="d-inline-flex align-items-center text-body sort-link">
                        Profil global
                        @if($sort === 'type')<i data-feather="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width:14px;height:14px;" class="ml-25"></i>@endif
                    </a>
                </th>
                <th>
                    <a href="#" data-sort="entreprise" class="d-inline-flex align-items-center text-body sort-link">
                        Entreprises
                        @if($sort === 'entreprise')<i data-feather="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width:14px;height:14px;" class="ml-25"></i>@endif
                    </a>
                </th>
                <th>
                    <a href="#" data-sort="statut" class="d-inline-flex align-items-center text-body sort-link">
                        Statut
                        @if($sort === 'statut')<i data-feather="{{ $direction === 'asc' ? 'chevron-up' : 'chevron-down' }}" style="width:14px;height:14px;" class="ml-25"></i>@endif
                    </a>
                </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->type_user)
                            <span class="badge badge-glow badge-primary">{{ $user->type_user->libelle }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @forelse ($user->tenants as $tenant)
                            <span class="badge badge-light-info mr-50">{{ $tenant->name }} ({{ $tenant->pivot->role_key }})</span>
                        @empty
                            <span class="text-muted">—</span>
                        @endforelse
                    </td>
                    <td>
                        <span class="badge badge-glow badge-{{ $user->statut ? 'success' : 'danger' }}">
                            {{ $user->statut ? 'Actif' : 'Désactivé' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-{{ $user->statut ? 'danger' : 'success' }}">
                                {{ $user->statut ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Aucun utilisateur ne correspond aux filtres.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($users->hasPages())
<div class="px-1">
    {{ $users->links() }}
</div>
@endif
