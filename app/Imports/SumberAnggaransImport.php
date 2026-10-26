<?php

namespace App\Imports;

use App\Models\SumberAnggaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SumberAnggaransImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SumberAnggaran([
            'nama_anggaran' => $row['nama_anggaran'],
        ]);
    }
}
