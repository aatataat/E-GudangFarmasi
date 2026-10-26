<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permintaan extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'permintaan';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Kolom status sengaja diisi dari form CreatePermintaan, 
     * tetapi biasanya default diisi oleh database.
     */
    protected $fillable = [
        'faskes_id',
        'nomor_surat',
        'perihal',
        'tanggal_surat',
        'permintaan_barang_id',
        'status',
        // Tambahkan kolom lain seperti 'keterangan' jika ada di tabel Anda
    ];

    /**
     * Casting untuk tipe data tertentu
     */
    protected $casts = [
        'tanggal_surat' => 'date',
        'jumlah' => 'integer',
        // 'status' tidak perlu di-cast karena sudah ENUM/string
    ];


    // =====================================
    // RELASI (RELATIONSHIPS)
    // =====================================

    /**
     * Permintaan ini milik satu Faskes (Puskesmas/RS).
     */
    public function faskes()
    {
        return $this->belongsTo(Faskes::class, 'faskes_id');
    }

    /**
     * Permintaan ini merujuk ke satu item Stok (Obat/BMHP) tertentu.
     * (Catatan: Sesuai skema Anda, 1 permintaan = 1 item stok. 
     * Jika Anda memiliki tabel detail_permintaan, relasi ini akan pindah ke sana.)
     */
    public function itemPermintaan()
    {
        // Asumsi: Permintaan memiliki banyak itemPermintaan
        // Kunci asing default adalah permintaan_id pada tabel permintaan_barangs
        return $this->hasMany(PermintaanBarang::class, 'permintaan_id'); 
    }

    /**
     * Relasi ke user yang membuat permintaan (optional, jika ada kolom user_id)
     */
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'created_by_user_id');
    // }
    public function transaksiKeluar(): HasMany
    {
        return $this->hasMany(TransaksiKeluar::class, 'id_permintaan');
    }
}