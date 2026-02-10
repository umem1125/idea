@props(['label', 'name', 'type' => 'text'])

<div class="space-y-2">
    <label for="{{ $name }}" class="label">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" class="input" {{ $attributes }}
        value="{{ old($name) }}" />

    @error($name)
        <p class="text-sm error">{{ $message }}</p>
    @enderror
</div>
