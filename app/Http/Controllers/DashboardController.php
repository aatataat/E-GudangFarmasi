<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk akses user login
use App\Models\User;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiReturn;
use App\Models\Stok;
use App\Models\Permintaan;

class DashboardController extends Controller
{
    public function index()
    {
        Artisan::call('notifications:check-daily');
        
        $user = Auth::user(); // Ambil data user yang sedang login

        // 1. Metrik Umum (Tetap global atau sesuaikan jika perlu)
        $totalTransaksiMasuk = TransaksiMasuk::count(); 
        $totalStok = Stok::where('jumlah', '>', 0)->count();
        $totalTransaksiKeluar = TransaksiKeluar::count();
        $totalTransaksiReturn = TransaksiReturn::count();
        $totalPengguna = User::count();

        // 2. Logika Filter Permintaan Berdasarkan Role
        $queryPermintaan = Permintaan::query();

        // Jika user yang login adalah role 'Faskes', hanya tampilkan milik faskes mereka sendiri
        if ($user->role === 'Faskes') {
            $queryPermintaan->where('faskes_id', $user->faskes_id);
        }

        // Hitung total berdasarkan filter di atas
        $permintaanDiajukan = (clone $queryPermintaan)->where('status', 'DIAJUKAN')->count();
        $permintaanDiterima = (clone $queryPermintaan)->where('status', 'DITERIMA')->count();
        $permintaanDitolak = (clone $queryPermintaan)->where('status', 'DITOLAK')->count();

        return view('dashboard.dashboard', [
            'totalTransaksiMasuk'   => $totalTransaksiMasuk,
            'totalStok'             => $totalStok,
            'totalPengguna'         => $totalPengguna,
            'totalTransaksiKeluar'  => $totalTransaksiKeluar,
            'totalTransaksiReturn'  => $totalTransaksiReturn,
            
            'permintaanDiajukan'    => $permintaanDiajukan,
            'permintaanDiterima'    => $permintaanDiterima,
            'permintaanDitolak'     => $permintaanDitolak,
        ]);
    }
}