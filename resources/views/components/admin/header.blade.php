@props([
  'title',
  'subtitle' => null,
])

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
  <div>
    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $title }}</h1>
    @if($subtitle)
      <p class="text-sm text-slate-600 mt-1">{{ $subtitle }}</p>
    @endif
  </div>

  <div class="flex items-center gap-2">
    {{ $actions ?? '' }}
  </div>
</div>
