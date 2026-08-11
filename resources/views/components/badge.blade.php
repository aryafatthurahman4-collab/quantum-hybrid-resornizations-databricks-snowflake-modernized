@props([
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'badge-default',
        'secondary' => 'badge-secondary',
        'destructive' => 'badge-destructive',
        'outline' => 'badge-outline',
    ];

    $classes = [
        'badge',
        $variants[$variant] ?? $variants['default'],
    ];
@endphp

<div {{ $attributes->class($classes) }}>
    {{ $slot }}
</div>
