<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_metode_bayar')->constrained('metode_bayar')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('tgl_penjualan');
            $table->string('url_resep', 255)->nullable();

            $table->decimal('ongkos_kirim', 15, 2);
            $table->decimal('biaya_app', 15, 2);
            $table->decimal('total_bayar', 15, 2);

            $table->enum('status_order', [
                'Menunggu Konfirmasi',
                'Diproses',
                'Menunggu Kurir',
                'Dibatalkan Pembeli',
                'Dibatalkan Penjual',
                'Bermasalah',
                'Selesai'
            ])->default('Menunggu Konfirmasi');

            $table->string('keterangan_status', 255)->nullable();

            $table->foreignId('id_jenis_kirim')->constrained('jenis_pengiriman')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('id_pelanggan')->constrained('pelanggan')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
