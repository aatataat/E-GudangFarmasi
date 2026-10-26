<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransaksiKeluar extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model
    protected $table = 'transaksi_keluar';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'tanggal_keluar',
        'id_stok',
        'jumlah',
        'harga',
        'total',
        'batch_number',
        'tanggal_kadaluarsa',
        'id_sumber_anggaran',
        'id_supplier',
        'id_permintaan'
    ];

    // Kolom tanggal yang akan diubah menjadi instance Carbon
    protected $casts = [
        'tanggal_keluar' => 'date',
        'tanggal_kadaluarsa' => 'date',
    ];

    /**
     * Relasi: Satu transaksi keluar memiliki satu stok.
     */
    public function stok()
    {
        // Menggunakan foreign key 'id_stok' di tabel transaksi_keluar
        // dan local key 'id' di tabel stok
        return $this->belongsTo(Stok::class, 'id_stok', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function sumberAnggaran():BelongsTo
    {
        return $this->belongsTo(SumberAnggaran::class, 'id_sumber_anggaran');
    }

    public function returns(): HasMany
    {
        // 'TransaksiReturn' adalah Model return yang baru kita buat
        // 'id_transaksi_keluar' adalah Foreign Key di tabel 'transaksi_return'
        return $this->hasMany(TransaksiReturn::class, 'id_transaksi_keluar');
    }
    
    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(Permintaan::class, 'id_permintaan');
    }
    
    public function faskes(): BelongsTo
    {
        return $this->belongsTo(Faskes::class, 'faskes_id');
    }
    
}
