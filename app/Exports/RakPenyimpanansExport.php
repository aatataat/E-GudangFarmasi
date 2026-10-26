<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RakPenyimpanansExport implements FromCollection, WithHeadings
{
    protected $rak_penyimpanans;

    public function __construct($rak_penyimpanans)
    {
        $this->rak_penyimpanans = $rak_penyimpanans;
    }

    public function collection()
    {
        return $this->rak_penyimpanans->map(function ($rak_penyimpanan) {
            return [
                'Kode Rak' => $rak_penyimpanan->kode_rak,
                'Lokasi Rak' => $rak_penyimpanan->lokasi_rak,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Rak',
            'Lokasi Rak',
        ];
    }
}
