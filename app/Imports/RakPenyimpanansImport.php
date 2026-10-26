<?php

namespace App\Imports;

use App\Models\RakPenyimpanan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Penting untuk membaca header

class RakPenyimpanansImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new RakPenyimpanan([
            'kode_rak' => $row['kode_rak'], // Pastikan nama kolom di Excel cocok
            'lokasi_rak' => $row['lokasi_rak'], // Pastikan nama kolom di Excel cocok
        ]);
    }
}
