@props([
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'alert-default',
        'destructive' => 'alert-destructive',
    ];

    $classes = [
        'alert',
        $variants[$variant] ?? $variants['default'],
    ];
@endphp

<div {{ $attributes->class($classes) }}>
    {{ $slot }}
</div>
