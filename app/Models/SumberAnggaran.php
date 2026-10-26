<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SumberAnggaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model ini.
     *
     * @var string
     */
    protected $table = 'sumber_anggaran';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'nama_anggaran',
    ];

    public function transaksi_masuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'id_sumber_anggaran');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class, 'id_sumber_anggaran');
    }

    public function transaksi_keluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'id_sumber_anggaran');
    }
    
    
}
