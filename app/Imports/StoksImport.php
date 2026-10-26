<?php

namespace App\Imports;

use App\Models\Stok;
use App\Models\Obat;
use App\Models\SumberAnggaran;
use App\Models\Supplier;
use App\Models\RakPenyimpanan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class StoksImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $tanggalKadaluarsa = null;
        if (isset($row['tanggal_kadaluarsa'])) {
            try {
                $tanggalKadaluarsa = Carbon::parse($row['tanggal_kadaluarsa'])->format('Y-m-d');
            } catch (\Exception $e) {
                // Tangani error jika format tanggal tidak valid, kembalikan null
                $tanggalKadaluarsa = null;
            }
        }

        // Mencari ID berdasarkan nama atau nomor yang diberikan di file Excel.
        // Nama kolom disesuaikan dengan file Excel Anda.
        $obat = Obat::where('nama_obat', $row['nama_obat'] ?? null)->first();
        $id_obat = $obat ? $obat->id : null;

        $sumberAnggaran = SumberAnggaran::where('nama_anggaran', $row['nama_anggaran'] ?? null)->first();
        $id_sumber_anggaran = $sumberAnggaran ? $sumberAnggaran->id : null;

        $supplier = Supplier::where('nama_supplier', $row['nama_supplier'] ?? null)->first();
        $id_supplier = $supplier ? $supplier->id : null;

        $rakPenyimpanan = RakPenyimpanan::where('kode_rak', $row['id_rak'] ?? null)->first();
        $id_rak = $rakPenyimpanan ? $rakPenyimpanan->id : null;

        // Jika ID obat tidak ditemukan, kembalikan null agar baris tidak diimpor.
        if (!$id_obat) {
            return null;
        }

        return new Stok([
            'obat_id'            => $id_obat,
            'batch_number'       => $row['batch_number'],
            'jumlah'             => $row['jumlah'],
            'harga'              => $row['harga'],
            'total'              => $row['total'],
            'tanggal_kadaluarsa' => $tanggalKadaluarsa,
            'jumlah_masuk'       => $row['jumlah_masuk'] ?? null,
            'harga_masuk'        => $row['harga_masuk'] ?? null,
            'total_masuk'        => $row['total_masuk'] ?? null,
            'jumlah_keluar'      => $row['jumlah_keluar'] ?? null,
            'harga_keluar'       => $row['harga_keluar'] ?? null,
            'total_keluar'       => $row['total_keluar'] ?? null,
            'id_sumber_anggaran' => $id_sumber_anggaran,
            'id_supplier'        => $id_supplier,   
            'id_rak'             => $id_rak,
        ]);
    }
}