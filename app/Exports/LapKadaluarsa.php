<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon; // <-- Tambahkan ini

class LapKadaluarsa implements FromCollection, WithHeadings, WithMapping
{
    protected $batchObats;

    public function __construct($batchObats)
    {
        $this->batchObats = $batchObats;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->batchObats;
    }

    /**
     * @param mixed $batchObat
     * @return array
     */
    public function map($batchObat): array
    {
        // Hitung selisih hari kadaluarsa
        $hariKadaluarsa = floor(Carbon::parse($batchObat->tanggal_kadaluarsa)->diffInDays(Carbon::now(), false));

        return [
            $batchObat->obat->nama_obat ?? '-',
            $batchObat->batch_number,
            $batchObat->tanggal_masuk,
            $batchObat->tanggal_kadaluarsa,
            $hariKadaluarsa, // <-- Tambahkan baris ini
            $batchObat->jumlah,
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Obat', // <-- Ubah urutan jika perlu
            'Batch Number',
            'Tanggal Masuk',
            'Tanggal Kadaluarsa',
            'Sudah Kadaluarsa', // <-- Tambahkan baris ini
            'Jumlah',
        ];
    }
}