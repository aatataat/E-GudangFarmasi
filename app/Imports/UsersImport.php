<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Faskes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // 1. Validasi Baris Kosong (Mencegah error 'nama' cannot be null)
        // Jika kolom nama di Excel kosong, lewati baris ini
        if (!isset($row['nama']) || empty(trim($row['nama']))) {
            return null;
        }

        // 2. Mencari ID Faskes dengan perbandingan yang lebih fleksibel
        $namaFaskes = isset($row['fasilitas_kesehatan']) ? trim($row['fasilitas_kesehatan']) : null;

        $faskes = Faskes::whereRaw('LOWER(nama_faskes) = ?', [strtolower($namaFaskes)])->first();

        if (!$faskes) {
            // Opsional: Log jika faskes tidak ditemukan untuk debugging
            \Log::info("Faskes tidak ditemukan: " . $namaFaskes);
        }

        $faskes_id = $faskes ? $faskes->id : null;

        // 3. Membuat instance User baru
        return new User([
            'nama'      => trim($row['nama']),
            'email'     => $row['email'],
            'role'      => $row['role'] ?? 'User',
            'faskes_id' => $faskes_id,
            // Menggunakan password dari Excel, jika kosong gunakan default
            'password'  => Hash::make($row['password'] ?? 'password123'),
        ]);
    }

    /**
     * Memastikan format email benar agar tidak error saat insert
     */
}
