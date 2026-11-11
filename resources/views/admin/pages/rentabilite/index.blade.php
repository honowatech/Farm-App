@extends('layouts.app')

@section('title', 'Rentabilité par bande')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Rentabilité par bande</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row match-height">
                @forelse($resultats as $r)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ $r['bande']->nom }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6"><p class="text-muted mb-0">Revenus</p><strong>{{ number_format($r['revenus'], 0, ',', ' ') }} FCFA</strong></div>
                                    <div class="col-6"><p class="text-muted mb-0">Coûts</p><strong>{{ number_format($r['total_couts'], 0, ',', ' ') }} FCFA</strong></div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <p class="text-muted mb-0">Marge</p>
                                    <h3 class="{{ $r['marge'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($r['marge'], 0, ',', ' ') }} FCFA
                                    </h3>
                                </div>
                                <div class="small text-muted">
                                    Poussins : {{ number_format($r['poussins'], 0, ',', ' ') }} ·
                                    Aliment : {{ number_format($r['aliment'], 0, ',', ' ') }} ·
                                    Vaccins : {{ number_format($r['vaccins'], 0, ',', ' ') }} ·
                                    Autres : {{ number_format($r['autres_couts'], 0, ',', ' ') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">Aucune bande.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
</script>
@endsection
