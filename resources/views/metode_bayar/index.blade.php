<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Metode Bayar" subtitle="Kelola metode pembayaran (transfer, cash, e-wallet, dll).">
            <x-slot:actions>
                <x-admin.button :href="route('metode-bayar.create')">+ Tambah</x-admin.button>
            </x-slot:actions>
        </x-admin.header>

        @if(session('success'))
            <div class="mb-5"><x-admin.alert type="success">{{ session('success') }}</x-admin.alert></div>
        @endif

        <x-admin.card class="p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('metode-bayar.index') }}"
                class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-8">
                    <x-admin.input label="Cari" name="q" :value="$q ?? ''"
                        placeholder="Cari metode / tempat / rekening..." />
                </div>
                <div class="md:col-span-3">
                    <x-admin.select label="Urutkan" name="sort">
                        <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="nama_asc" {{ ($sort ?? '') === 'nama_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="nama_desc" {{ ($sort ?? '') === 'nama_desc' ? 'selected' : '' }}>Nama (Z-A)
                        </option>
                    </x-admin.select>
                </div>
                <div class="md:col-span-1 flex items-end">
                    <x-admin.button variant="dark" type="submit" class="w-full">Apply</x-admin.button>
                </div>
                <div class="md:col-span-12">
                    <a href="{{ route('metode-bayar.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-800 underline">Reset</a>
                </div>
            </form>
        </x-admin.card>

        <x-admin.card>
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 text-sm text-slate-600">
                Total: <span class="font-bold text-slate-900">{{ $data->total() }}</span> data
            </div>

            <x-admin.table>
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <x-admin.th>Metode</x-admin.th>
                        <x-admin.th>Tempat</x-admin.th>
                        <x-admin.th>No Rekening</x-admin.th>
                        <x-admin.th>Logo</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $row)
                        <tr class="hover:bg-slate-50">
                            <x-admin.td>
                                <div class="font-semibold text-slate-900">{{ $row->metode_pembayaran }}</div>
                                <div class="text-xs text-slate-500">ID: {{ $row->id }}</div>
                            </x-admin.td>
                            <x-admin.td class="text-slate-700">{{ $row->tempat_bayar ?? '-' }}</x-admin.td>
                            <x-admin.td class="text-slate-700">{{ $row->no_rekening ?? '-' }}</x-admin.td>
                            <x-admin.td>
                                @if($row->url_logo)
                                    <a href="{{ $row->url_logo }}" target="_blank"
                                        class="text-indigo-600 underline text-sm">Lihat</a>
                                @else
                                    <span class="text-slate-400 text-sm">-</span>
                                @endif
                            </x-admin.td>
                            <x-admin.td>
                                <div class="flex justify-center gap-2">
                                    <x-admin.button variant="warn" :href="route('metode-bayar.edit', $row->id)"
                                        class="px-3 py-2">Edit</x-admin.button>
                                    <form action="{{ route('metode-bayar.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus metode ini?')">
                                        @csrf @method('DELETE')
                                        <x-admin.button variant="danger" type="submit"
                                            class="px-3 py-2">Hapus</x-admin.button>
                                    </form>
                                </div>
                            </x-admin.td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-admin.empty title="Belum ada metode bayar" subtitle="Klik tambah untuk input data."
                                    actionText="+ Tambah" :actionHref="route('metode-bayar.create')" />
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