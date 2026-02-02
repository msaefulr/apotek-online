<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Data Jenis Obat"
            subtitle="Kelola kategori/jenis obat: cari, urutkan, tambah, edit, hapus.">
            <x-slot:actions>
                <x-admin.button :href="route('jenis-obat.create')">
                    + Tambah Jenis
                </x-admin.button>
            </x-slot:actions>
        </x-admin.header>

        @if(session('success'))
            <div class="mb-5">
                <x-admin.alert type="success">{{ session('success') }}</x-admin.alert>
            </div>
        @endif

        {{-- Filter Bar --}}
        <x-admin.card class="p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('jenis-obat.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">

                <div class="md:col-span-7">
                    <x-admin.input label="Cari" name="q" :value="$q ?? ''" placeholder="Cari jenis / deskripsi..." />
                </div>

                <div class="md:col-span-4">
                    <x-admin.select label="Urutkan" name="sort">
                        <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="nama_asc" {{ ($sort ?? '') === 'nama_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="nama_desc" {{ ($sort ?? '') === 'nama_desc' ? 'selected' : '' }}>Nama (Z-A)
                        </option>
                        <option value="obat_desc" {{ ($sort ?? '') === 'obat_desc' ? 'selected' : '' }}>Jumlah Obat
                            (Terbanyak)</option>
                        <option value="obat_asc" {{ ($sort ?? '') === 'obat_asc' ? 'selected' : '' }}>Jumlah Obat
                            (Tersedikit)</option>
                    </x-admin.select>
                </div>

                <div class="md:col-span-1 flex items-end">
                    <x-admin.button variant="dark" type="submit" class="w-full">
                        Apply
                    </x-admin.button>
                </div>

                <div class="md:col-span-12">
                    <a href="{{ route('jenis-obat.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-800 underline">
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
                        <x-admin.th>Jenis</x-admin.th>
                        <x-admin.th>Deskripsi</x-admin.th>
                        <x-admin.th class="text-center">Jumlah Obat</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $row)
                        <tr class="hover:bg-slate-50">
                            <x-admin.td>
                                <div class="font-semibold text-slate-900">{{ $row->jenis }}</div>
                                <div class="text-xs text-slate-500">ID: {{ $row->id }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">
                                {{ $row->deskripsi_jenis ?? '-' }}
                            </x-admin.td>

                            <x-admin.td class="text-center">
                                @php
                                    $count = (int) ($row->obats_count ?? 0);
                                    $variant = $count === 0 ? 'danger' : ($count <= 5 ? 'warning' : 'success');
                                  @endphp
                                <div class="inline-flex items-center gap-2 justify-center">
                                    <x-admin.badge :variant="$variant">{{ $count }}</x-admin.badge>
                                    <span class="text-sm text-slate-600">obat</span>
                                </div>
                            </x-admin.td>

                            <x-admin.td>
                                <div class="flex justify-center gap-2">
                                    <x-admin.button variant="warn" :href="route('jenis-obat.edit', $row->id)"
                                        class="px-3 py-2">
                                        Edit
                                    </x-admin.button>

                                    <form action="{{ route('jenis-obat.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus jenis ini?')">
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
                            <td colspan="4">
                                <x-admin.empty title="Belum ada data jenis obat"
                                    subtitle="Klik tombol “Tambah Jenis” untuk mulai input." actionText="+ Tambah Jenis"
                                    :actionHref="route('jenis-obat.create')" />
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