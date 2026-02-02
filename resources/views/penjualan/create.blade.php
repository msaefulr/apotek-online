<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Tambah Penjualan" subtitle="Input transaksi penjualan. Stok akan otomatis berkurang.">
            <x-slot:actions>
                <a href="{{ route('penjualan.index') }}"
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

        <form method="POST" action="{{ route('penjualan.store') }}" class="space-y-6">
            @csrf

            <x-admin.card class="p-5 sm:p-6">
                <div class="text-base font-bold text-slate-900">Info Penjualan</div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-4">
                    <div class="md:col-span-4">
                        <x-admin.input label="Tanggal Penjualan" type="date" name="tgl_penjualan"
                            :value="old('tgl_penjualan', now()->toDateString())" required />
                    </div>

                    <div class="md:col-span-4">
                        <x-admin.select label="Pelanggan" name="id_pelanggan" required>
                            <option value="">-- pilih pelanggan --</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}" {{ old('id_pelanggan') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_pelanggan }} ({{ $p->no_telp ?? '-' }})
                                </option>
                            @endforeach
                        </x-admin.select>
                    </div>

                    <div class="md:col-span-4">
                        <x-admin.select label="Metode Bayar" name="id_metode_bayar" required>
                            <option value="">-- pilih metode --</option>
                            @foreach($metodes as $m)
                                <option value="{{ $m->id }}" {{ old('id_metode_bayar') == $m->id ? 'selected' : '' }}>
                                    {{ $m->metode_pembayaran }}
                                </option>
                            @endforeach
                        </x-admin.select>
                    </div>

                    <div class="md:col-span-4">
                        <x-admin.select label="Jenis Pengiriman" name="id_jenis_kirim" required>
                            <option value="">-- pilih pengiriman --</option>
                            @foreach($jenisKirim as $jk)
                                <option value="{{ $jk->id }}" {{ old('id_jenis_kirim') == $jk->id ? 'selected' : '' }}>
                                    {{ $jk->nama_ekspedisi }} - {{ $jk->jenis_kirim }}
                                </option>
                            @endforeach
                        </x-admin.select>
                    </div>

                    <div class="md:col-span-4">
                        <x-admin.input label="Ongkos Kirim" name="ongkos_kirim" type="number" min="0" step="0.01"
                            :value="old('ongkos_kirim', 0)" />
                    </div>

                    <div class="md:col-span-4">
                        <x-admin.input label="Biaya App" name="biaya_app" type="number" min="0" step="0.01"
                            :value="old('biaya_app', 0)" />
                    </div>

                    <div class="md:col-span-6">
                        <x-admin.input label="URL Resep (opsional)" name="url_resep" :value="old('url_resep')"
                            placeholder="https://..." />
                    </div>

                    <div class="md:col-span-3">
                        <x-admin.select label="Status Order" name="status_order" required>
                            @php
                                $status = ['Menunggu Konfirmasi', 'Diproses', 'Menunggu Kurir', 'Dibatalkan Pembeli', 'Dibatalkan Penjual', 'Bermasalah', 'Selesai'];
                              @endphp
                            @foreach($status as $s)
                                <option value="{{ $s }}" {{ old('status_order', 'Menunggu Konfirmasi') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </x-admin.select>
                    </div>

                    <div class="md:col-span-3">
                        <x-admin.input label="Keterangan (opsional)" name="keterangan_status"
                            :value="old('keterangan_status')" placeholder="catatan..." />
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card class="p-5 sm:p-6">
                <div class="text-base font-bold text-slate-900">Detail Item</div>
                <div class="text-sm text-slate-600">Minimal 1 item. Stok dicek sebelum simpan.</div>

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
                                <x-admin.input label="Harga" name="items[{{ $i }}][harga_beli]" type="number" min="0"
                                    step="0.01" :value="old("items.$i.harga_beli")" placeholder="0" />
                                @error("items.$i.harga_beli") <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-xs text-slate-500 mt-2">Isi baris ini kalau ada item. Kalau kosong, biarin.</div>
                    </div>
                @endfor

                <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
                    <x-admin.button variant="secondary" :href="route('penjualan.index')">Batal</x-admin.button>
                    <x-admin.button type="submit">Simpan Penjualan</x-admin.button>
                </div>
            </x-admin.card>

        </form>

    </x-admin.page>
</x-app-layout>