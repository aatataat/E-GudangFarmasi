<?php

namespace App\Imports;

use App\Models\Kecamatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Penting untuk membaca header

class KecamatansImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Kecamatan([
            'nama_kecamatan' => $row['nama_kecamatan'], // Pastikan nama kolom di Excel cocok
        ]);
    }
}
