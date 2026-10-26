<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'stok';

    protected $casts = [
        'tanggal_kadaluarsa' => 'date',
    ];

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'obat_id',
        'batch_number',
        'jumlah',
        'harga',
        'total',
        'tanggal_kadaluarsa',
        'jumlah_masuk',
        'harga_masuk',
        'total_masuk',
        'jumlah_keluar',
        'harga_keluar',
        'total_keluar',
        'id_sumber_anggaran',
        'id_supplier',
        'id_rak',
    ];

    /**
     * Mendefinisikan relasi ke model Obat.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    /**
     * Mendefinisikan relasi ke model SumberAnggaran.
     *
     *
     */
    public function sumberAnggaran(): BelongsTo
    {
        return $this->belongsTo(SumberAnggaran::class, 'id_sumber_anggaran');
    }

    /**
     * Mendefinisikan relasi ke model Supplier.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    /**
     * Mendefinisikan relasi ke model RakPenyimpanan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rak()
    {
        return $this->belongsTo(RakPenyimpanan::class, 'id_rak');
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'stok_id');
    }

    public function permintaanBarang()
    {
        return $this->hasMany(PermintaanBarang::class, 'stok_id');
    }
}
