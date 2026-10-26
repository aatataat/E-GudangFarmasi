<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel baru untuk menyimpan detail barang dalam satu surat permintaan.
     */
    public function up(): void
    {
        Schema::create('permintaan_barang', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel 'permintaan' (header surat)
            // Setiap baris di sini adalah detail dari satu permintaan.
            $table->foreignId('permintaan_id')
                ->constrained('permintaan')
                ->onDelete('cascade');

            // Relasi ke Stok/Item (barang apa yang diminta)
            $table->foreignId('id_stok')
                ->constrained('stok')
                ->onDelete('cascade');

            // Jumlah barang yang diminta
            $table->integer('jumlah_diminta');

            // Anda mungkin ingin menambahkan kolom ini untuk keperluan pelaporan atau audit:
            // $table->integer('jumlah_disetujui')->nullable(); 
            // $table->text('catatan_gudang')->nullable();

            $table->timestamps();

            // Opsional: Pastikan tidak ada duplikasi item stok dalam satu permintaan
            $table->unique(['permintaan_id', 'id_stok']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_barang');
    }
};
