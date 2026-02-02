<x-app-layout>
    <x-admin.page>
        <x-admin.header title="Edit Metode Bayar" subtitle="Update data metode pembayaran.">
            <x-slot:actions>
                <a href="{{ route('metode-bayar.index') }}"
                    class="text-sm font-semibold text-slate-700 hover:text-slate-900 underline">Kembali</a>
            </x-slot:actions>
        </x-admin.header>

        <x-admin.card class="p-5 sm:p-6">
            <form method="POST" action="{{ route('metode-bayar.update', $item->id) }}" class="space-y-6">
                @csrf @method('PUT')
                @include('metode_bayar._form', ['item' => $item])

                <div class="flex gap-3 justify-end">
                    <x-admin.button variant="secondary" :href="route('metode-bayar.index')">Batal</x-admin.button>
                    <x-admin.button type="submit">Update</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </x-admin.page>
</x-app-layout>