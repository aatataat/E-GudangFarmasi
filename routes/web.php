<?php
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Index;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// ===========================================
// Route Halaman Publik (tanpa autentikasi)
// ===========================================
Route::get('/', function () {
    return view('landing.home.index');
})->name('homepage');

Route::view('landing/home', 'landing.home.index')->name('landing.home.index');
Route::view('landing/visi-misi', 'landing.visi-misi.index')->name('landing.visi-misi.index');
Route::view('landing/struktur-organisasi', 'landing.struktur-organisasi.index')->name('landing.struktur-organisasi.index');
Route::view('landing/maklumat', 'landing.maklumat.index')->name('landing.maklumat.index');
Route::view('landing/about', 'landing.about.index')->name('landing.about.index');
Route::view('landing/pengaduan', 'landing.pengaduan.index')->name('landing.pengaduan.index');
Route::view('landing/kontak', 'landing.kontak.index')->name('landing.kontak.index');

// Informasi
Route::view('landing/informasi', 'landing.informasi.index')->name('landing.informasi.index');
Route::view('landing/informasi/iso2025', 'landing.informasi.iso2025.index')->name('landing.informasi.iso2025.index');

Auth::routes(['login' => false, 'register' => false]);

route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Tambahkan kode ini ke dalam web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});


// Route untuk halaman dashboard umum, bisa diakses semua pengguna yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::view('notifikasi', 'notifikasi.index')->name('notifikasi.index');
    Route::get('/cek-stok-manual', [NotifikasiController::class, 'cekStokKritis'])->name('notif.cek-stok');
});

// Route untuk testing notifikasi Telegram (bisa dihapus setelah testing selesai)
// Route::get('/test-telegram', [App\Http\Controllers\NotifikasiController::class, 'cekStokKritis']);
// Route::get('/cek-stok-sekarang', [NotifikasiController::class, 'cekStokKritis']);

// ---

// ===========================================
// Route Halaman Super Admin (Hanya Super Admin)
// ===========================================
Route::middleware(['auth', 'superadmin.access'])->group(function () {
    Route::view('superadmin/user', 'superadmin.user.index')->name('superadmin.user.index');
    Route::view('superadmin/supplier', 'superadmin.supplier.index')->name('superadmin.supplier.index');
    Route::view('superadmin/kecamatan', 'superadmin.kecamatan.index')->name('superadmin.kecamatan.index');
    Route::view('superadmin/faskes', 'superadmin.faskes.index')->name('superadmin.faskes.index');
    Route::view('superadmin/kategori', 'superadmin.kategori.index')->name('superadmin.kategori.index');
    Route::view('superadmin/satuan', 'superadmin.satuan.index')->name('superadmin.satuan.index');
    Route::view('superadmin/obat', 'superadmin.obat.index')->name('superadmin.obat.index');
    Route::view('superadmin/sumber_anggaran', 'superadmin.sumber_anggaran.index')->name('superadmin.sumber_anggaran.index');
    Route::view('superadmin/rak_penyimpanan', 'superadmin.rak_penyimpanan.index')->name('superadmin.rak_penyimpanan.index');
    Route::view('superadmin/stok', 'superadmin.stok.index')->name('superadmin.stok.index');
    Route::view('superadmin/permintaan', 'superadmin.permintaan.index')->name('superadmin.permintaan.index');
    Route::view('superadmin/transaksi_masuk', 'superadmin.transaksi_masuk.index')->name('superadmin.transaksi_masuk.index');
    Route::view('superadmin/transaksi_keluar', 'superadmin.transaksi_keluar.index')->name('superadmin.transaksi_keluar.index');
    Route::view('superadmin/transaksi_return', 'superadmin.transaksi_return.index')->name('superadmin.transaksi_return.index');


    Route::view('superadmin/LapTransaksiMasuk', 'superadmin.Laptransaksi_masuk.index')->name('superadmin.Laptransaksi_masuk.index');
    Route::view('superadmin/LapPermintaan', 'superadmin.LapPermintaan.index')->name('superadmin.LapPermintaan.index');
    Route::view('superadmin/LapTransaksiKeluar', 'superadmin.Laptransaksi_keluar.index')->name('superadmin.Laptransaksi_keluar.index');
    Route::view('superadmin/LapStok', 'superadmin.LapStok.index')->name('superadmin.LapStok.index');
    Route::view('superadmin/LapAkanKadaluarsa', 'superadmin.LapAkanKadaluarsa.index')->name('superadmin.LapAkanKadaluarsa.index');
    Route::view('superadmin/LapKadaluarsa', 'superadmin.LapKadaluarsa.index')->name('superadmin.LapKadaluarsa.index');
    Route::view('superadmin/LapStokMinimum', 'superadmin.LapStokMinimum.index')->name('superadmin.LapStokMinimum.index');
    Route::view('superadmin/Laptransaksi_return', 'superadmin.Laptransaksi_return.index')->name('superadmin.Laptransaksi_return.index');
    Route::view('superadmin/LapStokObat', 'superadmin.LapStokObat.index')->name('superadmin.LapStokObat.index');
    Route::view('superadmin/LapSupplier', 'superadmin.LapSupplier.index')->name('superadmin.LapSupplier.index');
    Route::view('superadmin/Lap10Minta', 'superadmin.Lap10Minta.index')->name('superadmin.Lap10Minta.index');
});

// ---

// ===========================================
// Route Halaman Admin (Admin)
// ===========================================
Route::middleware(['auth', 'admin.access'])->group(function () {
    Route::view('admin/transaksi_masuk', 'admin.transaksi_masuk.index')->name('admin.transaksi_masuk.index');
    Route::view('admin/transaksi_keluar', 'admin.transaksi_keluar.index')->name('admin.transaksi_keluar.index');
    Route::view('admin/transaksi_return', 'admin.transaksi_return.index')->name('admin.transaksi_return.index');
    Route::view('admin/stok', 'admin.stok.index')->name('admin.stok.index');
    Route::view('admin/permintaan', 'admin.permintaan.index')->name('admin.permintaan.index');
});



// ===========================================
// Route Halaman Faskes (Fasilitas Kesehatan)
// ===========================================
Route::middleware(['auth', 'faskes.access'])->group(function () {
    Route::view('faskes/permintaan', 'faskes.permintaan.index')->name('faskes.permintaan.index');
    Route::view('faskes/riwayat_permintaan', 'faskes.riwayat_permintaan.index')->name('faskes.riwayat_permintaan.index');
});

// ===========================================
// Route Halaman Pimpinan
// ===========================================
Route::middleware(['auth', 'pimpinan.access'])->group(function () {
    Route::view('pimpinan/LapTransaksiMasuk', 'pimpinan.Laptransaksi_masuk.index')->name('pimpinan.Laptransaksi_masuk.index');
    Route::view('pimpinan/LapPermintaan', 'pimpinan.LapPermintaan.index')->name('pimpinan.LapPermintaan.index');
    Route::view('pimpinan/LapTransaksiKeluar', 'pimpinan.Laptransaksi_keluar.index')->name('pimpinan.Laptransaksi_keluar.index');
    Route::view('pimpinan/LapStok', 'pimpinan.LapStok.index')->name('pimpinan.LapStok.index');
    Route::view('pimpinan/LapAkanKadaluarsa', 'pimpinan.LapAkanKadaluarsa.index')->name('pimpinan.LapAkanKadaluarsa.index');
    Route::view('pimpinan/LapKadaluarsa', 'pimpinan.LapKadaluarsa.index')->name('pimpinan.LapKadaluarsa.index');
    Route::view('pimpinan/LapStokMinimum', 'pimpinan.LapStokMinimum.index')->name('pimpinan.LapStokMinimum.index');
    Route::view('pimpinan/Laptransaksi_return', 'pimpinan.Laptransaksi_return.index')->name('pimpinan.Laptransaksi_return.index');
    Route::view('pimpinan/LapStokObat', 'pimpinan.LapStokObat.index')->name('pimpinan.LapStokObat.index');
    Route::view('pimpinan/LapSupplier', 'pimpinan.LapSupplier.index')->name('pimpinan.LapSupplier.index');
    Route::view('pimpinan/Lap10Minta', 'pimpinan.Lap10Minta.index')->name('pimpinan.Lap10Minta.index');
    
});
