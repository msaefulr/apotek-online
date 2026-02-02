<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Pengiriman" subtitle="Kelola status pengiriman dari transaksi penjualan.">
            <x-slot:actions>
                <div class="text-xs text-slate-500">
                    Pengiriman dibuat otomatis saat penjualan ✅
                </div>
            </x-slot:actions>
        </x-admin.header>

        @if(session('success'))
            <div class="mb-5"><x-admin.alert type="success">{{ session('success') }}</x-admin.alert></div>
        @endif

        <x-admin.card class="p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('pengiriman.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-8">
                    <x-admin.input label="Cari" name="q" :value="$q ?? ''"
                        placeholder="Cari invoice / pelanggan / status / kurir..." />
                </div>

                <div class="md:col-span-3">
                    <x-admin.select label="Urutkan" name="sort">
                        <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="kirim_desc" {{ ($sort ?? '') === 'kirim_desc' ? 'selected' : '' }}>Tgl Kirim
                            (Terbaru)</option>
                        <option value="kirim_asc" {{ ($sort ?? '') === 'kirim_asc' ? 'selected' : '' }}>Tgl Kirim
                            (Terlama)</option>
                    </x-admin.select>
                </div>

                <div class="md:col-span-1 flex items-end">
                    <x-admin.button variant="dark" type="submit" class="w-full">Apply</x-admin.button>
                </div>

                <div class="md:col-span-12">
                    <a href="{{ route('pengiriman.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-800 underline">Reset filter</a>
                </div>
            </form>
        </x-admin.card>

        <x-admin.card>
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 text-sm text-slate-600">
                Total: <span class="font-bold text-slate-900">{{ $data->total() }}</span> pengiriman
            </div>

            <x-admin.table>
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <x-admin.th>Invoice</x-admin.th>
                        <x-admin.th>Pelanggan</x-admin.th>
                        <x-admin.th>Ekspedisi</x-admin.th>
                        <x-admin.th>Status</x-admin.th>
                        <x-admin.th>Timeline</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $row)
                        @php
                            $isArrived = ($row->status_kirim === 'Tiba Di Tujuan');
                            $badgeClass = $isArrived
                                ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'
                                : 'bg-amber-50 text-amber-800 ring-1 ring-amber-200';
                        @endphp

                        <tr class="hover:bg-slate-50">
                            <x-admin.td>
                                <div class="font-extrabold text-slate-900">{{ $row->no_invoice }}</div>
                                <div class="text-xs text-slate-500">Penjualan #{{ $row->id_penjualan }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">
                                <div class="font-semibold">{{ $row->penjualan->pelanggan->nama_pelanggan ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $row->penjualan->pelanggan->no_telp ?? '-' }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">
                                {{ $row->penjualan->jenisPengiriman->nama_ekspedisi ?? '-' }}
                                <div class="text-xs text-slate-500">
                                    {{ $row->penjualan->jenisPengiriman->jenis_kirim ?? '' }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">
                                <div class="inline-flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                        {{ $row->status_kirim }}
                                    </span>
                                </div>

                                <div class="text-xs text-slate-500 mt-1">
                                    Kurir: <span class="font-semibold text-slate-700">{{ $row->nama_kurir ?? '-' }}</span>
                                </div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">
                                <div class="text-sm">
                                    Kirim:
                                    <span class="font-semibold">{{ $row->tgl_kirim ?? '-' }}</span>
                                </div>
                                <div class="text-sm">
                                    Tiba:
                                    <span class="font-semibold">{{ $row->tgl_tiba ?? '-' }}</span>
                                </div>
                            </x-admin.td>

                            <x-admin.td>
                                <div class="flex justify-center gap-2">
                                    {{-- Quick action: Tandai Tiba --}}
                                    @if(!$isArrived)
                                        <form action="{{ route('pengiriman.markArrived', $row->id) }}" method="POST"
                                            onsubmit="return confirm('Tandai pengiriman ini sebagai Tiba Di Tujuan?')">
                                            @csrf
                                            @method('PATCH')
                                            <x-admin.button variant="dark" type="submit" class="px-3 py-2">
                                                Tandai Tiba
                                            </x-admin.button>
                                        </form>
                                    @else
                                        <span
                                            class="text-xs font-semibold text-emerald-700 bg-emerald-50 ring-1 ring-emerald-200 px-3 py-2 rounded-xl">
                                            Selesai ✅
                                        </span>
                                    @endif

                                    <x-admin.button variant="warn" :href="route('pengiriman.edit', $row->id)"
                                        class="px-3 py-2">
                                        Update
                                    </x-admin.button>
                                </div>
                            </x-admin.td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-admin.empty title="Belum ada pengiriman"
                                    subtitle="Pengiriman akan muncul otomatis setelah kamu membuat transaksi penjualan." />
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