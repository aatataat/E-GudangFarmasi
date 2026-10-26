<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = [
        'nama_supplier',
        'alamat',
        'no_hp',
    ];

    public function obat()
    {
        return $this->hasMany(Obat::class, 'id_supplier');
    }

    // public function peralatanMedis()
    // {
    //     return $this->hasMany(PeralatanMedis::class, 'id_supplier');
    // }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'id_supplier');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class, 'id_supplier');
    }

    public function transaksi_keluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'id_supplier');
    }
    
}
