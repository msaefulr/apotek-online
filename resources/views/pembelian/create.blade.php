<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Tambah Pembelian"
            subtitle="Input pembelian + detail item. Stok akan otomatis bertambah.">
            <x-slot:actions>
                <a href="{{ route('pembelian.index') }}"
                    class="text-sm font-semibold text-slate-700 hover:text-slate-900 underline">Kembali</a>
            </x-slot:actions>
        </x-admin.header>

        @if($errors->any())
            <div class="mb-5">
                <x-admin.alert type="danger">
                    <div class="font-bold mb-1">Ada yang perlu dibenerin:</div>
                    <ul class="list-disc ml-5 text-sm">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </x-admin.alert>
            </div>
        @endif

        <form method="POST" action="{{ route('pembelian.store') }}" class="space-y-6">
            @csrf

            <x-admin.card class="p-5 sm:p-6">
                <div class="text-base font-bold text-slate-900">Info Pembelian</div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-4">
                    <div class="md:col-span-4">
                        <x-admin.input label="No Nota" name="nota" :value="old('nota')" placeholder="INV-001"
                            required />
                    </div>

                    <div class="md:col-span-4">
                        <x-admin.input label="Tanggal Pembelian" type="date" name="tgl_pembelian"
                            :value="old('tgl_pembelian', now()->toDateString())" required />
                    </div>

                    <div class="md:col-span-4">
                        <x-admin.select label="Distributor" name="id_distributor" required>
                            <option value="">-- pilih distributor --</option>
                            @foreach($distributors as $d)
                                <option value="{{ $d->id }}" {{ old('id_distributor') == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama_distributor }}
                                </option>
                            @endforeach
                        </x-admin.select>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card class="p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-base font-bold text-slate-900">Detail Item</div>
                        <div class="text-sm text-slate-600">Minimal 1 item. Subtotal & total dihitung otomatis saat
                            simpan.</div>
                    </div>
                    <div class="text-xs text-slate-500">
                        Tip: kalau mau “tambah baris” beneran, next step gue bikinin versi JS dinamis.
                    </div>
                </div>

                {{-- 5 baris input siap pakai (tanpa JS) --}}
                @for($i = 0; $i < 5; $i++)
                    <div class="mt-5 border border-slate-200 rounded-2xl p-4 bg-slate-50">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-6">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Obat (baris
                                    {{ $i + 1 }})</label>
                                <select name="items[{{ $i }}][id_obat]"
                                    class="w-full rounded-xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">
                                    <option value="">-- pilih obat --</option>
                                    @foreach($obats as $o)
                                        <option value="{{ $o->id }}" {{ old("items.$i.id_obat") == $o->id ? 'selected' : '' }}>
                                            {{ $o->nama_obat }} (stok: {{ $o->stok }})
                                        </option>
                                    @endforeach
                                </select>
                                @error("items.$i.id_obat") <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="md:col-span-3">
                                <x-admin.input label="Jumlah" name="items[{{ $i }}][jumlah_beli]" type="number" min="1"
                                    :value="old("items.$i.jumlah_beli")" placeholder="0" />
                                @error("items.$i.jumlah_beli") <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="md:col-span-3">
                                <x-admin.input label="Harga Beli" name="items[{{ $i }}][harga_beli]" type="number" min="0"
                                    step="0.01" :value="old("items.$i.harga_beli")" placeholder="0" />
                                @error("items.$i.harga_beli") <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-xs text-slate-500 mt-2">
                            Isi baris ini kalau ada item. Kalau kosong, biarin aja.
                        </div>
                    </div>
                @endfor

                <div class="mt-5 flex justify-end gap-3">
                    <x-admin.button variant="secondary" :href="route('pembelian.index')">Batal</x-admin.button>
                    <x-admin.button type="submit">Simpan Pembelian</x-admin.button>
                </div>
            </x-admin.card>
        </form>

    </x-admin.page>
</x-app-layout>