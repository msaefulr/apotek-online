<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Penjualan" subtitle="Kelola transaksi penjualan (stok berkurang otomatis).">
            <x-slot:actions>
                <x-admin.button :href="route('penjualan.create')">+ Tambah Penjualan</x-admin.button>
            </x-slot:actions>
        </x-admin.header>

        @if(session('success'))
            <div class="mb-5"><x-admin.alert type="success">{{ session('success') }}</x-admin.alert></div>
        @endif
        @if(session('error'))
            <div class="mb-5"><x-admin.alert type="danger">{{ session('error') }}</x-admin.alert></div>
        @endif

        <x-admin.card class="p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('penjualan.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-8">
                    <x-admin.input label="Cari" name="q" :value="$q ?? ''"
                        placeholder="Cari pelanggan / invoice / status..." />
                </div>

                <div class="md:col-span-3">
                    <x-admin.select label="Urutkan" name="sort">
                        <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="tgl_desc" {{ ($sort ?? '') === 'tgl_desc' ? 'selected' : '' }}>Tanggal (Terbaru)
                        </option>
                        <option value="tgl_asc" {{ ($sort ?? '') === 'tgl_asc' ? 'selected' : '' }}>Tanggal (Terlama)
                        </option>
                    </x-admin.select>
                </div>

                <div class="md:col-span-1 flex items-end">
                    <x-admin.button variant="dark" type="submit" class="w-full">Apply</x-admin.button>
                </div>

                <div class="md:col-span-12">
                    <a href="{{ route('penjualan.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-800 underline">Reset filter</a>
                </div>
            </form>
        </x-admin.card>

        <x-admin.card>
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 text-sm text-slate-600">
                Total: <span class="font-bold text-slate-900">{{ $data->total() }}</span> transaksi
            </div>

            <x-admin.table>
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <x-admin.th>Invoice</x-admin.th>
                        <x-admin.th>Pelanggan</x-admin.th>
                        <x-admin.th>Tanggal</x-admin.th>
                        <x-admin.th>Status</x-admin.th>
                        <x-admin.th>Total</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $row)
                        <tr class="hover:bg-slate-50">
                            <x-admin.td>
                                <div class="font-semibold text-slate-900">
                                    {{ $row->pengiriman->no_invoice ?? ('INV-' . $row->id) }}</div>
                                <div class="text-xs text-slate-500">{{ $row->details_count }} item</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">
                                <div class="font-semibold">{{ $row->pelanggan->nama_pelanggan ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $row->pelanggan->no_telp ?? '-' }}</div>
                            </x-admin.td>

                            <x-admin.td
                                class="text-slate-700">{{ \Carbon\Carbon::parse($row->tgl_penjualan)->format('d M Y') }}</x-admin.td>

                            <x-admin.td class="text-slate-700">
                                <div class="font-semibold">{{ $row->status_order }}</div>
                                <div class="text-xs text-slate-500">{{ $row->pengiriman->status_kirim ?? '-' }}</div>
                            </x-admin.td>

                            <x-admin.td class="font-bold text-slate-900">
                                Rp {{ number_format((float) $row->total_bayar, 0, ',', '.') }}
                            </x-admin.td>

                            <x-admin.td>
                                <div class="flex justify-center gap-2">
                                    <x-admin.button variant="secondary" :href="route('penjualan.show', $row->id)"
                                        class="px-3 py-2">Detail</x-admin.button>

                                    <form action="{{ route('penjualan.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus transaksi penjualan ini?')">
                                        @csrf @method('DELETE')
                                        <x-admin.button variant="danger" type="submit"
                                            class="px-3 py-2">Hapus</x-admin.button>
                                    </form>
                                </div>
                            </x-admin.td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-admin.empty title="Belum ada penjualan"
                                    subtitle="Klik tambah penjualan untuk mulai transaksi." actionText="+ Tambah Penjualan"
                                    :actionHref="route('penjualan.create')" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-admin.table>

            <div class="px-4 sm:px-6 py-4 border-t border-slate-200">
                {{ $data->links() }}
            </div>
        </x-admin.card>

    </x-admin.page>
</x-app-layout>