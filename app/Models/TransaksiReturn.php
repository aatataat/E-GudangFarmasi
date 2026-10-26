<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiReturn extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit jika berbeda dari konvensi
    protected $table = 'transaksi_return';

    // Kolom-kolom yang diperbolehkan untuk diisi secara massal (mass assignment)
    protected $fillable = [
        'id_transaksi_keluar',
        'tanggal_return',
        'jumlah',
        'keterangan',
        'status_stok',
    ];

    // Kolom yang harus di-cast ke tipe data tertentu (misalnya, tanggal)
    protected $casts = [
        'tanggal_return' => 'date',
    ];

    /**
     * Relasi ke model TransaksiKeluar.
     * TransaksiReturn ini dimiliki oleh satu TransaksiKeluar.
     */
    public function transaksiKeluar(): BelongsTo
    {
        return $this->belongsTo(TransaksiKeluar::class, 'id_transaksi_keluar');
    }

}
