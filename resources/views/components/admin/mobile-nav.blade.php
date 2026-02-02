@props([
  'title' => 'Apotek Online',
  'menus' => [],
])

@php
  $isActive = function ($pattern) {
      return request()->is($pattern) ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100';
  };
@endphp

<div x-data="{ open: false }" class="lg:hidden">
  {{-- Topbar --}}
  <div class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4">
    <button @click="open = true" class="p-2 rounded-lg hover:bg-slate-100">
      ☰
    </button>
    <div class="font-extrabold text-slate-900">{{ $title }}</div>
    <div class="w-8"></div>
  </div>

  {{-- Drawer --}}
  <div x-show="open" class="fixed inset-0 z-50" style="display:none;">
    <div class="absolute inset-0 bg-black/40" @click="open=false"></div>

    <div class="absolute left-0 top-0 bottom-0 w-72 bg-white border-r border-slate-200 shadow-xl p-4">
      <div class="flex items-center justify-between mb-4">
        <div class="font-extrabold text-slate-900 text-lg">{{ $title }}</div>
        <button @click="open=false" class="p-2 rounded-lg hover:bg-slate-100">✕</button>
      </div>

      <nav class="space-y-1">
        @foreach($menus as $m)
          <a href="{{ $m['href'] }}"
             class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition {{ $isActive($m['active']) }}">
            <span class="text-lg leading-none">{{ $m['icon'] ?? '•' }}</span>
            <span>{{ $m['label'] }}</span>
          </a>
        @endforeach
      </nav>

      <div class="mt-6 pt-4 border-t border-slate-200 text-xs text-slate-500">
        Logged in as
        <div class="font-semibold text-slate-800 truncate">{{ auth()->user()->name ?? 'User' }}</div>
      </div>
    </div>
  </div>
</div>
