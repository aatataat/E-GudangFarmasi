<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\NotifikasiController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Menjalankan pengecekan setiap hari jam 08:00 pagi
// Schedule::call([NotifikasiController::class, 'cekStokKritis'])->dailyAt('16:37');
// Schedule::call([NotifikasiController::class, 'cekStokKritis'])->everyMinute();


Schedule::call(function () {
    (new NotifikasiController())->cekDanKirimNotifikasi();
})->everyMinute();

// Schedule::call(function () {
//     $controller = new NotifikasiController();
//     return $controller->cekStokKritis();
// })->everyMinute();

Schedule::call(function () {
    (new NotifikasiController())->cekDanKirimNotifikasi();
})->dailyAt('08:00');
