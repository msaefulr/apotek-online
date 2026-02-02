<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Keranjang" subtitle="Kelola keranjang pelanggan & checkout jadi penjualan.">
            <x-slot:actions>
                <div class="text-xs text-slate-500">
                    Checkout → otomatis bikin Penjualan + Pengiriman ✅
                </div>
            </x-slot:actions>
        </x-admin.header>

        @if(session('success'))
            <div class="mb-5"><x-admin.alert type="success">{{ session('success') }}</x-admin.alert></div>
        @endif
        @if(session('error'))
            <div class="mb-5"><x-admin.alert type="danger">{{ session('error') }}</x-admin.alert></div>
        @endif

        {{-- Filter + Add Item --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            <x-admin.card class="lg:col-span-7 p-5 sm:p-6">
                <div class="font-extrabold text-slate-900">Filter</div>
                <form method="GET" action="{{ route('keranjang.index') }}"
                    class="grid grid-cols-1 md:grid-cols-12 gap-3 mt-4">
                    <div class="md:col-span-6">
                        <x-admin.select label="Pelanggan" name="id_pelanggan">
                            <option value="">-- pilih pelanggan --</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}" {{ (string) $pelangganId === (string) $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_pelanggan }} ({{ $p->no_telp ?? '-' }})
                                </option>
                            @endforeach
                        </x-admin.select>
                    </div>

                    <div class="md:col-span-5">
                        <x-admin.input label="Cari" name="q" :value="$q ?? ''"
                            placeholder="Cari nama obat / pelanggan..." />
                    </div>

                    <div class="md:col-span-1 flex items-end">
                        <x-admin.button variant="dark" type="submit" class="w-full">Go</x-admin.button>
                    </div>

                    <div class="md:col-span-12">
                        <a href="{{ route('keranjang.index') }}"
                            class="text-sm text-slate-500 hover:text-slate-800 underline">Reset</a>
                    </div>
                </form>
            </x-admin.card>

            <x-admin.card class="lg:col-span-5 p-5 sm:p-6">
                <div class="font-extrabold text-slate-900">Tambah Item</div>
                <div class="text-sm text-slate-600">Pilih pelanggan dulu biar rapi.</div>

                <form method="POST" action="{{ route('keranjang.store') }}" class="mt-4 space-y-3">
                    @csrf

                    <x-admin.select label="Pelanggan" name="id_pelanggan" required>
                        <option value="">-- pilih pelanggan --</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id }}" {{ old('id_pelanggan', $pelangganId) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pelanggan }}
                            </option>
                        @endforeach
                    </x-admin.select>

                    <x-admin.select label="Obat" name="id_obat" required>
                        <option value="">-- pilih obat --</option>
                        @foreach($obats as $o)
                            <option value="{{ $o->id }}">
                                {{ $o->nama_obat }} (stok: {{ $o->stok }}) - Rp
                                {{ number_format((float) $o->harga_jual, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </x-admin.select>

                    <x-admin.input label="Jumlah" name="jumlah_order" type="number" min="1" step="1"
                        :value="old('jumlah_order', 1)" required />

                    <div class="flex justify-end pt-2">
                        <x-admin.button type="submit">+ Tambah</x-admin.button>
                    </div>
                </form>
            </x-admin.card>
        </div>

        {{-- Summary + Checkout --}}
        <x-admin.card class="p-5 sm:p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <div class="text-sm text-slate-500">Subtotal (keranjang terpilih)</div>
                    <div class="text-2xl font-extrabold text-slate-900">
                        Rp {{ number_format((float) $total, 0, ',', '.') }}
                    </div>
                </div>

                <form method="POST" action="{{ route('keranjang.checkout') }}"
                    class="grid grid-cols-1 md:grid-cols-12 gap-3 w-full md:w-auto">
                    @csrf
                    <input type="hidden" name="id_pelanggan" value="{{ $pelangganId }}">

                    <div class="md:col-span-4">
                        <x-admin.input label="Ongkir" name="ongkos_kirim" type="number" min="0" step="0.01" value="0" />
                    </div>
                    <div class="md:col-span-4">
                        <x-admin.input label="Biaya App" name="biaya_app" type="number" min="0" step="0.01" value="0" />
                    </div>
                    <div class="md:col-span-4">
                        <x-admin.input label="Metode Bayar ID" name="id_metode_bayar" type="number" min="1"
                            placeholder="contoh: 1" required />
                    </div>
                    <div class="md:col-span-4">
                        <x-admin.input label="Jenis Kirim ID" name="id_jenis_kirim" type="number" min="1"
                            placeholder="contoh: 1" required />
                    </div>

                    <div class="md:col-span-8 flex items-end justify-end gap-2">
                        <x-admin.button variant="dark" type="submit"
                            onclick="return {{ $pelangganId ? 'confirm(\'Checkout sekarang?\')' : 'alert(\'Pilih pelanggan dulu.\') || false' }}">
                            Checkout
                        </x-admin.button>
                    </div>

                    <div class="md:col-span-12 text-xs text-slate-500">
                        *Untuk versi cepat: Metode Bayar ID & Jenis Kirim ID isi angka sesuai data master kamu.
                        Nanti kalau mau, kita ganti jadi dropdown biar elegan.
                    </div>
                </form>
            </div>
        </x-admin.card>

        {{-- Table Keranjang --}}
        <x-admin.card>
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 text-sm text-slate-600">
                Total: <span class="font-bold text-slate-900">{{ $items->total() }}</span> item keranjang
            </div>

            <x-admin.table>
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <x-admin.th>Pelanggan</x-admin.th>
                        <x-admin.th>Obat</x-admin.th>
                        <x-admin.th class="text-right">Harga</x-admin.th>
                        <x-admin.th class="text-right">Qty</x-admin.th>
                        <x-admin.th class="text-right">Subtotal</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($items as $row)
                        @php
                            $subtotal = (float) $row->harga * (float) $row->jumlah_order;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <x-admin.td class="text-slate-700">
                                <div class="font-semibold">{{ $row->pelanggan->nama_pelanggan ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $row->pelanggan->no_telp ?? '-' }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">
                                <div class="font-semibold">{{ $row->obat->nama_obat ?? ('Obat #' . $row->id_obat) }}</div>
                                <div class="text-xs text-slate-500">Stok: {{ $row->obat->stok ?? '-' }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-right font-bold">
                                Rp {{ number_format((float) $row->harga, 0, ',', '.') }}
                            </x-admin.td>

                            <x-admin.td class="text-right">
                                <form method="POST" action="{{ route('keranjang.update', $row->id) }}"
                                    class="flex justify-end gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="jumlah_order" min="1" step="1"
                                        value="{{ $row->jumlah_order }}"
                                        class="w-24 rounded-xl border-slate-200 focus:border-slate-900 focus:ring-slate-900 text-right" />
                                    <x-admin.button variant="secondary" type="submit"
                                        class="px-3 py-2">Save</x-admin.button>
                                </form>
                            </x-admin.td>

                            <x-admin.td class="text-right font-extrabold text-slate-900">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </x-admin.td>

                            <x-admin.td>
                                <div class="flex justify-center">
                                    <form method="POST" action="{{ route('keranjang.destroy', $row->id) }}"
                                        onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.button variant="danger" type="submit"
                                            class="px-3 py-2">Hapus</x-admin.button>
                                    </form>
                                </div>
                            </x-admin.td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-admin.empty title="Keranjang kosong"
                                    subtitle="Tambah item di panel kanan untuk mulai." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-admin.table>

            <div class="px-4 sm:px-6 py-4 border-t border-slate-200">
                {{ $items->links() }}
            </div>
        </x-admin.card>

    </x-admin.page>
</x-app-layout>