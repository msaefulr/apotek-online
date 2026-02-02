<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Distributor" subtitle="Kelola distributor/supplier obat.">
            <x-slot:actions>
                <x-admin.button :href="route('distributor.create')">+ Tambah</x-admin.button>
            </x-slot:actions>
        </x-admin.header>

        @if(session('success'))
            <div class="mb-5"><x-admin.alert type="success">{{ session('success') }}</x-admin.alert></div>
        @endif

        <x-admin.card class="p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('distributor.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-8">
                    <x-admin.input label="Cari" name="q" :value="$q ?? ''"
                        placeholder="Cari nama / telepon / alamat..." />
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
                    <a href="{{ route('distributor.index') }}"
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
                        <x-admin.th>Telepon</x-admin.th>
                        <x-admin.th>Alamat</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $row)
                        <tr class="hover:bg-slate-50">
                            <x-admin.td>
                                <div class="font-semibold text-slate-900">{{ $row->nama_distributor }}</div>
                                <div class="text-xs text-slate-500">ID: {{ $row->id }}</div>
                            </x-admin.td>
                            <x-admin.td class="text-slate-700">{{ $row->telepon ?? '-' }}</x-admin.td>
                            <x-admin.td class="text-slate-700">
                                {{ $row->alamat ? \Illuminate\Support\Str::limit($row->alamat, 50) : '-' }}
                            </x-admin.td>
                            <x-admin.td>
                                <div class="flex justify-center gap-2">
                                    <x-admin.button variant="warn" :href="route('distributor.edit', $row->id)"
                                        class="px-3 py-2">Edit</x-admin.button>
                                    <form action="{{ route('distributor.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus distributor ini?')">
                                        @csrf @method('DELETE')
                                        <x-admin.button variant="danger" type="submit"
                                            class="px-3 py-2">Hapus</x-admin.button>
                                    </form>
                                </div>
                            </x-admin.td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-admin.empty title="Belum ada distributor" subtitle="Klik tambah untuk input."
                                    actionText="+ Tambah" :actionHref="route('distributor.create')" />
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