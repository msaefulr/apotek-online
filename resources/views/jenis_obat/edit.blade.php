<x-app-layout>
    <div class="p-6 max-w-xl">

        <h2 class="text-xl font-bold mb-4">Edit Jenis Obat</h2>

        <form action="{{ route('jenis-obat.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Jenis</label>
                <input type="text" name="jenis" value="{{ $item->jenis }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi_jenis"
                    class="w-full border p-2 rounded">{{ $item->deskripsi_jenis }}</textarea>
            </div>

            <div class="mb-3">
                <label>URL Gambar</label>
                <input type="text" name="image_url" value="{{ $item->image_url }}" class="w-full border p-2 rounded">
            </div>

            <button class="bg-indigo-600 text-white px-4 py-2 rounded">
                Update
            </button>

            <a href="{{ route('jenis-obat.index') }}" class="ml-2 text-gray-600">
                Kembali
            </a>

        </form>

    </div>
</x-app-layout>