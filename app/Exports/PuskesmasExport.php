<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PuskesmasExport implements FromCollection, WithHeadings
{
    protected $puskesmas;

    public function __construct($puskesmas)
    {
        $this->puskesmas = $puskesmas;
    }

    public function collection()
    {
        return $this->puskesmas->map(function ($puskesmas) {
            return [
                'Nama Puskesmas' => $puskesmas->nama_puskesmas,
                'Alamat' => $puskesmas->nama_puskesmas,
                'Kecamatan' => $puskesmas->kecamatan->nama_kecamatan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Puskesmas',
            'Alamat',
            'Kecamatan',
        ];
    }
}
