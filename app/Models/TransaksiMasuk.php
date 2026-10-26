<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransaksiMasuk extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'transaksi_masuk';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no',
        'obat_id',
        'tanggal_masuk',
        'batch_number',
        'tanggal_kadaluarsa',
        'jumlah',
        'harga',
        'total',
        'id_sumber_anggaran',
        'id_supplier',
    ];

    /**
     * Atribut yang harus diubah menjadi tipe data Date.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_kadaluarsa' => 'date',
    ];

    /**
     * Mendefinisikan relasi "belongs to" ke model Obat.
     *
     * @return BelongsTo
     */
    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    /**
     * Mendefinisikan relasi "belongs to" ke model SumberAnggaran.
     *
     * @return BelongsTo
     */
    public function sumberAnggaran(): BelongsTo
    {
        return $this->belongsTo(SumberAnggaran::class, 'id_sumber_anggaran');
    }

    /**
     * Mendefinisikan relasi "belongs to" ke model Supplier.
     *
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

}
