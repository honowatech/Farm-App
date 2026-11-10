{{-- Champ de formulaire réutilisable. Usage : <x-form.input name="email" label="E-mail" type="email" /> --}}
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'class' => '',
])

<div class="form-group">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        class="form-control @error($name) is-invalid @enderror {{ $class }}"
    >

    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
