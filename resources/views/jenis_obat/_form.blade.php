<div class="grid grid-cols-1 md:grid-cols-12 gap-4">

    <div class="md:col-span-6">
        <x-admin.input label="Nama Jenis" name="jenis" :value="$item->jenis ?? ''"
            placeholder="Contoh: Tablet, Sirup, Vitamin" required />
    </div>

    <div class="md:col-span-6">
        <x-admin.input label="URL Gambar (opsional)" name="image_url" :value="$item->image_url ?? ''"
            placeholder="https://..." />
    </div>

    <div class="md:col-span-12">
        <x-admin.textarea label="Deskripsi (opsional)" name="deskripsi_jenis" :value="$item->deskripsi_jenis ?? ''"
            rows="4" placeholder="Keterangan singkat tentang jenis obat..." />
    </div>

    @if(old('image_url', $item->image_url ?? null))
        <div class="md:col-span-12">
            <x-admin.card class="p-4 bg-slate-50">
                <div class="text-sm font-semibold text-slate-700 mb-3">Preview Gambar</div>
                <img src="{{ old('image_url', $item->image_url ?? '') }}"
                    class="h-40 w-40 object-cover rounded-xl border border-slate-200" onerror="this.style.display='none'">
                <div class="text-xs text-slate-500 mt-2">Kalau URL salah, preview bakal hilang.</div>
            </x-admin.card>
        </div>
    @endif

</div>