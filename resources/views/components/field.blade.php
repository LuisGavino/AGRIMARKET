@props(['label', 'name' => null, 'type' => 'text', 'placeholder' => '', 'value' => null, 'disabled' => false, 'password' => false])
{{-- Labelled input used by the settings and seller forms. $password adds the show/hide eye. --}}
<div @class(['field', 'field--password' => $password])>
    <label for="{{ $name }}">{{ $label }}</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $password ? 'password' : $type }}"
        placeholder="{{ $placeholder }}"
        @if (!is_null($value)) value="{{ $value }}" @endif
        @disabled($disabled)
    >
    @if ($password)
        <button type="button" data-toggle-password aria-label="Show or hide password"><x-icon name="eye-off" :size="20" /></button>
    @endif
</div>
