<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class LapTransaksiReturnsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;
    protected $rowNumber = 0;

    /**
     * Terima data dari komponen Livewire
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Mengembalikan data yang akan diekspor
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Header Tabel Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Return',
            'Nama Obat',
            'Batch Number',
            'Tanggal Kadaluarsa',
            'Jumlah',
            'Status Stok',
        ];
    }

    /**
     * Mapping data agar sesuai dengan urutan kolom di view Anda
     */
    public function map($item): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            Carbon::parse($item->tanggal_return)->format('d F Y'),
            $item->transaksiKeluar->stok->obat->nama_obat ?? '-',
            $item->transaksiKeluar->batch_number ?? '-',
            isset($item->transaksiKeluar->tanggal_kadaluarsa) 
                ? Carbon::parse($item->transaksiKeluar->tanggal_kadaluarsa)->format('d F Y') 
                : '-',
            $item->jumlah,
            $item->status_stok,
        ];
    }
}