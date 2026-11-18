@extends('layouts.app')

@section('title', 'Paramètres de la plateforme')

@section('contenu')
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Paramètres de la plateforme</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Configuration générale</h4>
                </div>
                <div class="card-body">
                    <form class="form" action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <div class="row mx-auto col-lg-10">
                            @foreach ($definitions as $key => $meta)
                                @if ($meta['type'] === 'boolean')
                                    <div class="col-12 py-1">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="{{ $key }}" name="{{ $key }}" value="1" {{ $values[$key] === '1' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="{{ $key }}">{{ $meta['label'] }}</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12 col-md-6 py-1">
                                        <label for="{{ $key }}">{{ $meta['label'] }}</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i data-feather="settings"></i></span>
                                            </div>
                                            <input type="{{ $meta['type'] }}" id="{{ $key }}" name="{{ $key }}" class="form-control" value="{{ $values[$key] }}">
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div class="col-12 py-2 text-center">
                                <button type="submit" class="btn btn-success mt-2">
                                    <i data-feather="download" class="mr-50"></i> Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
