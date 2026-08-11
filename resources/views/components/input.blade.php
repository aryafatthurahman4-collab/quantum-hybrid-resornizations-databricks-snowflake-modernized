@props([
    'type' => 'text',
    'disabled' => false,
])

@php
    $classes = 'input';
@endphp

<input {{ $attributes->merge(['type' => $type, 'disabled' => $disabled])->class($classes) }}>
