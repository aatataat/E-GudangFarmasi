<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

// Ubah nama kelas menjadi TransaksiMasukExport
class LapTransaksiMasuksExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transaksiMasuk; // Ubah properti untuk menyimpan koleksi transaksi

    public function __construct($transaksiMasuk) // Terima koleksi TransaksiMasuk
    {
        $this->transaksiMasuk = $transaksiMasuk; // Inisialisasi properti
    }

    /**
     * Mengembalikan koleksi data TransaksiMasuk yang akan diekspor.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transaksiMasuk; // Mengembalikan koleksi yang diterima
    }

    /**
     * Memetakan setiap objek TransaksiMasuk menjadi array yang sesuai untuk baris Excel.
     * Menggunakan relasi yang telah didefinisikan di model TransaksiMasuk.
     *
     * @param mixed $transaksi Objek TransaksiMasuk dari koleksi.
     * @return array
     */
    public function map($transaksi): array
    {
        // Mengakses kolom dari model TransaksiMasuk dan relasinya
        return [
            $transaksi->no,
            $transaksi->obat->nama_obat ?? 'Data Obat Tidak Ada', // Akses nama obat dari relasi
            $transaksi->tanggal_masuk,
            $transaksi->batch_number,
            $transaksi->tanggal_kadaluarsa,
            $transaksi->jumlah,
            $transaksi->harga,
            $transaksi->total,
            $transaksi->sumberAnggaran->nama_sumber ?? 'Anggaran Tidak Diketahui', // Akses nama sumber anggaran
            $transaksi->supplier->nama_supplier ?? 'Supplier Tidak Diketahui', // Akses nama supplier
        ];
    }

    /**
     * Menentukan judul kolom (headings) untuk baris pertama di file Excel.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nomor Transaksi',
            'Nama Obat',
            'Tanggal Masuk',
            'Nomor Batch',
            'Tanggal Kadaluarsa',
            'Jumlah (Unit)',
            'Harga Satuan',
            'Total Harga',
            'Sumber Anggaran',
            'Supplier',
        ];
    }
}