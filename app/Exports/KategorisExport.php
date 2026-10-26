<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KategorisExport implements FromCollection, WithHeadings
{
    protected $kategoris;

    public function __construct($kategoris)
    {
        $this->kategoris = $kategoris;
    }

    public function collection()
    {
        return $this->kategoris->map(function ($kategori) {
            return [
                'Nama Kategori' => $kategori->nama_kategori,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Kategori',
        ];
    }
}
