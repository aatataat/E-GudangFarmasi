<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SatuansExport implements FromCollection, WithHeadings
{
    protected $satuans;

    public function __construct($satuans)
    {
        $this->satuans = $satuans;
    }

    public function collection()
    {
        return $this->satuans->map(function ($satuan) {
            return [
                'Nama Satuan' => $satuan->nama_satuan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Satuan',
        ];
    }
}
