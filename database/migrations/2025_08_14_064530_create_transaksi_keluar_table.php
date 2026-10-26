<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_permintaan')->nullable()->constrained('permintaan')->nullOnDelete();
            $table->date('tanggal_keluar');
            $table->foreignId('id_stok')->constrained('stok')->onDelete('cascade');
            $table->string('batch_number', 100);
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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluar');
    }
};
