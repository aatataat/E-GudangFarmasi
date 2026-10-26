<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class LapStokObat implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $stoks;

    public function __construct(Collection $stoks)
    {
        $this->stoks = $stoks;
    }

    public function collection()
    {
        return $this->stoks;
    }

    /**
     * Mendefinisikan baris judul (heading) untuk file Excel.
     * Catatan: Excel tidak mendukung header berlapis seperti HTML, jadi kita buat header datar.
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Obat',
            'Satuan',
            'Batch Number',
            'Tanggal Kadaluarsa',
            'Penerimaan - Jumlah Satuan',
            'Penerimaan - Nilai Satuan',
            'Penerimaan - Total',
            'Pengeluaran - Jumlah Satuan',
            'Pengeluaran - Nilai Satuan',
            'Pengeluaran - Total',
            'Sisa Stok', // Saldo Akhir
            'Sumber Anggaran',
            'Nama Penyedia',
        ];
    }

    /**
     * Mapping data per kolom dari objek model ke array.
     */
    public function map($stok): array
    {
        // Format angka dan tanggal
        $nilai_satuan = $stok->harga;
        $total_nilai = $stok->total;
        $tgl_kadaluarsa = \Carbon\Carbon::parse($stok->tanggal_kadaluarsa)->format('d-m-Y');
        
        // ASUMSI:
        // Karena data ini adalah "Stok" (saldo akhir),
        // kita menggunakan nilai stok saat ini sebagai referensi Penerimaan/Saldo Awal.
        // Data Pengeluaran akan di-nol-kan kecuali Anda memiliki relasi historis pergerakan.
        $penerimaan_jumlah = $stok->jumlah;
        $pengeluaran_jumlah = 0; // Asumsi: tidak ada riwayat pergerakan

        return [
            $stok->id,
            $stok->obat->nama_obat ?? '-',
            $stok->obat->satuan ?? '-',
            $stok->batch_number,
            $tgl_kadaluarsa,
            // Penerimaan
            $penerimaan_jumlah, // Jumlah Satuan Masuk
            $nilai_satuan, // Nilai Satuan Masuk
            $total_nilai, // Total Nilai Masuk
            // Pengeluaran
            $pengeluaran_jumlah, // Jumlah Satuan Keluar (0)
            0, // Nilai Satuan Keluar (0)
            0, // Total Keluar (0)
            // Sisa Stok
            $stok->jumlah, // Sisa Stok
            $stok->sumberAnggaran->nama_sumber ?? '-',
            $stok->supplier->nama_supplier ?? '-',
        ];
    }
}