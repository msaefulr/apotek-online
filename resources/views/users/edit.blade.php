<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Edit User" subtitle="Update nama & jabatan user.">
            <x-slot:actions>
                <x-admin.button variant="secondary" :href="route('users.index')">Kembali</x-admin.button>
            </x-slot:actions>
        </x-admin.header>

        @if($errors->any())
            <div class="mb-5">
                <x-admin.alert type="danger">
                    <div class="font-bold mb-1">Ada yang perlu dibenerin:</div>
                    <ul class="list-disc ml-5 text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </x-admin.alert>
            </div>
        @endif

        <x-admin.card class="p-5 sm:p-6">
            <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-6">
                        <x-admin.input label="Nama" name="name" :value="old('name', $user->name)" required />
                    </div>
                    <div class="md:col-span-6">
                        <x-admin.select label="Jabatan" name="jabatan" required>
                            @foreach($roles as $r)
                                <option value="{{ $r }}" {{ old('jabatan', $user->jabatan) === $r ? 'selected' : '' }}>
                                    {{ $r }}
                                </option>
                            @endforeach
                        </x-admin.select>
                    </div>

                    <div class="md:col-span-12">
                        <div class="text-sm text-slate-500">Email</div>
                        <div class="font-semibold text-slate-900">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <x-admin.button variant="secondary" :href="route('users.index')">Batal</x-admin.button>
                    <x-admin.button type="submit">Update</x-admin.button>
                </div>
            </form>
        </x-admin.card>

    </x-admin.page>
</x-app-layout>