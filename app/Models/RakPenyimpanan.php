<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakPenyimpanan extends Model
{
    use HasFactory;

    protected $table = 'rak_penyimpanan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_rak',
        'kode_rak',
        'lokasi',
    ];

    public function stok()
    {
        return $this->hasMany(Stok::class, 'id_rak');
    }
}
