@extends('layouts.app')

@section('title', 'Alertes')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Alertes</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Alertes actives</h4>
                </div>
                <div class="card-body">
                    @php
                        $sevBadges = ['info' => 'info', 'warning' => 'warning', 'danger' => 'danger'];
                    @endphp
                    @forelse($alertes as $alerte)
                        <div class="d-flex align-items-start border-bottom pb-1 mb-1">
                            <span class="badge badge-glow badge-{{ $sevBadges[$alerte['severite']] ?? 'secondary' }} mr-1 mt-50">
                                {{ strtoupper($alerte['severite']) }}
                            </span>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $alerte['titre'] }}</h6>
                                <p class="mb-50">{{ $alerte['message'] }}</p>
                                <a href="{{ $alerte['lien'] }}" class="btn btn-sm btn-primary">Voir</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center">Aucune alerte active. 🎉</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
</script>
@endsection
