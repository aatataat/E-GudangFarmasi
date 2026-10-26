<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notifikasi;
use App\Models\Stok;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CheckDrugNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for expiring and low-stock drugs and creates notifications efficiently.';

    /**
     * Ambang batas stok minimum (bisa dipindahkan ke file konfigurasi untuk fleksibilitas).
     *
     * @var int
     */
    protected $stockThreshold = 100;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan notifikasi obat...');

        // 1. Notifikasi Kedaluwarsa Mendekat
        $this->checkUpcomingExpiryNotifications();

        // 2. Notifikasi Obat Sudah Kedaluwarsa
        $this->checkExpiredNotifications();

        // 3. Notifikasi Stok Minimum
        $this->checkLowStockNotifications();

        $this->info('Pengecekan notifikasi obat selesai.');
    }

    /**
     * Pengecekan Notifikasi Kedaluwarsa yang Mendekat.
     */
    protected function checkUpcomingExpiryNotifications()
    {
        // Menggunakan Eager Loading (with('obat')) agar lebih efisien (mengatasi N+1)
        $batchesToNotifyExpiry = Stok::whereDate('tanggal_kadaluarsa', '<=', Carbon::now()->addDays(90))
            ->whereDate('tanggal_kadaluarsa', '>=', Carbon::now()->startOfDay()) // Pastikan belum kadaluarsa
            ->with('obat')
            ->get();

        foreach ($batchesToNotifyExpiry as $batch) {
            // Pengecekan keamanan relasi (jika obat_id tidak ada di tabel Obat)
            if (!$batch->obat) {
                $this->warn("Batch ID {$batch->id} memiliki referensi obat yang hilang. Melewati notifikasi.");
                continue;
            }

            $daysUntilExpiry = now()->startOfDay()->diffInDays(Carbon::parse($batch->tanggal_kadaluarsa)->startOfDay());
            $roundedDays = round($daysUntilExpiry);

            $pesan = "Obat {$batch->obat->nama_obat} (Batch: {$batch->batch_number}) akan KADALUARSA dalam {$roundedDays} hari (Tanggal: " . Carbon::parse($batch->tanggal_kadaluarsa)->translatedFormat('d F Y') . ").";

            // Cek duplikasi
            $existingNotif = Notifikasi::where('tipe', 'lainnya')
                ->where('pesan', $pesan)
                ->where(function ($query) {
                    $query->where('status', 'Belum Dibaca')
                        ->orWhereDate('tanggal', Carbon::today());
                })
                ->first();

            if (!$existingNotif) {
                Notifikasi::create([
                    'pesan' => $pesan,
                    'tipe' => 'lainnya',
                    'status' => 'Belum Dibaca',
                    'tanggal' => now(),
                ]);
            }
        }
        $this->info('  -> Pengecekan kedaluwarsa mendekat selesai.');
    }

    /**
     * Pengecekan Notifikasi Obat Sudah Kedaluwarsa.
     */
    protected function checkExpiredNotifications()
    {
        // Menggunakan Eager Loading (with('obat'))
        $batchesToNotifyExpired = Stok::whereDate('tanggal_kadaluarsa', '<=', Carbon::now()->startOfDay())
            ->with('obat')
            ->get();

        foreach ($batchesToNotifyExpired as $batch) {
            // Pengecekan keamanan relasi
            if (!$batch->obat) {
                $this->warn("Batch ID {$batch->id} memiliki referensi obat yang hilang. Melewati notifikasi.");
                continue;
            }

            $pesan = "Obat {$batch->obat->nama_obat} (Batch: {$batch->batch_number}) telah KADALUARSA pada tanggal " . Carbon::parse($batch->tanggal_kadaluarsa)->translatedFormat('d F Y') . ".";

            // Cek duplikasi
            $existingNotif = Notifikasi::where('tipe', 'kadaluarsa')
                ->where('pesan', $pesan)
                ->where(function ($query) {
                    $query->where('status', 'Belum Dibaca')
                        ->orWhereDate('tanggal', Carbon::today());
                })
                ->first();

            if (!$existingNotif) {
                Notifikasi::create([
                    'pesan' => $pesan,
                    'tipe' => 'kadaluarsa',
                    'status' => 'Belum Dibaca',
                    'tanggal' => now(),
                ]);
            }
        }
        $this->info('  -> Pengecekan obat sudah kedaluwarsa selesai.');
    }

    /**
     * Pengecekan Notifikasi Stok Minimum.
     */
    protected function checkLowStockNotifications()
    {
        // 1. Ambil data stok, kelompokkan berdasarkan obat_id, dan hitung total jumlahnya
        // Kita tetap melakukan Eager Loading 'obat' untuk mengambil nama obatnya secara efisien
        $lowStokData = \App\Models\Stok::with('obat')
            ->select('obat_id', DB::raw('SUM(jumlah) as total_stok'))
            ->groupBy('obat_id')
            ->get();

        foreach ($lowStokData as $data) {
            // Pastikan relasi obat tersedia agar tidak error saat mengambil nama_obat
            if (!$data->obat) {
                continue;
            }

            $obat = $data->obat;
            $totalStok = $data->total_stok;

            // Menggunakan Accessor dinamis dari model Obat yang sudah kita buat sebelumnya
            $batasMinimum = $obat->batas_minimum;

            // 2. Cek apakah total stok dari batch-batch obat tersebut sudah kritis
            if ($totalStok <= $batasMinimum) {
                $namaObat = $obat->nama_obat; // Nama diambil melalui relasi di data stok
                $tipe = 'Stok Minimum';

                if ($totalStok > 0) {
                    $pesan = "Stok obat '{$namaObat}' menipis ({$totalStok} unit), sudah menyentuh batas minimum ({$batasMinimum} unit). Segera lakukan pemesanan ulang.";
                } else {
                    $pesan = "Stok obat '{$namaObat}' di gudang habis (0 unit). Segera lakukan pemesanan ulang.";
                }

                // 3. Pengecekan duplikasi agar tidak spam jika status masih 'Belum Dibaca'
                $existingNotif = \App\Models\Notifikasi::where('tipe', $tipe)
                    ->where('pesan', 'LIKE', "Stok obat '{$namaObat}'%")
                    ->where(function ($query) {
                        // Jangan buat notif baru jika:
                        // Ada yang statusnya 'Belum Dibaca' ATAU sudah ada notif yang dibuat HARI INI
                        $query->where('status', 'Belum Dibaca')
                            ->orWhereDate('tanggal', \Carbon\Carbon::today());
                    })
                    ->first();

                if (!$existingNotif) {
                    \App\Models\Notifikasi::create([
                        'pesan' => $pesan,
                        'tipe' => $tipe,
                        'status' => 'Belum Dibaca',
                        'tanggal' => now(),
                    ]);
                }
            }
        }
        $this->info('  -> Pengecekan stok minimum berdasarkan data stok selesai.');
    }
}
