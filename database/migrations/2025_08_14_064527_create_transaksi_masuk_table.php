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
        Schema::create('transaksi_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no', 50);
            $table->foreignId('obat_id')->constrained('obat')->onDelete('cascade');
            $table->date('tanggal_masuk');
            $table->string('batch_number', 50);
            $table->date('tanggal_kadaluarsa');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('total');
            $table->foreignId('id_sumber_anggaran')->nullable()->constrained('sumber_anggaran')->nullOnDelete();
            $table->foreignId('id_supplier')->nullable()->constrained('supplier')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuk');
    }
};
