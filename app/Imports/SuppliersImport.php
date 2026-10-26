<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Penting untuk membaca header

class SuppliersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Supplier([
            'nama_supplier' => $row['nama_supplier'], // Pastikan nama kolom di Excel cocok
            'alamat' => $row['alamat'],
            'no_hp' => $row['no_hp'],
        ]);
    }
}
