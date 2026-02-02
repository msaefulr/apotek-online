@props(['variant' => 'info'])

@php
    $map = [
        'success' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'warning' => 'bg-amber-100 text-amber-800 border-amber-200',
        'danger' => 'bg-rose-100 text-rose-800 border-rose-200',
        'info' => 'bg-slate-100 text-slate-800 border-slate-200',
    ];
@endphp

<span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $map[$variant] ?? $map['info'] }}">
    {{ $slot }}
</span>