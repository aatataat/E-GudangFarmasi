<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LapStokMinimum implements FromCollection, WithHeadings, WithMapping
{
    protected $obats;

    public function __construct($obats)
    {
        $this->obats = $obats;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->obats;
    }

    /**
     * @param mixed $obat
     * @return array
     */
    public function map($obat): array
    {
        return [
            $obat->nama_obat,
            $obat->stok,
            $obat->stok_minimum,
            $obat->satuan->nama_satuan,
            $obat->kategori->nama_kategori,
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Obat',
            'Stok Saat Ini',
            'Stok Minimum',
            'Satuan',
            'Kategori',
        ];
    }
}