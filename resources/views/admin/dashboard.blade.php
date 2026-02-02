<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Dashboard" subtitle="Ringkasan penjualan, pembelian, stok, dan pengiriman (real-time).">
            <x-slot:actions>
                <div class="text-xs text-slate-500">
                    Update: {{ now()->format('d M Y H:i') }}
                </div>
            </x-slot:actions>
        </x-admin.header>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
            <x-admin.card class="md:col-span-3 p-5 sm:p-6">
                <div class="text-sm text-slate-500">Penjualan Hari Ini</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">
                    Rp {{ number_format((float) $penjualanHariIni, 0, ',', '.') }}
                </div>
                <div class="text-xs text-slate-500 mt-2">Tanggal: {{ now()->format('d M Y') }}</div>
            </x-admin.card>

            <x-admin.card class="md:col-span-3 p-5 sm:p-6">
                <div class="text-sm text-slate-500">Penjualan Bulan Ini</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">
                    Rp {{ number_format((float) $penjualanBulanIni, 0, ',', '.') }}
                </div>
                <div class="text-xs text-slate-500 mt-2">{{ now()->format('F Y') }}</div>
            </x-admin.card>

            <x-admin.card class="md:col-span-3 p-5 sm:p-6">
                <div class="text-sm text-slate-500">Pembelian Bulan Ini</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">
                    Rp {{ number_format((float) $pembelianBulanIni, 0, ',', '.') }}
                </div>
                <div class="text-xs text-slate-500 mt-2">{{ now()->format('F Y') }}</div>
            </x-admin.card>

            <x-admin.card class="md:col-span-3 p-5 sm:p-6">
                <div class="text-sm text-slate-500">Stok Menipis</div>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">
                    {{ $stokMenipisCount }} item
                </div>

                @php
                    $badge = $stokMenipisCount > 0
                        ? 'bg-amber-50 text-amber-800 ring-1 ring-amber-200'
                        : 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200';
                @endphp

                <div class="mt-2 inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">
                    Threshold ≤ {{ $threshold }}
                </div>
            </x-admin.card>
        </div>

        {{-- GRID: Top Obat + Stok Menipis --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
            <x-admin.card class="md:col-span-7">
                <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                    <div class="font-extrabold text-slate-900">Top Obat Terjual</div>
                    <div class="text-sm text-slate-600">30 hari terakhir</div>
                </div>

                <x-admin.table>
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <x-admin.th>Obat</x-admin.th>
                            <x-admin.th class="text-right">Qty</x-admin.th>
                            <x-admin.th class="text-right">Omzet</x-admin.th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($topObatTerjual as $row)
                            <tr class="hover:bg-slate-50">
                                <x-admin.td class="font-semibold text-slate-900">{{ $row->nama_obat }}</x-admin.td>
                                <x-admin.td class="text-right font-bold">{{ $row->qty }}</x-admin.td>
                                <x-admin.td class="text-right font-bold">
                                    Rp {{ number_format((float) $row->omzet, 0, ',', '.') }}
                                </x-admin.td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-slate-500">
                                    Belum ada data penjualan 30 hari terakhir.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-admin.table>
            </x-admin.card>

            <x-admin.card class="md:col-span-5">
                <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                    <div class="font-extrabold text-slate-900">Stok Menipis</div>
                    <div class="text-sm text-slate-600">Prioritas restock</div>
                </div>

                <x-admin.table>
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <x-admin.th>Obat</x-admin.th>
                            <x-admin.th class="text-right">Stok</x-admin.th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($stokMenipis as $o)
                            @php
                                $isLow = (int) $o->stok <= $threshold;
                                $badge = $isLow
                                    ? 'bg-amber-50 text-amber-800 ring-1 ring-amber-200'
                                    : 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200';
                              @endphp
                            <tr class="hover:bg-slate-50">
                                <x-admin.td class="font-semibold text-slate-900">{{ $o->nama_obat }}</x-admin.td>
                                <x-admin.td class="text-right">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">
                                        {{ $o->stok }}
                                    </span>
                                </x-admin.td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-6 text-center text-slate-500">
                                    Belum ada data obat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-admin.table>

                <div class="px-4 sm:px-6 py-4 border-t border-slate-200 flex justify-end">
                    <x-admin.button variant="secondary" :href="route('obat.index')">Lihat Semua Obat</x-admin.button>
                </div>
            </x-admin.card>
        </div>

        {{-- GRID: Pengiriman terbaru + Penjualan terbaru --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <x-admin.card class="md:col-span-6">
                <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                    <div class="font-extrabold text-slate-900">Pengiriman Terbaru</div>
                    <div class="text-sm text-slate-600">8 data terakhir</div>
                </div>

                <div class="p-4 sm:p-6 space-y-3">
                    @forelse($pengirimanTerbaru as $g)
                        @php
                            $isArrived = ($g->status_kirim === 'Tiba Di Tujuan');
                            $badgeClass = $isArrived
                                ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'
                                : 'bg-amber-50 text-amber-800 ring-1 ring-amber-200';
                        @endphp

                        <div class="border border-slate-200 rounded-2xl p-4 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="font-extrabold text-slate-900">{{ $g->no_invoice }}</div>
                                    <div class="text-xs text-slate-500">
                                        {{ $g->penjualan->pelanggan->nama_pelanggan ?? '-' }}
                                        • {{ $g->penjualan->jenisPengiriman->nama_ekspedisi ?? '-' }}
                                    </div>
                                </div>

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                    {{ $g->status_kirim }}
                                </span>
                            </div>

                            <div class="mt-2 text-xs text-slate-600">
                                Kirim: <span class="font-semibold">{{ $g->tgl_kirim ?? '-' }}</span>
                                • Tiba: <span class="font-semibold">{{ $g->tgl_tiba ?? '-' }}</span>
                                • Kurir: <span class="font-semibold">{{ $g->nama_kurir ?? '-' }}</span>
                            </div>

                            <div class="mt-3 flex justify-end">
                                <x-admin.button variant="warn" :href="route('pengiriman.edit', $g->id)"
                                    class="px-3 py-2">Update</x-admin.button>
                            </div>
                        </div>
                    @empty
                        <div class="text-slate-500 text-sm">Belum ada pengiriman.</div>
                    @endforelse
                </div>

                <div class="px-4 sm:px-6 py-4 border-t border-slate-200 flex justify-end">
                    <x-admin.button variant="secondary" :href="route('pengiriman.index')">Lihat Semua
                        Pengiriman</x-admin.button>
                </div>
            </x-admin.card>

            <x-admin.card class="md:col-span-6">
                <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                    <div class="font-extrabold text-slate-900">Penjualan Terbaru</div>
                    <div class="text-sm text-slate-600">8 transaksi terakhir</div>
                </div>

                <div class="p-4 sm:p-6 space-y-3">
                    @forelse($penjualanTerbaru as $p)
                        <div class="border border-slate-200 rounded-2xl p-4 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="font-extrabold text-slate-900">
                                        {{ $p->pengiriman->no_invoice ?? ('INV-' . $p->id) }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $p->pelanggan->nama_pelanggan ?? '-' }}
                                        • {{ \Carbon\Carbon::parse($p->tgl_penjualan)->format('d M Y') }}
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="font-extrabold text-slate-900">
                                        Rp {{ number_format((float) $p->total_bayar, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $p->status_order }}</div>
                                </div>
                            </div>

                            <div class="mt-3 flex justify-end">
                                <x-admin.button variant="secondary" :href="route('penjualan.show', $p->id)"
                                    class="px-3 py-2">Detail</x-admin.button>
                            </div>
                        </div>
                    @empty
                        <div class="text-slate-500 text-sm">Belum ada penjualan.</div>
                    @endforelse
                </div>

                <div class="px-4 sm:px-6 py-4 border-t border-slate-200 flex justify-end">
                    <x-admin.button variant="secondary" :href="route('penjualan.index')">Lihat Semua
                        Penjualan</x-admin.button>
                </div>
            </x-admin.card>
        </div>

    </x-admin.page>
</x-app-layout>