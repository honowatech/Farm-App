@php
    $features = $plan->features ?? [];
    $limits = $plan->limits ?? [];
    $isEdit = $plan->exists;
@endphp

<style>
    .plan-config-card {
        position: sticky;
        top: 5rem;
        z-index: 5;
    }
    .plan-config-card .card-body {
        max-height: calc(100vh - 8.5rem);
        overflow-y: auto;
    }
    .config-section {
        border: 1px solid #ebe9f1;
        border-radius: .5rem;
        margin-bottom: .75rem;
        overflow: hidden;
    }
    .config-section:last-child {
        margin-bottom: 0;
    }
    .config-section-header {
        background: #f8f8fb;
        padding: .55rem .8rem;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #6e6b7b;
        border-bottom: 1px solid #ebe9f1;
    }
    .config-section-body {
        padding: .25rem .8rem .5rem;
    }
    .config-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .45rem 0;
        border-bottom: 1px solid #f1f1f5;
    }
    .config-item:last-child {
        border-bottom: 0;
    }
    .config-label {
        font-size: .84rem;
        color: #5e5873;
        font-weight: 500;
        line-height: 1.25;
    }
    .config-desc {
        font-size: .72rem;
        color: #b9b9c3;
        margin-top: .1rem;
    }
    .config-input {
        width: 84px;
        text-align: right;
    }
    .config-subgroup {
        margin: .3rem 0 .6rem;
        padding-left: .6rem;
        border-left: 3px solid #7367f0;
    }
    .config-subgroup-title {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #7367f0;
        margin-bottom: .15rem;
    }
</style>

<form class="form" action="{{ $isEdit ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="row">
        {{-- Colonne principale : identité & tarification --}}
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informations du forfait</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Nom <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $plan->name) }}" placeholder="Ex : Starter" required>
                                @error('name')<div class="text-danger"><strong>{{ $message }}</strong></div>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="code">Code <span class="text-danger">*</span></label>
                                <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $plan->code) }}" placeholder="ex : starter" required>
                                @error('code')<div class="text-danger"><strong>{{ $message }}</strong></div>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="price">Prix <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend"><span class="input-group-text"><i data-feather="dollar-sign"></i></span></div>
                                    <input type="number" step="0.01" min="0" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $plan->price ?? 0) }}" required>
                                </div>
                                @error('price')<div class="text-danger"><strong>{{ $message }}</strong></div>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="currency">Devise <span class="text-danger">*</span></label>
                                <input type="text" id="currency" name="currency" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', $plan->currency ?? 'XAF') }}" maxlength="3" required>
                                @error('currency')<div class="text-danger"><strong>{{ $message }}</strong></div>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="billing_interval">Période de facturation <span class="text-danger">*</span></label>
                                <select id="billing_interval" name="billing_interval" class="form-control @error('billing_interval') is-invalid @enderror">
                                    <option value="monthly" {{ old('billing_interval', $plan->billing_interval) === 'monthly' ? 'selected' : '' }}>Mensuel</option>
                                    <option value="annually" {{ old('billing_interval', $plan->billing_interval) === 'annually' ? 'selected' : '' }}>Annuel</option>
                                </select>
                                @error('billing_interval')<div class="text-danger"><strong>{{ $message }}</strong></div>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="trial_days">Jours d'essai</label>
                                <input type="number" min="0" max="365" id="trial_days" name="trial_days" class="form-control @error('trial_days') is-invalid @enderror" value="{{ old('trial_days', $plan->trial_days ?? 0) }}">
                                @error('trial_days')<div class="text-danger"><strong>{{ $message }}</strong></div>@enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Description du forfait">{{ old('description', $plan->description) }}</textarea>
                                @error('description')<div class="text-danger"><strong>{{ $message }}</strong></div>@enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Forfait actif</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panneau latéral sticky : fonctionnalités, quotas, limites --}}
        <div class="col-12 col-lg-4">
            <div class="card plan-config-card">
                <div class="card-header">
                    <h4 class="card-title">Fonctionnalités & quotas</h4>
                </div>
                <div class="card-body">

                    {{-- Fonctionnalités --}}
                    <div class="config-section">
                        <div class="config-section-header">Fonctionnalités</div>
                        <div class="config-section-body">
                            @foreach ($featureCatalog as $key => $meta)
                                @php $checked = $isEdit ? ($features[$key] ?? false) : true; @endphp
                                <div class="config-item">
                                    <div>
                                        <div class="config-label">{{ $meta['label'] }}</div>
                                        @if (! empty($meta['description']))
                                            <div class="config-desc">{{ $meta['description'] }}</div>
                                        @endif
                                    </div>
                                    <div class="custom-control custom-switch ml-1">
                                        <input type="checkbox" class="custom-control-input" id="feature-{{ $key }}" name="features[]" value="{{ $key }}" {{ $checked ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="feature-{{ $key }}"></label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Quotas par profil --}}
                    <div class="config-section">
                        <div class="config-section-header">Quotas par profil</div>
                        <div class="config-section-body">
                            @foreach ($featureCatalog as $featureKey => $featureMeta)
                                @php
                                    $featureProfiles = collect(config('features.profiles', []))->filter(fn ($p) => ($p['feature'] ?? null) === $featureKey);
                                    $featureEnabled = $isEdit ? ($features[$featureKey] ?? false) : true;
                                @endphp
                                @if ($featureProfiles->isNotEmpty())
                                    <div class="config-subgroup profile-quota-group" data-feature="{{ $featureKey }}" @if(!$featureEnabled) style="display:none;" @endif>
                                        <div class="config-subgroup-title">{{ $featureMeta['label'] }}</div>
                                        @foreach ($featureProfiles as $profileKey => $profileMeta)
                                            @php
                                                $profileValue = $isEdit ? ($limits['profiles'][$profileKey] ?? $profileMeta['default']) : ($profileMeta['default'] ?? 0);
                                            @endphp
                                            <div class="config-item">
                                                <label for="quota-{{ $profileKey }}" class="config-label mb-0">{{ $profileMeta['label'] }}</label>
                                                <input type="number" id="quota-{{ $profileKey }}" name="limits[profiles][{{ $profileKey }}]" class="form-control form-control-sm config-input" value="{{ old('limits.profiles.'.$profileKey, $profileValue) }}" min="0" max="10000">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Limites métier --}}
                    <div class="config-section">
                        <div class="config-section-header">Limites métier</div>
                        <div class="config-section-body">
                            @foreach ($limitCatalog as $key => $meta)
                                @php $value = $isEdit ? ($limits[$key] ?? $meta['default']) : ($meta['default'] ?? 0); @endphp
                                <div class="config-item">
                                    <label for="limit-{{ $key }}" class="config-label mb-0">{{ $meta['label'] }}</label>
                                    <input type="number" id="limit-{{ $key }}" name="limits[{{ $key }}]" class="form-control form-control-sm config-input" value="{{ old('limits.'.$key, $value) }}" min="{{ $meta['min'] ?? 0 }}" max="{{ $meta['max'] ?? 100000 }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Boutons --}}
    <div class="row mt-1">
        <div class="col-12 text-center">
            <a href="{{ route('admin.plans.index') }}" class="btn btn-danger mr-2">
                <i data-feather="x" class="mr-50"></i> Annuler
            </a>
            <button type="submit" class="btn btn-success">
                <i data-feather="save" class="mr-50"></i> {{ $isEdit ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
        </div>
    </div>
</form>

<script type="text/javascript">
    document.addEventListener('change', function (e) {
        if (!e.target || !e.target.matches || !e.target.matches('input[name="features[]"]')) {
            return;
        }
        var groups = document.querySelectorAll('.profile-quota-group[data-feature="' + e.target.value + '"]');
        groups.forEach(function (group) {
            group.style.display = e.target.checked ? '' : 'none';
        });
    });
</script>
