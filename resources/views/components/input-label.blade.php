@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm form-label']) }}>
    {{ $value ?? $slot }}
</label>
