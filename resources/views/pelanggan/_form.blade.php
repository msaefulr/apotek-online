@php
    $ktp = old('url_ktp', $item->url_ktp ?? null);
    $foto = old('url_foto', $item->url_foto ?? null);
@endphp

<div class="space-y-6">

    <x-admin.card class="p-5 sm:p-6">
        <div class="text-base font-bold text-slate-900">Informasi Dasar</div>
        <div class="text-sm text-slate-600 mb-4">Data utama pelanggan untuk transaksi & pengiriman.</div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-6">
                <x-admin.input label="Nama Pelanggan" name="nama_pelanggan" :value="old('nama_pelanggan', $item->nama_pelanggan ?? '')" required />
            </div>

            <div class="md:col-span-6">
                <x-admin.input label="Email (opsional)" name="email" type="email" :value="old('email', $item->email ?? '')" placeholder="email@domain.com" />
            </div>

            <div class="md:col-span-6">
                <x-admin.input label="No Telepon (opsional)" name="no_telp" :value="old('no_telp', $item->no_telp ?? '')" placeholder="08xxxxxxxxxx" />
            </div>

            <div class="md:col-span-6">
                <x-admin.input label="Kata Kunci (opsional)" name="katakunci" :value="old('katakunci', $item->katakunci ?? '')" placeholder="maks 15 karakter" />
                <div class="text-xs text-slate-500 mt-1">Kalau kamu pakai kolom ini buat PIN / kode, keep pendek &
                    simple.</div>
            </div>
        </div>
    </x-admin.card>


    <x-admin.card class="p-5 sm:p-6">
        <div class="text-base font-bold text-slate-900">Alamat 1 (Utama)</div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-4">
            <div class="md:col-span-12">
                <x-admin.textarea label="Alamat" name="alamat1" rows="3" :value="old('alamat1', $item->alamat1 ?? '')"
                    placeholder="Jalan, nomor rumah, RT/RW..." />
            </div>
            <div class="md:col-span-4">
                <x-admin.input label="Kota" name="kota1" :value="old('kota1', $item->kota1 ?? '')" />
            </div>
            <div class="md:col-span-4">
                <x-admin.input label="Provinsi" name="provinsi1" :value="old('provinsi1', $item->provinsi1 ?? '')" />
            </div>
            <div class="md:col-span-4">
                <x-admin.input label="Kodepos" name="kodepos1" :value="old('kodepos1', $item->kodepos1 ?? '')" />
            </div>
        </div>
    </x-admin.card>


    <x-admin.card class="p-5 sm:p-6">
        <div class="text-base font-bold text-slate-900">Alamat 2 (Opsional)</div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-4">
            <div class="md:col-span-12">
                <x-admin.textarea label="Alamat" name="alamat2" rows="3" :value="old('alamat2', $item->alamat2 ?? '')" />
            </div>
            <div class="md:col-span-4">
                <x-admin.input label="Kota" name="kota2" :value="old('kota2', $item->kota2 ?? '')" />
            </div>
            <div class="md:col-span-4">
                <x-admin.input label="Provinsi" name="provinsi2" :value="old('provinsi2', $item->provinsi2 ?? '')" />
            </div>
            <div class="md:col-span-4">
                <x-admin.input label="Kodepos" name="kodepos2" :value="old('kodepos2', $item->kodepos2 ?? '')" />
            </div>
        </div>
    </x-admin.card>


    <x-admin.card class="p-5 sm:p-6">
        <div class="text-base font-bold text-slate-900">Alamat 3 (Opsional)</div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-4">
            <div class="md:col-span-12">
                <x-admin.textarea label="Alamat" name="alamat3" rows="3" :value="old('alamat3', $item->alamat3 ?? '')" />
            </div>
            <div class="md:col-span-4">
                <x-admin.input label="Kota" name="kota3" :value="old('kota3', $item->kota3 ?? '')" />
            </div>
            <div class="md:col-span-4">
                <x-admin.input label="Provinsi" name="provinsi3" :value="old('provinsi3', $item->provinsi3 ?? '')" />
            </div>
            <div class="md:col-span-4">
                <x-admin.input label="Kodepos" name="kodepos3" :value="old('kodepos3', $item->kodepos3 ?? '')" />
            </div>
        </div>
    </x-admin.card>


    <x-admin.card class="p-5 sm:p-6">
        <div class="text-base font-bold text-slate-900">Dokumen (URL)</div>
        <div class="text-sm text-slate-600 mb-4">Sesuai PDM: url_ktp & url_foto.</div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-6">
                <x-admin.input label="URL KTP" name="url_ktp" :value="old('url_ktp', $item->url_ktp ?? '')"
                    placeholder="https://..." />
            </div>
            <div class="md:col-span-6">
                <x-admin.input label="URL Foto" name="url_foto" :value="old('url_foto', $item->url_foto ?? '')"
                    placeholder="https://..." />
            </div>

            <div class="md:col-span-6">
                <x-admin.card class="p-4 bg-slate-50">
                    <div class="text-sm font-semibold text-slate-700 mb-2">Preview KTP</div>
                    @if($ktp)
                        <img src="{{ $ktp }}"
                            class="h-44 w-full object-contain bg-white border border-slate-200 rounded-xl p-2"
                            onerror="this.style.display='none'">
                        <a href="{{ $ktp }}" target="_blank" class="text-indigo-600 underline text-sm">Buka KTP</a>
                    @else
                        <div class="text-sm text-slate-500">Belum ada URL.</div>
                    @endif
                </x-admin.card>
            </div>

            <div class="md:col-span-6">
                <x-admin.card class="p-4 bg-slate-50">
                    <div class="text-sm font-semibold text-slate-700 mb-2">Preview Foto</div>
                    @if($foto)
                        <img src="{{ $foto }}"
                            class="h-44 w-full object-contain bg-white border border-slate-200 rounded-xl p-2"
                            onerror="this.style.display='none'">
                        <a href="{{ $foto }}" target="_blank" class="text-indigo-600 underline text-sm">Buka Foto</a>
                    @else
                        <div class="text-sm text-slate-500">Belum ada URL.</div>
                    @endif
                </x-admin.card>
            </div>
        </div>
    </x-admin.card>

</div>