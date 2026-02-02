@php $isEdit = isset($obat); @endphp

<div class="grid grid-cols-1 md:grid-cols-12 gap-4">

    <div class="md:col-span-7">
        <x-admin.input label="Nama Obat" name="nama_obat" :value="$obat->nama_obat ?? ''"
            placeholder="Contoh: Paracetamol 500mg" required />
    </div>

    <div class="md:col-span-5">
        <x-admin.select label="Jenis Obat" name="idjenis" required>
            <option value="">-- Pilih Jenis --</option>
            @foreach($jenis as $j)
                <option value="{{ $j->id }}" {{ (string) old('idjenis', $obat->idjenis ?? '') === (string) $j->id ? 'selected' : '' }}>
                    {{ $j->jenis }}
                </option>
            @endforeach
        </x-admin.select>
    </div>

    <div class="md:col-span-4">
        <x-admin.input label="Harga Jual" name="harga_jual" type="number" min="0" :value="$obat->harga_jual ?? ''"
            placeholder="5000" required />
    </div>

    <div class="md:col-span-4">
        <x-admin.input label="Stok" name="stok" type="number" min="0" :value="$obat->stok ?? ''" placeholder="0"
            required />
    </div>

    <div class="md:col-span-4">
        <x-admin.input label="Foto URL (opsional)" name="foto1" :value="$obat->foto1 ?? ''" placeholder="https://..." />
    </div>

    <div class="md:col-span-12">
        <x-admin.textarea label="Deskripsi" name="deskripsi_obat" :value="$obat->deskripsi_obat ?? ''" rows="4"
            placeholder="Catatan tentang obat..." />
    </div>

    @if(old('foto1', $obat->foto1 ?? null))
        <div class="md:col-span-12">
            <x-admin.card class="p-4 bg-slate-50">
                <div class="text-sm font-semibold text-slate-700 mb-3">Preview Foto</div>
                <img src="{{ old('foto1', $obat->foto1 ?? '') }}"
                    class="h-40 w-40 object-cover rounded-xl border border-slate-200" onerror="this.style.display='none'">
                <div class="text-xs text-slate-500 mt-2">Kalau URL salah, preview bakal hilang.</div>
            </x-admin.card>
        </div>
    @endif
</div>