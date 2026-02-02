@php
    $opsi = ['ekonomi', 'reguler', 'same day', 'standar'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
    <div class="md:col-span-6">
        <x-admin.input label="Nama Ekspedisi" name="nama_ekspedisi" :value="$item->nama_ekspedisi ?? ''"
            placeholder="JNE / J&T / SiCepat" required />
    </div>

    <div class="md:col-span-6">
        <x-admin.select label="Jenis Kirim" name="jenis_kirim" required>
            <option value="">-- pilih --</option>
            @foreach($opsi as $o)
                <option value="{{ $o }}" {{ old('jenis_kirim', $item->jenis_kirim ?? '') === $o ? 'selected' : '' }}>{{ $o }}
                </option>
            @endforeach
        </x-admin.select>
    </div>

    <div class="md:col-span-12">
        <x-admin.input label="Logo URL (opsional)" name="logo_ekspedisi" :value="$item->logo_ekspedisi ?? ''"
            placeholder="https://..." />
    </div>
</div>