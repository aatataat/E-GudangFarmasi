<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\DB;

class ObatChart extends Component
{
    public $tahun;

    public function mount()
    {
        // Default filter ke tahun sekarang
        $this->tahun = date('Y');
    }

    public function render()
    {
        // Query untuk mengambil 10 obat yang paling banyak keluar berdasarkan tahun
        $grafikData = DB::table('transaksi_keluar')
            ->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
            ->join('obat', 'stok.obat_id', '=', 'obat.id')
            ->select('obat.nama_obat', DB::raw('SUM(transaksi_keluar.jumlah) as total_jumlah'))
            ->whereYear('transaksi_keluar.tanggal_keluar', $this->tahun) // <--- Sudah diubah ke tanggal_keluar
            ->groupBy('obat.nama_obat')
            ->orderBy('total_jumlah', 'desc')
            ->limit(10)
            ->get();

        // Ambil daftar tahun unik dari database untuk pilihan di select filter
        $daftarTahun = DB::table('transaksi_keluar')
            ->selectRaw('YEAR(tanggal_keluar) as tahun') // <--- Sudah diubah ke tanggal_keluar
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Jika data kosong di tahun tersebut, berikan fallback agar chart tidak error
        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([date('Y')]);
        }

        return view('livewire.obat-chart', [
            'labels'       => $grafikData->pluck('nama_obat'),
            'data'         => $grafikData->pluck('total_jumlah'),
            'daftarTahun'  => $daftarTahun,
        ]);
    }
}