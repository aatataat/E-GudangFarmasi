<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuppliersExport implements FromCollection, WithHeadings
{
    protected $suppliers;

    public function __construct($suppliers)
    {
        $this->suppliers = $suppliers;
    }

    public function collection()
    {
        return $this->suppliers->map(function ($supplier) {
            return [
                'nama_supplier' => $supplier->nama_supplier,
                'Alamat' => $supplier->alamat,
                'No. HP' => $supplier->no_hp,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'nama_supplier',
            'Alamat',
            'No. HP',
        ];
    }
}
