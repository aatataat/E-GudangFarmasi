<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiKeluarsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transaksi_keluar; // Properti untuk menyimpan koleksi data batch obat

    public function __construct($transaksi_keluar)
    {
        $this->transaksi_keluar = $transaksi_keluar; // Menerima koleksi data dari Livewire component
    }

    /**
     * Mengembalikan koleksi data batch obat yang akan diekspor.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transaksi_keluar; // Mengembalikan koleksi data yang telah dimuat
    }

    /**
     * Memetakan setiap objek BatchObat menjadi array untuk baris Excel.
     * Ini digunakan untuk menentukan kolom mana yang akan diekspor dan bagaimana formatnya.
     *
     * @param mixed $transaksi_keluar Item batch obat dari koleksi.
     * @return array
     */
    public function map($transaksi_keluar): array
    {
        return [
            $transaksi_keluar->obat->nama_obat ?? '-',
            $transaksi_keluar->batch_number,
            $transaksi_keluar->tanggal_keluar,
            $transaksi_keluar->jumlah,
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
            'Nomor Batch',
            'Tanggal Keluar',
            'Jumlah',
        ];
    }
}
