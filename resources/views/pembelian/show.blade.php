<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Detail Pembelian" subtitle="Rincian pembelian + item yang masuk stok.">
            <x-slot:actions>
                <x-admin.button variant="secondary" :href="route('pembelian.index')">Kembali</x-admin.button>
            </x-slot:actions>
        </x-admin.header>

        <x-admin.card class="p-5 sm:p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <div class="text-sm text-slate-500">No Nota</div>
                    <div class="text-xl font-extrabold text-slate-900">{{ $pembelian->no_nota }}</div>
                </div>
                <div class="md:col-span-4">
                    <div class="text-sm text-slate-500">Tanggal</div>
                    <div class="font-semibold text-slate-900">{{ $pembelian->tgl_pembelian }}</div>
                </div>
                <div class="md:col-span-4">
                    <div class="text-sm text-slate-500">Distributor</div>
                    <div class="font-semibold text-slate-900">{{ $pembelian->distributor->nama_distributor ?? '-' }}
                    </div>
                    <div class="text-xs text-slate-500">{{ $pembelian->distributor->telepon ?? '-' }}</div>
                </div>
            </div>
        </x-admin.card>

        <x-admin.card>
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200">
                <div class="font-extrabold text-slate-900">Item Pembelian</div>
                <div class="text-sm text-slate-600">Total item: {{ $pembelian->details->count() }}</div>
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
                    @foreach($pembelian->details as $d)
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

            <div class="px-4 sm:px-6 py-4 border-t border-slate-200 flex justify-end">
                <div class="text-right">
                    <div class="text-sm text-slate-500">Subtotal</div>
                    <div class="text-xl font-extrabold text-slate-900">
                        Rp {{ number_format((float) $subtotal, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-slate-500">Total bayar tersimpan: Rp
                        {{ number_format((float) $pembelian->total_bayar, 0, ',', '.') }}</div>
                </div>
            </div>
        </x-admin.card>

    </x-admin.page>
</x-app-layout>