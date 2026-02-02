<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Pembelian" subtitle="Catat pembelian dari distributor (stok otomatis bertambah).">
            <x-slot:actions>
                <x-admin.button :href="route('pembelian.create')">+ Tambah Pembelian</x-admin.button>
            </x-slot:actions>
        </x-admin.header>

        @if(session('success'))
            <div class="mb-5"><x-admin.alert type="success">{{ session('success') }}</x-admin.alert></div>
        @endif
        @if(session('error'))
            <div class="mb-5"><x-admin.alert type="danger">{{ session('error') }}</x-admin.alert></div>
        @endif

        <x-admin.card class="p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('pembelian.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-8">
                    <x-admin.input label="Cari" name="q" :value="$q ?? ''" placeholder="Cari nota / distributor..." />
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
                    <a href="{{ route('pembelian.index') }}"
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
                        <x-admin.th>Nota</x-admin.th>
                        <x-admin.th>Distributor</x-admin.th>
                        <x-admin.th>Tanggal</x-admin.th>
                        <x-admin.th>Item</x-admin.th>
                        <x-admin.th>Total</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $row)
                        <tr class="hover:bg-slate-50">
                            <x-admin.td>
                                <div class="font-semibold text-slate-900">{{ $row->nota }}</div>
                                <div class="text-xs text-slate-500">ID: {{ $row->id }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">{{ $row->distributor->nama_distributor ?? '-' }}</x-admin.td>
                            <x-admin.td
                                class="text-slate-700">{{ \Carbon\Carbon::parse($row->tgl_pembelian)->format('d M Y') }}</x-admin.td>
                            <x-admin.td class="text-slate-700">{{ $row->details_count }} item</x-admin.td>
                            <x-admin.td class="font-semibold text-slate-900">
                                Rp {{ number_format((float) $row->total_bayar, 0, ',', '.') }}
                            </x-admin.td>

                            <x-admin.td>
                                <div class="flex justify-center gap-2">
                                    <x-admin.button variant="secondary" :href="route('pembelian.show', $row->id)"
                                        class="px-3 py-2">Detail</x-admin.button>

                                    <form action="{{ route('pembelian.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus transaksi pembelian ini?')">
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
                                <x-admin.empty title="Belum ada pembelian"
                                    subtitle="Klik tombol tambah pembelian untuk mulai catat stok masuk."
                                    actionText="+ Tambah Pembelian" :actionHref="route('pembelian.create')" />
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