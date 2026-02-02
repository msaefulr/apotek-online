<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
    <div class="md:col-span-6">
        <x-admin.input label="Nama Distributor" name="nama_distributor" :value="$item->nama_distributor ?? ''"
            placeholder="PT Sehat Sentosa" required />
    </div>

    <div class="md:col-span-6">
        <x-admin.input label="Telepon (opsional)" name="telepon" :value="$item->telepon ?? ''"
            placeholder="08xxxxxxxxxx" />
    </div>

    <div class="md:col-span-12">
        <x-admin.textarea label="Alamat (opsional)" name="alamat" :value="$item->alamat ?? ''" rows="4"
            placeholder="Alamat distributor..." />
    </div>
</div>