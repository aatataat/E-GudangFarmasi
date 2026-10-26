<?php

namespace App\Exports;

use App\Models\Stok;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StoksExport implements FromCollection, WithHeadings, WithMapping
{
    protected $stoks;

    // Konstruktor untuk menerima data yang akan diekspor
    public function __construct($stoks)
    {
        $this->stoks = $stoks;
    }

    // Mengambil koleksi data yang akan diekspor
    public function collection()
    {
        return $this->stoks;
    }

    // Mendefinisikan header kolom untuk file Excel
    public function headings(): array
    {
        return [
            'Nama Obat',
            'Nomor Batch',
            'Jumlah',
            'Harga',
            'Total',
            'Tanggal Kadaluarsa',
            'Jumlah Masuk',
            'Harga Masuk',
            'Total Masuk',
            'Jumlah Keluar',
            'Harga Keluar',
            'Total Keluar',
            'Sumber Anggaran',
            'Supplier',
            'Rak Penyimpanan',
        ];
    }

    // Memetakan setiap baris data ke format yang diinginkan
    public function map($stok): array
    {
        return [
            $stok->obat->nama_obat, // Mengambil nama obat dari relasi
            $stok->batch_number,
            $stok->jumlah,
            $stok->harga,
            $stok->total,
            $stok->tanggal_kadaluarsa,
            $stok->jumlah_masuk,
            $stok->harga_masuk,
            $stok->total_masuk,
            $stok->jumlah_keluar,
            $stok->harga_keluar,
            $stok->total_keluar,
            $stok->sumberAnggaran->nama_sumber_anggaran ?? '', // Mengambil nama sumber anggaran dari relasi
            $stok->supplier->nama_supplier ?? '', // Mengambil nama supplier dari relasi
            $stok->rakPenyimpanan->nama_rak ?? '', // Mengambil nama rak penyimpanan dari relasi
        ];
    }
}
