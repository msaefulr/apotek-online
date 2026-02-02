<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Detail Penjualan" subtitle="Rincian order, item, pembayaran, dan pengiriman.">
            <x-slot:actions>
                <x-admin.button variant="secondary" :href="route('penjualan.index')">Kembali</x-admin.button>
                @if($penjualan->pengiriman)
                    <x-admin.button variant="warn" :href="route('pengiriman.edit', $penjualan->pengiriman->id)">Update
                        Pengiriman</x-admin.button>
                @endif
            </x-slot:actions>
        </x-admin.header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            <x-admin.card class="lg:col-span-7 p-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-6">
                        <div class="text-sm text-slate-500">Tanggal</div>
                        <div class="font-semibold text-slate-900">{{ $penjualan->tgl_penjualan }}</div>
                    </div>
                    <div class="md:col-span-6">
                        <div class="text-sm text-slate-500">Status Order</div>
                        <div class="font-extrabold text-slate-900">{{ $penjualan->status_order }}</div>
                        <div class="text-xs text-slate-500">{{ $penjualan->keterangan_status ?? '-' }}</div>
                    </div>

                    <div class="md:col-span-6">
                        <div class="text-sm text-slate-500">Pelanggan</div>
                        <div class="font-semibold text-slate-900">{{ $penjualan->pelanggan->nama_pelanggan ?? '-' }}
                        </div>
                        <div class="text-xs text-slate-500">{{ $penjualan->pelanggan->no_telp ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-6">
                        <div class="text-sm text-slate-500">Pembayaran</div>
                        <div class="font-semibold text-slate-900">
                            {{ $penjualan->metodeBayar->metode_pembayaran ?? '-' }}</div>
                        <div class="text-xs text-slate-500">{{ $penjualan->metodeBayar->tempat_bayar ?? '-' }}</div>
                    </div>

                    <div class="md:col-span-12">
                        <div class="text-sm text-slate-500">Pengiriman</div>
                        <div class="font-semibold text-slate-900">
                            {{ $penjualan->jenisPengiriman->nama_ekspedisi ?? '-' }}
                            {{ $penjualan->jenisPengiriman->jenis_kirim ? ' - ' . $penjualan->jenisPengiriman->jenis_kirim : '' }}
                        </div>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card class="lg:col-span-5 p-5 sm:p-6">
                <div class="font-extrabold text-slate-900 mb-4">Ringkasan Biaya</div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Subtotal item</span>
                        <span class="font-bold text-slate-900">Rp
                            {{ number_format((float) $subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Ongkir</span>
                        <span class="font-bold text-slate-900">Rp
                            {{ number_format((float) $penjualan->ongkos_kirim, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Biaya App</span>
                        <span class="font-bold text-slate-900">Rp
                            {{ number_format((float) $penjualan->biaya_app, 0, ',', '.') }}</span>
                    </div>

                    <div class="border-t border-slate-200 pt-3 flex justify-between">
                        <span class="text-slate-700 font-extrabold">Grand Total</span>
                        <span class="text-slate-900 font-extrabold text-lg">Rp
                            {{ number_format((float) $grandTotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="text-xs text-slate-500">
                        Total bayar tersimpan: Rp {{ number_format((float) $penjualan->total_bayar, 0, ',', '.') }}
                    </div>
                </div>

                @if($penjualan->pengiriman)
                    @php
                        $inv = $penjualan->pengiriman->no_invoice ?? ('INV-' . $penjualan->id);
                      @endphp
                    <div class="mt-5 border border-slate-200 rounded-2xl p-4 bg-slate-50">
                        <div class="text-sm text-slate-500">Invoice</div>
                        <div class="font-extrabold text-slate-900">{{ $inv }}</div>
                        <div class="text-xs text-slate-600 mt-1">
                            Status kirim: <span class="font-bold">{{ $penjualan->pengiriman->status_kirim }}</span>
                        </div>
                    </div>
                @endif
            </x-admin.card>
        </div>

        <x-admin.card>
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                <div class="font-extrabold text-slate-900">Item Penjualan</div>
                <div class="text-sm text-slate-600">Total item: {{ $penjualan->details->count() }}</div>
            </div>

            <x-admin.table>
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <x-admin.th>Obat</x-admin.th>
                        <x-admin.th class="text-right">Harga</x-admin.th>
                        <x-admin.th class="text-right">Qty</x-admin.th>
                        <x-admin.th class="text-right">Subtotal</x-admin.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($penjualan->details as $d)
                        <tr class="hover:bg-slate-50">
                            <x-admin.td class="font-semibold text-slate-900">
                                {{ $d->obat->nama_obat ?? ('Obat #' . $d->id_obat) }}
                            </x-admin.td>
                            <x-admin.td class="text-right font-bold">
                                Rp {{ number_format((float) $d->harga_beli, 0, ',', '.') }}
                            </x-admin.td>
                            <x-admin.td class="text-right font-bold">{{ $d->jumlah_beli }}</x-admin.td>
                            <x-admin.td class="text-right font-extrabold text-slate-900">
                                Rp {{ number_format((float) $d->subtotal, 0, ',', '.') }}
                            </x-admin.td>
                        </tr>
                    @endforeach
                </tbody>
            </x-admin.table>
        </x-admin.card>

    </x-admin.page>
</x-app-layout>