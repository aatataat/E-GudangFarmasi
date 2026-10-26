<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; // Tambahkan ini untuk memetakan kolom

class ObatsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $obats; // Properti untuk menyimpan koleksi obat

    public function __construct($obats) // Konstruktor untuk menerima data obat
    {
        $this->obats = $obats; // Inisialisasi properti $obats
    }

    /**
     * Mengembalikan koleksi data obat yang akan diekspor.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->obats; // Langsung mengembalikan koleksi yang diterima
    }

    /**
     * Memetakan setiap objek obat menjadi array yang sesuai untuk baris Excel.
     * Ini akan mengubah objek Eloquent menjadi format yang dapat dibaca Excel.
     *
     * @param mixed $obat Objek obat dari koleksi.
     * @return array
     */
    public function map($obat): array
    {
        return [
            $obat->nama_obat,
            $obat->kategori->nama_kategori ?? '-', // Mengakses nama kategori, menggunakan '-' jika tidak ada
            $obat->satuan->nama_satuan ?? '-',     // Mengakses nama satuan, menggunakan '-' jika tidak ada
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
            'kategori',
            'Satuan',
        ];
    }
}
