<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Users" subtitle="Kelola jabatan user (admin/apoteker/karyawan/kasir/pemilik)." />

        @if(session('success'))
            <div class="mb-5"><x-admin.alert type="success">{{ session('success') }}</x-admin.alert></div>
        @endif

        <x-admin.card class="p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-10">
                    <x-admin.input label="Cari" name="q" :value="$q ?? ''" placeholder="Cari nama/email/jabatan..." />
                </div>
                <div class="md:col-span-2 flex items-end">
                    <x-admin.button variant="dark" type="submit" class="w-full">Search</x-admin.button>
                </div>
                <div class="md:col-span-12">
                    <a href="{{ route('users.index') }}"
                        class="text-sm text-slate-500 hover:text-slate-800 underline">Reset</a>
                </div>
            </form>
        </x-admin.card>

        <x-admin.card>
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 text-sm text-slate-600">
                Total: <span class="font-bold text-slate-900">{{ $data->total() }}</span> user
            </div>

            <x-admin.table>
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <x-admin.th>Nama</x-admin.th>
                        <x-admin.th>Email</x-admin.th>
                        <x-admin.th>Jabatan</x-admin.th>
                        <x-admin.th class="text-center">Aksi</x-admin.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($data as $u)
                        <tr class="hover:bg-slate-50">
                            <x-admin.td class="font-semibold text-slate-900">{{ $u->name }}</x-admin.td>
                            <x-admin.td class="text-slate-700">{{ $u->email }}</x-admin.td>
                            <x-admin.td>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800">
                                    {{ $u->jabatan ?? '-' }}
                                </span>
                            </x-admin.td>
                            <x-admin.td>
                                <div class="flex justify-center">
                                    <x-admin.button variant="warn" :href="route('users.edit', $u->id)"
                                        class="px-3 py-2">Edit</x-admin.button>
                                </div>
                            </x-admin.td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-admin.empty title="Belum ada user" subtitle="User akan muncul dari registrasi/login." />
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