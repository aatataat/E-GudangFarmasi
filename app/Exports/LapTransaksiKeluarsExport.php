<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

// Mengubah nama kelas untuk Transaksi Keluar
class LapTransaksiKeluarsExport implements FromCollection, WithHeadings, WithMapping
{
    // Menggunakan nama properti yang sesuai dengan Transaksi Keluar
    protected $transaksiKeluar;

    /**
     * @param \Illuminate\Support\Collection $transaksiKeluar Koleksi data TransaksiKeluar
     */
    public function __construct($transaksiKeluar)
    {
        $this->transaksiKeluar = $transaksiKeluar;
    }

    /**
     * Mengembalikan koleksi data TransaksiKeluar yang akan diekspor.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transaksiKeluar;
    }

    /**
     * Memetakan setiap objek TransaksiKeluar menjadi array untuk baris Excel.
     * Mengakses relasi: stok, sumberAnggaran, supplier, dan permintaan.
     *
     * @param mixed $transaksiKeluar Objek TransaksiKeluar dari koleksi.
     * @return array
     */
    public function map($transaksiKeluar): array
    {
        // Asumsi: Model Stok memiliki kolom 'nama_item' atau 'nama'.
        $namaItem = $transaksiKeluar->stok->nama_item ?? $transaksiKeluar->stok->nama ?? 'Item Tidak Diketahui';
        
        // Asumsi: Model Permintaan memiliki kolom 'kode_permintaan'
        $kodePermintaan = $transaksiKeluar->permintaan->kode_permintaan ?? 'N/A';

        return [
            // 1. Tanggal Keluar
            $transaksiKeluar->tanggal_keluar->format('Y-m-d'),

            // 2. Nama Item/Stok (dari relasi stok)
            $namaItem,

            // 3. Nomor Batch
            $transaksiKeluar->batch_number,

            // 4. Tanggal Kadaluarsa (Menggunakan null coalescing operator untuk memastikan format)
            $transaksiKeluar->tanggal_kadaluarsa?->format('Y-m-d') ?? '-',

            // 5. Jumlah
            $transaksiKeluar->jumlah,

            // 6. Harga Satuan
            $transaksiKeluar->harga,

            // 7. Total Harga
            $transaksiKeluar->total,

            // 8. Sumber Anggaran
            $transaksiKeluar->sumberAnggaran->nama_sumber ?? 'Anggaran Tidak Diketahui', 

            // 9. Supplier
            $transaksiKeluar->supplier->nama_supplier ?? 'Supplier Tidak Diketahui', 

            // 10. Kode Permintaan (dari relasi permintaan)
            $kodePermintaan,
        ];
    }

    /**
     * Menentukan judul kolom (headings) untuk baris pertama di file Excel.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal Keluar',
            'Nama Item/Stok',
            'Nomor Batch',
            'Tanggal Kadaluarsa',
            'Jumlah Keluar (Unit)',
            'Harga Satuan',
            'Total Harga',
            'Sumber Anggaran',
            'Supplier',
            'Kode Permintaan', // Kolom baru dari relasi permintaan
        ];
    }
}
