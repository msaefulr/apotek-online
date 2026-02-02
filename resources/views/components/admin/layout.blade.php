@props([
    'title' => 'Apotek Online'
])
@php
    $menus = [
        [
            'label' => 'Dashboard',
            'href' => route('dashboard'),
            'active' => 'dashboard*',
            'icon' => '🏠',
        ],
        [
            'label' => 'Jenis Obat',
            'href' => route('jenis-obat.index'),
            'active' => 'jenis-obat*',
            'icon' => '🏷️',
        ],
        [
            'label' => 'Obat',
            'href' => route('obat.index'),
            'active' => 'obat*',
            'icon' => '💊',
        ],
    ];
@endphp
  
  <x-app-layout>
  {{-- Mobile Nav --}}
    <x-admin.mobile-nav :title="$title" :menus="$menus" />
  
  {{-- Desktop Sidebar --}}
    <x-admin.sidebar :title="$title" :menus="$menus" />
  
      {{-- Content area: pushed right when desktop sidebar exists --}}
        <div class="lg:pl-72">
            <div class="min-h-screen bg-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </div>
    </div>
  </div>
</x-app-layout>
