<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KecamatansExport implements FromCollection, WithHeadings
{
    protected $kecamatans;

    public function __construct($kecamatans)
    {
        $this->kecamatans = $kecamatans;
    }

    public function collection()
    {
        return $this->kecamatans->map(function ($kecamatan) {
            return [
                'Nama Kecamatan' => $kecamatan->nama_kecamatan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Kecamatan',
        ];
    }
}
