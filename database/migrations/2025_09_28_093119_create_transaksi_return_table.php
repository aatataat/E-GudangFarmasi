<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('transaksi_return', function (Blueprint $table) {
            $table->id();

            // Kunci asing yang berpatok pada transaksi_keluar
            $table->foreignId('id_transaksi_keluar')
                ->constrained('transaksi_keluar') // Asumsi nama tabel adalah 'transaksi_keluar'
                ->onDelete('cascade'); // Jika transaksi keluar dihapus, return juga ikut dihapus (optional)
            $table->date('tanggal_return');
            $table->integer('jumlah');
            $table->text('Keterangan')->nullable();

            // Kolom untuk menentukan status barang: 'Musnah' atau 'Kembali_Stok'
            $table->enum('status_stok', ['Expired', 'Kembali Stok'])->default('Expired');

            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_return');
    }
};
