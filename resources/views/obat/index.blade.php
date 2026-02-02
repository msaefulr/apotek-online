<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Data Obat" subtitle="Kelola master obat: cari, filter, urutkan, tambah, edit, hapus.">
            <x-slot:actions>
                <x-admin.button :href="route('obat.create')">
                    + Tambah Obat
                </x-admin.button>
            </x-slot:actions>
        </x-admin.header>

        @if(session('success'))
            <div class="mb-5">
                <x-admin.alert type="success">{{ session('success') }}</x-admin.alert>
            </div>
        @endif

        {{-- Filter --}}
        <x-admin.card class="p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('obat.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">

                <div class="md:col-span-5">
                    <x-admin.input label="Cari" name="q" :value="$q ?? ''" placeholder="Cari nama obat / harga..." />
                </div>

                <div class="md:col-span-3">
                    <x-admin.select label="Jenis" name="jenis">
                        <option value="">Semua</option>
                        @foreach($jenis as $j)
                            <option value="{{ $j->id }}" {{ (string) ($jenisId ?? '') === (string) $j->id ? 'selected' : '' }}>
                                {{ $j->jenis }}
                            </option>
                        @endforeach
                    </x-admin.select>
                </div>

                <div class="md:col-span-3">
                    <x-admin.select label="Urutkan" name="sort">
                        <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="nama_asc" {{ ($sort ?? '') === 'nama_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="nama_desc" {{ ($sort ?? '') === 'nama_desc' ? 'selected' : '' }}>Nama (Z-A)
                        </option>
                        <option value="harga_asc" {{ ($sort ?? '') === 'harga_asc' ? 'selected' : '' }}>Harga (Termurah)
                        </option>
                        <option value="harga_desc" {{ ($sort ?? '') === 'harga_desc' ? 'selected' : '' }}>Harga (Termahal)
                        </option>
                        <option value="stok_asc" {{ ($sort ?? '') === 'stok_asc' ? 'selected' : '' }}>Stok (Sedikit)
                        </option>
                        <option value="stok_desc" {{ ($sort ?? '') === 'stok_desc' ? 'selected' : '' }}>Stok (Banyak)
                        </option>
                    </x-admin.select>
                </div>

                <div class="md:col-span-1 flex items-end">
                    <x-admin.button variant="dark" type="submit" class="w-full">
                        Apply
                    </x-admin.button>
                </div>

                <div class="md:col-span-12">
                    <a href="{{ route('obat.index') }}" class="text-sm text-slate-500 hover:text-slate-800 underline">
                        Reset filter
                    </a>
                </div>
            </form>
        </x-admin.card>

        {{-- Table --}}
        <x-admin.card>
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 text-sm text-slate-600">
                Total: <span class="font-bold text-slate-900">{{ $data->total() }}</span> data
            </div>

            <x-admin.table>
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <x-admin.th>Nama</x-admin.th>
                        <x-admin.th>Jenis</x-admin.th>
                        <x-admin.th>Harga</x-admin.th>
                        <x-admin.th>Stok</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $row)
                        @php
                            $stok = (int) $row->stok;
                            $variant = $stok <= 0 ? 'danger' : ($stok <= 10 ? 'warning' : 'success');
                            $label = $stok <= 0 ? 'Habis' : ($stok <= 10 ? 'Menipis' : 'Aman');
                          @endphp

                        <tr class="hover:bg-slate-50">
                            <x-admin.td>
                                <div class="font-semibold text-slate-900">{{ $row->nama_obat }}</div>
                                <div class="text-xs text-slate-500">ID: {{ $row->id }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">{{ $row->jenis->jenis ?? '-' }}</x-admin.td>

                            <x-admin.td class="font-semibold text-slate-900">Rp
                                {{ number_format($row->harga_jual) }}</x-admin.td>

                            <x-admin.td>
                                <div class="inline-flex items-center gap-2">
                                    <x-admin.badge :variant="$variant">{{ $label }}</x-admin.badge>
                                    <span class="text-sm font-semibold text-slate-800">{{ $stok }}</span>
                                </div>
                            </x-admin.td>

                            <x-admin.td>
                                <div class="flex justify-center gap-2">
                                    <x-admin.button variant="warn" :href="route('obat.edit', $row->id)" class="px-3 py-2">
                                        Edit
                                    </x-admin.button>

                                    <form action="{{ route('obat.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus obat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.button variant="danger" type="submit" class="px-3 py-2">
                                            Hapus
                                        </x-admin.button>
                                    </form>
                                </div>
                            </x-admin.td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-admin.empty title="Belum ada data obat"
                                    subtitle="Klik tombol “Tambah Obat” untuk mulai input." actionText="+ Tambah Obat"
                                    :actionHref="route('obat.create')" />
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