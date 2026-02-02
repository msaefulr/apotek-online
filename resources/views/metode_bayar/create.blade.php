<x-app-layout>
    <x-admin.page>
        <x-admin.header title="Tambah Metode Bayar" subtitle="Input metode pembayaran baru.">
            <x-slot:actions>
                <a href="{{ route('metode-bayar.index') }}"
                    class="text-sm font-semibold text-slate-700 hover:text-slate-900 underline">Kembali</a>
            </x-slot:actions>
        </x-admin.header>

        <x-admin.card class="p-5 sm:p-6">
            <form method="POST" action="{{ route('metode-bayar.store') }}" class="space-y-6">
                @csrf
                @include('metode_bayar._form', ['item' => (object) []])

                <div class="flex gap-3 justify-end">
                    <x-admin.button variant="secondary" :href="route('metode-bayar.index')">Batal</x-admin.button>
                    <x-admin.button type="submit">Simpan</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </x-admin.page>
</x-app-layout>