@extends('layouts.app')

@section('title', 'Permissions du profil')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Permissions — {{ $typeUser->libelle }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            @forelse ($permissions as $group => $items)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $group }}</h4>
                    </div>
                    <div class="card-body">
                        @foreach ($items as $permission)
                            <span class="badge badge-light-primary mr-50 mb-50">{{ $permission->name }}</span>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="card"><div class="card-body text-center text-muted">Aucune permission.</div></div>
            @endforelse
        </div>
    </div>
</div>
@endsection
