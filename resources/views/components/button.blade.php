@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'button',
    'disabled' => false,
    'href' => null,
])

@php
    $variants = [
        'default' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'destructive' => 'btn-destructive',
        'outline' => 'btn-outline',
        'ghost' => 'btn-ghost',
        'link' => 'btn-link',
    ];

    $sizes = [
        'default' => 'btn-md',
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        'icon' => 'h-10 w-10',
    ];

    $classes = [
        'btn',
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['default'],
    ];

    $targetHref = $href ?? $attributes->get('href');
@endphp

@if($targetHref)
<a href="{{ $targetHref }}" {{ $attributes->except(['type', 'href'])->class($classes) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => $type, 'disabled' => $disabled])->class($classes) }}>
    {{ $slot }}
</button>
@endif
