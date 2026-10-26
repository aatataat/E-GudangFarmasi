<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'id_kategori',
        'id_satuan',
    ];

    // =======================
    // Relasi ke Master Data
    // =======================

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class, 'obat_id');
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'obat_id');
    }

    
    // =======================
    // Accessors (Logic Perhitungan)
    // =======================

    /**
     * 1. Menghitung Total Stok dari semua Batch yang ada di tabel stok.
     */
    public function getTotalStokAttribute()
    {
        return $this->stok->sum('jumlah');
    }

    /**
     * 2. Menghitung Rata-rata Pemakaian (30 hari terakhir).
     * Diambil dari tabel transaksi_keluar yang berelasi dengan batch stok obat ini.
     */
    public function getRataRataPemakaianAttribute()
    {
        $tglMulai = now()->subDays(30);

        $totalKeluar = DB::table('transaksi_keluar')
            ->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
            ->where('stok.obat_id', $this->id)
            ->where('transaksi_keluar.tanggal_keluar', '>=', $tglMulai)
            ->sum('transaksi_keluar.jumlah');

        // Jika tidak ada transaksi, kembalikan 0 untuk menghindari pembagian nol
        return $totalKeluar > 0 ? $totalKeluar / 30 : 0;
    }

    /**
     * 3. Menghitung Batas Stok Minimum.
     * Menggunakan asumsi Lead Time 3 hari dan Safety Factor 20% (1.2).
     */
    /**
     * 3. Menghitung Batas Stok Minimum (Rumus Baru berdasarkan Gambar).
     * SS = Lead Time * Rata-rata Penggunaan (CA)
     * Smin = (Lead Time * CA) + SS = 2 * SS
     */
    public function getBatasMinimumAttribute()
    {
        $leadTime = 11; // Nilai LT Anda tetap 11 hari

        // SS = LT * CA
        $safetyStock = $leadTime * $this->rata_rata_pemakaian;

        // Smin = 2 * SS
        $perhitungan = 2 * $safetyStock;

        // Menggunakan ceil agar hasil dibulatkan ke atas (misal 1.2 menjadi 2)
        // supaya stok pengaman selalu tersedia dalam satuan utuh.
        return ceil($perhitungan);
    }

    /**
     * 4. Menentukan apakah stok sudah kritis atau belum.
     */
    public function getIsKritisAttribute()
    {
        return $this->total_stok <= $this->batas_minimum;
    }
}
