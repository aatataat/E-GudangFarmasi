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
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained('obat')->onDelete('cascade');
            $table->string('batch_number', 100);
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('total');
            $table->date('tanggal_kadaluarsa');
            $table->integer('jumlah_masuk')->nullable();
            $table->integer('harga_masuk')->nullable();
            $table->integer('total_masuk')->nullable();
            $table->integer('jumlah_keluar')->nullable();
            $table->integer('harga_keluar')->nullable();
            $table->integer('total_keluar')->nullable();
            $table->foreignId('id_sumber_anggaran')->nullable()->constrained('sumber_anggaran')->nullOnDelete();
            $table->foreignId('id_supplier')->nullable()->constrained('supplier')->nullOnDelete();
            $table->foreignId('id_rak')->nullable()->constrained('rak_penyimpanan')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};
