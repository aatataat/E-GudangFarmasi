<?php

namespace App\Imports;

use App\Models\TransaksiMasuk;
use App\Models\Obat;
use App\Models\SumberAnggaran;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class TransaksiMasuksImport implements ToModel, WithHeadingRow
{
    /**
     * Metode ini akan dipanggil untuk setiap baris data dari file Excel.
     * Baris data akan diberikan sebagai array asosiatif, di mana key-nya adalah nama header kolom.
     *
     * @param array $row Baris data dari Excel.
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Mencari ID obat berdasarkan 'nama_obat' dari Excel
        $obat = Obat::where('nama_obat', $row['nama_obat'] ?? null)->first();
        $id_obat = $obat ? $obat->id : null;

        // Mencari ID sumber anggaran berdasarkan 'sumber_anggaran' dari Excel
        $sumber_anggaran = SumberAnggaran::where('nama_anggaran', $row['sumber_anggaran'] ?? null)->first();
        $id_sumber_anggaran = $sumber_anggaran ? $sumber_anggaran->id : null;

        // Mencari ID supplier berdasarkan 'supplier' dari Excel
        $supplier = Supplier::where('nama_supplier', $row['supplier'] ?? null)->first();
        $id_supplier = $supplier ? $supplier->id : null;

        // Mengubah format tanggal dari Excel jika diperlukan
        $tanggal_masuk = null;
        if (isset($row['tanggal_masuk'])) {
            // Asumsi format tanggal di Excel adalah Y-m-d atau d/m/Y
            try {
                $tanggal_masuk = Carbon::parse($row['tanggal_masuk'])->format('Y-m-d');
            } catch (\Exception $e) {
                // Biarkan null jika format tidak valid
            }
        }

        $tanggal_kadaluarsa = null;
        if (isset($row['tanggal_kadaluarsa'])) {
            // Asumsi format tanggal di Excel adalah Y-m-d atau d/m/Y
            try {
                $tanggal_kadaluarsa = Carbon::parse($row['tanggal_kadaluarsa'])->format('Y-m-d');
            } catch (\Exception $e) {
                // Biarkan null jika format tidak valid
            }
        }

        // Jika salah satu relasi tidak ditemukan atau data kunci tidak ada,
        // kembalikan null agar baris ini dilewati.
        if (is_null($id_obat) || is_null($id_sumber_anggaran) || is_null($id_supplier)) {
            return null;
        }

        // Membuat instance baru dari model TransaksiMasuk dengan data yang dipetakan
        return new TransaksiMasuk([
            'obat_id' => $id_obat,
            'tanggal_masuk' => $tanggal_masuk,
            'batch_number' => $row['batch_number'] ?? null,
            'tanggal_kadaluarsa' => $tanggal_kadaluarsa,
            'jumlah' => $row['jumlah'] ?? 0,
            'harga' => $row['harga'] ?? 0,
            'total' => $row['total'] ?? 0,
            'id_sumber_anggaran' => $id_sumber_anggaran,
            'id_supplier' => $id_supplier,
        ]);
    }
}
