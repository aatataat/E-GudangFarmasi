<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; 
use App\Models\Faskes; 

class FaskesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $faskes; // Properti untuk menyimpan koleksi Faskes

    public function __construct($faskes) // Konstruktor untuk menerima data Faskes
    {
        // Koleksi Faskes yang sudah di-query dari Livewire Component
        $this->faskes = $faskes; 
    }

    /**
     * Mengembalikan koleksi data Faskes yang akan diekspor.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->faskes; 
    }

    /**
     * Memetakan setiap objek Faskes menjadi array baris Excel.
     *
     * @param mixed $faskes Objek Faskes dari koleksi.
     * @return array
     */
    public function map($faskes): array
    {
        return [
            $faskes->nama_faskes,
            $faskes->alamat,
            $faskes->telp,
            $faskes->email,
            $faskes->nama_kepala,
            $faskes->nip_kepala,
            $faskes->nama_apoteker,
            $faskes->nip_apoteker,
        ];
    }

    /**
     * Menentukan judul kolom (headings) untuk baris pertama di file Excel.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Faskes',
            'Alamat',
            'Telepon',
            'Email',
            'Nama Kepala',
            'NIP Kepala',
            'Nama Apoteker',
            'NIP Apoteker',
        ];
    }
}
