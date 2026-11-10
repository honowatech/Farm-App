{{-- Composant carte réutilisable (Vuexy). Usage : <x-card title="...">contenu</x-card> --}}
@props([
    'title' => null,
    'class' => '',
    'header' => null,
])

<div class="card {{ $class }}">
    @if ($title || $header)
        <div class="card-header">
            @if ($title)
                <h4 class="card-title">{{ $title }}</h4>
            @endif
            {{ $header }}
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
