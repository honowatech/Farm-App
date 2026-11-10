{{-- Liste déroulante réutilisable. Usage : <x-form.select name="..." label="..." :options="$array" /> --}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'class' => '',
])

<div class="form-group">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if ($required) required @endif
        class="form-control @error($name) is-invalid @enderror {{ $class }}"
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
