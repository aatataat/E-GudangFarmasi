<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiMasuksExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transaksi_masuks;

    public function __construct($transaksi_masuks)
    {
        $this->transaksi_masuks = $transaksi_masuks;
    }

    /**
     * Mengembalikan koleksi data transaksi masuk yang akan diekspor.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transaksi_masuks;
    }

    /**
     * Memetakan setiap objek transaksi masuk menjadi array yang sesuai untuk baris Excel.
     * Ini akan mengubah objek Eloquent menjadi format yang dapat dibaca Excel.
     *
     * @param mixed $transaksi_masuk Objek transaksi masuk dari koleksi.
     * @return array
     */
    public function map($transaksi_masuk): array
    {
        return [
            $transaksi_masuk->obat->nama_obat ?? '-', // Mengakses nama obat dari relasi
            $transaksi_masuk->tanggal_masuk,
            $transaksi_masuk->batch_number,
            $transaksi_masuk->tanggal_kadaluarsa,
            $transaksi_masuk->jumlah,
            $transaksi_masuk->harga,
            $transaksi_masuk->total,
            $transaksi_masuk->sumberAnggaran->nama_anggaran ?? '-', // Mengakses nama sumber anggaran dari relasi
            $transaksi_masuk->supplier->nama_supplier ?? '-', // Mengakses nama supplier dari relasi
        ];
    }

    /**
     * Menentukan judul kolom (headings) untuk baris pertama di file Excel.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Obat',
            'Tanggal Masuk',
            'Nomor Batch',
            'Tanggal Kadaluarsa',
            'Jumlah',
            'Harga',
            'Total',
            'Sumber Anggaran',
            'Supplier',
        ];
    }
}
