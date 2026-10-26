<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori'
    ];

    public function obat()
    {
        return $this->hasMany(Obat::class, 'id_kategori');
    }

    // public function peralatanMedis()
    // {
    //     return $this->hasMany(PeralatanMedis::class, 'id_kategori');
    // }
}
