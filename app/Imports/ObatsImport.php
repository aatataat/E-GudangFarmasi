<?php

namespace App\Imports;

use App\Models\Obat;      // Ganti dari Puskesmas menjadi Obat
use App\Models\Kategori;  // Import Model Kategori
use App\Models\Satuan;    // Import Model Satuan
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Penting untuk membaca header dari baris pertama Excel

class ObatsImport implements ToModel, WithHeadingRow
{
    /**
     * Metode ini akan dipanggil untuk setiap baris data dari file Excel.
     * Baris data akan diberikan sebagai array asosiatif, di mana key-nya adalah nama header kolom.
     *
     * @param array $row Baris data dari Excel.
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Mencari ID kategori berdasarkan 'nama_kategori' dari Excel.
        // Jika tidak ditemukan, akan mengembalikan null.
        $kategori = Kategori::where('nama_kategori', $row['kategori'] ?? null)->first();
        $id_kategori = $kategori ? $kategori->id : null;

        // Mencari ID satuan berdasarkan 'nama_satuan' dari Excel.
        $satuan = Satuan::where('nama_satuan', $row['satuan'] ?? null)->first();
        $id_satuan = $satuan ? $satuan->id : null;

        // Membuat instance baru dari model Obat dengan data yang dipetakan.
        // Pastikan nama header kolom di file Excel (misalnya, 'nama_obat', 'kategori', 'jenis')
        // sesuai dengan key array $row di bawah.
        return new Obat([
            'nama_obat'   => $row['nama_obat'] ?? null,   // Asumsikan ada kolom 'nama_obat' di Excel
            'id_kategori' => $id_kategori,
            'id_satuan'   => $id_satuan,
            // Tambahkan kolom lain jika ada, misalnya 'deskripsi' => $row['deskripsi']
        ]);
    }
}
