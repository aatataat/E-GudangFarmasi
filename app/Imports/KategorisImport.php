<?php

namespace App\Imports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Penting untuk membaca header

class KategorisImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Kategori([
            'nama_kategori' => $row['nama_kategori'], // Pastikan nama kolom di Excel cocok
        ]);
    }
}
