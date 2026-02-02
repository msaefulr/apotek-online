<x-app-layout>
    <x-admin.page>

        <x-admin.header title="Edit Distributor" subtitle="Update data distributor/supplier.">
            <x-slot:actions>
                <a href="{{ route('distributor.index') }}"
                    class="text-sm font-semibold text-slate-700 hover:text-slate-900 underline">Kembali</a>
            </x-slot:actions>
        </x-admin.header>

        @if($errors->any())
            <div class="mb-5">
                <x-admin.alert type="danger">
                    <div class="font-bold mb-1">Ada yang perlu dibenerin:</div>
                    <ul class="list-disc ml-5 text-sm">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </x-admin.alert>
            </div>
        @endif

        <x-admin.card class="p-5 sm:p-6">
            <form method="POST" action="{{ route('distributor.update', $item->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                @include('distributor._form', ['item' => $item])

                <div class="flex flex-col sm:flex-row gap-3 justify-end pt-2">
                    <x-admin.button variant="secondary" :href="route('distributor.index')">Batal</x-admin.button>
                    <x-admin.button type="submit">Update</x-admin.button>
                </div>
            </form>
        </x-admin.card>

    </x-admin.page>
</x-app-layout>