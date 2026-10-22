@props(['user', 'size' => 'md'])

@php
    $gradients = [
        'from-tuwaiq-purple to-tuwaiq-teal',
        'from-tuwaiq-orange to-pink-500',
        'from-tuwaiq-teal to-sky-500',
        'from-pink-500 to-tuwaiq-purple',
    ];
    $sizes = [
        'sm' => 'h-9 w-9 text-sm',
        'md' => 'h-10 w-10 text-base',
        'lg' => 'h-24 w-24 text-4xl',
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex shrink-0 items-center justify-center rounded-full bg-gradient-to-br font-bold text-white uppercase shadow-lg ring-2 ring-white/10',
    $gradients[$user->id % count($gradients)],
    $sizes[$size],
]) }} aria-hidden="true">{{ mb_substr($user->name, 0, 1) }}</span>
