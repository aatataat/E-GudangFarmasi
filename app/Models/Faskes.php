<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faskes extends Model
{
    use HasFactory;

    /**
     * Nama tabel database yang terhubung dengan Model.
     * Sudah diatur 'faskes' sesuai dengan skema Anda.
     *
     * @var string
     */
    protected $table = 'faskes';

    /**
     * Atribut yang boleh diisi (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_faskes',
        'alamat',
        'telp',
        'email',
        'nama_kepala',
        'nip_kepala',
        'nama_apoteker',
        'nip_apoteker',
    ];

    /**
     * Atribut yang seharusnya di-cast ke tipe data tertentu.
     * (Tidak ada yang spesifik saat ini selain timestamps)
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];
    
    /**
     * Relasi ke model 'Permintaan' (satu Faskes bisa memiliki banyak Permintaan).
     */
    public function permintaan()
    {
        // Berdasarkan skema, tabel 'permintaan' memiliki foreign key 'faskes_id'
        return $this->hasMany(Permintaan::class, 'faskes_id');
    }
    public function transaksi_keluar()
    {
        // Berdasarkan skema, tabel 'permintaan' memiliki foreign key 'faskes_id'
        return $this->hasMany(TransaksiKeluar::class, 'faskes_id');
    }


    public function users()
    {
        // Berdasarkan skema, tabel 'users' memiliki foreign key 'faskes_id'
        return $this->hasMany(User::class, 'faskes_id');
    }
    /**
     * Relasi ke model 'User' (satu Faskes bisa dimiliki oleh banyak User dengan role Faskes).
     */

}