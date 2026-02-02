@props([
  'label' => null,
  'name' => null,
  'value' => null,
])

<div>
  @if($label)
    <label class="text-sm font-semibold text-slate-700">{{ $label }}</label>
  @endif

  <select
    name="{{ $name }}"
    {{ $attributes->merge(['class' => 'mt-1 w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500']) }}
  >
    {{ $slot }}
  </select>

  @if($name)
    @error($name) <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
  @endif
</div>
