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
        Schema::create('faskes', function (Blueprint $table) {
            // Kolom ID utama
            $table->id(); // Sama dengan bigint UNSIGNED NOT NULL AUTO_INCREMENT

            // Data Utama Faskes
            $table->string('nama_faskes'); // varchar(255) NOT NULL
            $table->text('alamat'); // text NOT NULL
            $table->string('telp')->nullable(); // varchar(255) DEFAULT NULL
            $table->string('email')->unique()->nullable(); // varchar(255) DEFAULT NULL

            // Data Kepala Faskes
            $table->string('nama_kepala'); // varchar(255) NOT NULL
            $table->string('nip_kepala')->nullable(); // varchar(255) DEFAULT NULL

            // Data Apoteker/Penanggung Jawab
            $table->string('nama_apoteker'); // varchar(255) NOT NULL
            $table->string('nip_apoteker')->nullable(); // varchar(255) DEFAULT NULL

            // Timestamps standar Laravel
            $table->timestamps(); // created_at dan updated_at
            $table->softDeletes(); // deleted_at untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faskes');
    }
};