<?php

namespace App\Imports;

use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Penting untuk membaca header

class SatuansImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Satuan([
            'nama_satuan' => $row['nama_satuan'], // Pastikan nama kolom di Excel cocok
        ]);
    }
}
