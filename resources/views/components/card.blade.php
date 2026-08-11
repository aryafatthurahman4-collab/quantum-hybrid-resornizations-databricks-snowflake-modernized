@props([
    'class' => '',
])

@php
    $classes = 'card ' . $class;
@endphp

<div {{ $attributes->class($classes) }}>
    {{ $slot }}
</div>
