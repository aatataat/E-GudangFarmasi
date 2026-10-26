<?php

namespace App\Imports;

use App\Models\Faskes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Penting untuk membaca header

class FaskesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Faskes([
            'nama_faskes' => $row['nama_faskes'], // Pastikan nama kolom di Excel cocok
            'alamat' => $row['alamat'], // Pastikan nama kolom di Excel cocok
            'telp' => $row['telp'], // Pastikan nama kolom di Excel cocok
            'email' => $row['email'], // Pastikan nama kolom di Excel cocok
            'nama_kepala' => $row['nama_kepala'], // Pastikan nama kolom di Excel cocok
            'nip_kepala' => $row['nip_kepala'], // Pastikan nama kolom di Excel cocok
            'nama_apoteker' => $row['nama_apoteker'], // Pastikan nama kolom di Excel cocok
            'nip_apoteker' => $row['nip_apoteker'], // Pastikan nama kolom di Excel cocok
        ]);
    }
}
