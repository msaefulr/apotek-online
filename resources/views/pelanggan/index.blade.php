<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Pelanggan" subtitle="Kelola data pelanggan sesuai PDM (alamat 1-3 + dokumen).">
            <x-slot:actions>
                <x-admin.button :href="route('pelanggan.create')">+ Tambah Pelanggan</x-admin.button>
            </x-slot:actions>
        </x-admin.header>

        @if(session('success'))
            <div class="mb-5"><x-admin.alert type="success">{{ session('success') }}</x-admin.alert></div>
        @endif

        <x-admin.card class="p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('pelanggan.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-8">
                    <x-admin.input label="Cari" name="q" :value="$q ?? ''"
                        placeholder="Cari nama / email / no telp / kota..." />
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
                    <a href="{{ route('pelanggan.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-800 underline">Reset filter</a>
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
                        <x-admin.th>Nama</x-admin.th>
                        <x-admin.th>Kontak</x-admin.th>
                        <x-admin.th>Alamat Utama</x-admin.th>
                        <x-admin.th>Dokumen</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $row)
                        <tr class="hover:bg-slate-50">
                            <x-admin.td>
                                <div class="font-semibold text-slate-900">{{ $row->nama_pelanggan }}</div>
                                <div class="text-xs text-slate-500">ID: {{ $row->id }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">
                                <div>{{ $row->email ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $row->no_telp ?? '-' }}</div>
                            </x-admin.td>

                            <x-admin.td class="text-slate-700">
                                <div class="font-medium">{{ $row->kota1 ?? '-' }}, {{ $row->provinsi1 ?? '-' }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ $row->alamat1 ? \Illuminate\Support\Str::limit($row->alamat1, 40) : '-' }}
                                </div>
                            </x-admin.td>

                            <x-admin.td>
                                <div class="flex gap-2 text-sm">
                                    @if($row->url_ktp)
                                        <a class="text-indigo-600 underline" target="_blank" href="{{ $row->url_ktp }}">KTP</a>
                                    @else
                                        <span class="text-slate-400">KTP</span>
                                    @endif

                                    @if($row->url_foto)
                                        <a class="text-indigo-600 underline" target="_blank"
                                            href="{{ $row->url_foto }}">Foto</a>
                                    @else
                                        <span class="text-slate-400">Foto</span>
                                    @endif
                                </div>
                            </x-admin.td>

                            <x-admin.td>
                                <div class="flex justify-center gap-2">
                                    <x-admin.button variant="warn" :href="route('pelanggan.edit', $row->id)"
                                        class="px-3 py-2">Edit</x-admin.button>
                                    <form action="{{ route('pelanggan.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus pelanggan ini?')">
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
                                <x-admin.empty title="Belum ada pelanggan"
                                    subtitle="Klik tambah untuk input data pelanggan." actionText="+ Tambah Pelanggan"
                                    :actionHref="route('pelanggan.create')" />
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