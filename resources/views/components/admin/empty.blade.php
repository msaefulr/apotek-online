@props([
    'title' => 'Belum ada data',
    'subtitle' => 'Tambahkan data untuk mulai.',
    'actionText' => null,
    'actionHref' => null,
])
  
<div class="px-6 py-14 text-center">
  <div   class="text-slate-900 font-bold text-lg">{{ $title }}</div>
  <div class="text-slate-500 text-sm mt-1 mb-4">{{ $subtitle }}</div>
 
   @if($actionText && $actionHref)
    <x-admin.button :href="$actionHref">
      {{ $actionText }}
    </x-admin.button>
  @endif
</div>
