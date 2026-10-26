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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            
            // Kolom role yang direvisi: Ditambahkan 'Faskes'
            $table->enum('role',['Admin','Pegawai','Pimpinan','Faskes']); 
            
            // Kolom Faskes ID (Relasi ke tabel 'faskes')
            // Penting: Hanya role 'Faskes' yang wajib memiliki faskes_id.
            $table->foreignId('faskes_id')->nullable()->constrained('faskes')->nullOnDelete();
            

            $table->string('password');
            $table->string('telegram_chat_id')->nullable()->unique();
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel untuk reset password token
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel untuk pelacakan sesi
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
