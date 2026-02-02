<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
    <div class="md:col-span-6">
        <x-admin.input label="Metode Pembayaran" name="metode_pembayaran" :value="$item->metode_pembayaran ?? ''"
            placeholder="Transfer / Cash / E-Wallet" required />
    </div>
    <div class="md:col-span-6">
        <x-admin.input label="Tempat Bayar (opsional)" name="tempat_bayar" :value="$item->tempat_bayar ?? ''"
            placeholder="BCA / Kasir / QRIS" />
    </div>
    <div class="md:col-span-6">
        <x-admin.input label="No Rekening (opsional)" name="no_rekening" :value="$item->no_rekening ?? ''"
            placeholder="1234567890" />
    </div>
    <div class="md:col-span-6">
        <x-admin.input label="URL Logo (opsional)" name="url_logo" :value="$item->url_logo ?? ''"
            placeholder="https://..." />
    </div>
</div>