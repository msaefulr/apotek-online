@props([
    'title' => 'Apotek Online',
    'menus' => [],
])
@php
    // active checker: based on request path prefix
    $isActive = function ($pattern) {
        return request()->is($pattern) ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100';
    };
@endphp
    
   <aside    class="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 lg:bg-white lg:border-r lg:border-slate-200">
     <d iv class="h-16 flex items-center px-6 border-b border-slate-200">
      <div class="font-extrabold text-slate-900 text-lg tracking-tight">
      {{ $title }}
      </div>
  </    div>
 
     <nav cl ass="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
     @foreach($menus as $m)
             <a  href="{{ $m['href'] }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition {{ $isActive($m['active']) }}">
                <span class="text-lg leading-none">{{ $m['icon'] ?? '•' }}</span>
              <span>{{ $m['label'] }}</span>
          </a>
      @endforeach
      </nav>

            <div   class="p-4 border-t border-slate-200">
        <div class="text-xs text-slate-500">
        Logged in as
      <div class="font-semibold text-slate-800 truncate">{{ auth()->user()->name ?? 'User' }}</div>
    </div>
  </div>
</aside>
