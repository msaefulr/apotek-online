@props([
    'variant' => 'primary', // primary | secondary | danger | warn | dark
    'href' => null,
    'type' => 'button',
])
@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold shadow active:scale-[0.99] transition';
    $variants = [
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
        'secondary' => 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 shadow-none',
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white',
        'warn' => 'bg-amber-500 hover:bg-amber-600 text-white',
        'dark' => 'bg-slate-900 hover:bg-black text-white',
    ];
    $cls = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp
@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>
        {{ $slot }}
    </a>
@else      <button type="{{ $type }}" {{ $attributes->merge(['class' => $cls]) }}>
        {{ $slot }}
      </button>
@endif
