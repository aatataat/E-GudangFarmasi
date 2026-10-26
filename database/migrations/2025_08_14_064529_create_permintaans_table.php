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
        Schema::create('permintaan', function (Blueprint $table) {
            $table->id();

            // Relasi ke Faskes (siapa yang mengajukan)
            $table->foreignId('faskes_id')->constrained('faskes')->onDelete('cascade');

            // Data Surat
            $table->string('nomor_surat')->unique();
            $table->string('perihal');
            $table->date('tanggal_surat');
            
            // Status Permintaan (Penting untuk alur kerja ke Gudang Farmasi)
            $table->enum('status', ['DIAJUKAN', 'DITERIMA', 'DITOLAK'])->default('DIAJUKAN');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan');
    }
};
