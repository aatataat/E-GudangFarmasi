<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SumberAnggaransExport implements FromCollection, WithHeadings
{
    protected $sumber_anggarans;

    public function __construct($sumber_anggarans)
    {
        $this->sumber_anggarans = $sumber_anggarans;
    }

    public function collection()
    {
        return $this->sumber_anggarans->map(function ($sumber_anggaran) {
            return [
                'Nama Anggaran' => $sumber_anggaran->nama_anggaran,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Anggaran',
        ];
    }
}
