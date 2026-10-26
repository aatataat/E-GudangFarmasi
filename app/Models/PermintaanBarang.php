<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PermintaanBarang
 * Merepresentasikan item-item spesifik (detail) yang diminta 
 * dalam satu surat permintaan logistik (Permintaan header).
 */
class PermintaanBarang extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'permintaan_barang';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'permintaan_id',      // Foreign key ke Permintaan
        'id_stok',            // Foreign key ke Stok (item yang diminta)
        'jumlah_diminta',     // Jumlah yang diajukan
        // 'jumlah_disetujui', // Jika kolom ini nanti diaktifkan
    ];

    /**
     * Casting untuk tipe data tertentu
     */
    protected $casts = [
        'jumlah_diminta' => 'integer',
    ];


    // =====================================
    // RELASI (RELATIONSHIPS)
    // =====================================

    /**
     * Detail item ini milik satu Permintaan (surat header).
     */
    public function permintaan()
    {
        // Relasi many-to-one (detail_permintaan belongs to permintaan)
        return $this->belongsTo(Permintaan::class, 'permintaan_id');
    }

    /**
     * Item ini merujuk ke Stok/Obat tertentu.
     */
    public function stok()
    {
        // Relasi many-to-one (permintaan_barang belongs to stok)
        return $this->belongsTo(Stok::class, 'id_stok');
    }

    public function transaksiKeluar()
    {
        // Berdasarkan sql Anda, transaksi_keluar mencatat parent id melalui 'id_permintaan'
        // Kita hubungkan kolom 'id_permintaan' di tabel transaksi_keluar dengan 'permintaan_id' di tabel ini
        return $this->hasMany(TransaksiKeluar::class, 'id_permintaan', 'permintaan_id');
    }

    // =====================================
    // ACCESSOR (ATTRIBUT DINAMIS)
    // =====================================

    /**
     * Mengambil total jumlah diberi/keluar untuk item obat ini
     */
    public function getJumlahDiberiAttribute()
    {
        // Ambil obat_id dari stok item permintaan saat ini
        $obatId = $this->stok->obat_id ?? null;

        if (!$obatId) {
            return 0;
        }

        // Hitung sum('jumlah') dari tabel transaksi_keluar
        return \App\Models\TransaksiKeluar::where('id_permintaan', $this->permintaan_id)
            ->whereHas('stok', function($q) use ($obatId) {
                $q->where('obat_id', $obatId);
            })
            ->sum('jumlah');
    }

    /**
     * Mengambil total nilai harga (jumlah * harga) yang dikeluarkan untuk item obat ini
     */
    public function getTotalHargaDiberiAttribute()
    {
        $obatId = $this->stok->obat_id ?? null;

        if (!$obatId) {
            return 0;
        }

        // Ambil semua data transaksi keluar yang cocok lalu kalkulasikan totalnya
        return \App\Models\TransaksiKeluar::where('id_permintaan', $this->permintaan_id)
            ->whereHas('stok', function($q) use ($obatId) {
                $q->where('obat_id', $obatId);
            })
            ->get()
            ->sum(function($transaksi) {
                return $transaksi->jumlah * $transaksi->harga;
            });
    }

}
