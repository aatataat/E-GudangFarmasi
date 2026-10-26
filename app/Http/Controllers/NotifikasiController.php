<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Stok;
use App\Models\User;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    protected $telegramToken = "8270166224:AAG_BI-8x_3rK-wSdr8_VDg-U8PPrmnfELI";

    public function cekDanKirimNotifikasi()
    {
        $penerima = User::whereIn('role', ['Pimpinan', 'Pegawai', 'Admin'])
            ->whereNotNull('telegram_chat_id')
            ->get();

        if ($penerima->isEmpty()) return "Penerima tidak ditemukan.";

        $this->prosesStokKritis($penerima);
        $this->prosesAkanKadaluarsa($penerima);
        $this->prosesKadaluarsa($penerima);

        return "Notifikasi diproses.";
    }

    protected function prosesStokKritis($penerima)
    {
        // 1. Ambil data HANYA dari tabel stok yang memiliki jumlah > 0
        // Kita join ke obat hanya untuk mengambil 'nama_obat' dan menjalankan Accessor
        $dataStok = Stok::select('obat_id', DB::raw('SUM(jumlah) as total_sisa'))
            ->groupBy('obat_id')
            ->with('obat') // Untuk mengambil properti nama_obat dan batas_minimum
            ->get();

        $pesanStok = "📦 <b>LAPORAN STOK KRITIS</b>\n";
        $pesanStok .= "⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯\n";
        $adaKritis = false;

        foreach ($dataStok as $stok) {
            $obat = $stok->obat;
            if (!$obat) continue;

            $totalSisa = $stok->total_sisa;
            $batasMin = $obat->batas_minimum; // Menggunakan rumus Safety Stock Anda

            // 2. Hanya kirim jika sisa stok di bawah atau sama dengan batas minimum
            if ($totalSisa <= $batasMin) {
                $adaKritis = true;
                $pesanStok .= "• <b>{$obat->nama_obat}</b>\n";
                $pesanStok .= "  Sisa: <code>{$totalSisa}</code> (Batas Min: {$batasMin})\n\n";
            }
        }

        if ($adaKritis) {
            foreach ($penerima as $user) {
                $this->sendTelegram($pesanStok, $user->telegram_chat_id);
            }
        }
    }

    protected function prosesAkanKadaluarsa($penerima)
    {
        // Langsung ambil dari tabel stok
        $dataExpired = Stok::whereDate('tanggal_kadaluarsa', '<=', Carbon::now()->addDays(90))
            ->whereDate('tanggal_kadaluarsa', '>=', Carbon::now())
            ->with('obat')
            ->get();

        if ($dataExpired->count() > 0) {
            $pesanExp = "🚫 <b>PERINGATAN BARANG AKAN KADALUARSA 3 BULAN KEDEPAN</b>\n";
            $pesanExp .= "⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯\n";

            foreach ($dataExpired as $item) {
                $nama = $item->obat->nama_obat ?? 'Obat Tidak Diketahui';
                $tgl = Carbon::parse($item->tanggal_kadaluarsa)->translatedFormat('d M Y');
                
                $pesanExp .= "• <b>{$nama}</b>\n";
                $pesanExp .= "  Batch: <code>{$item->batch_number}</code>\n";
                $pesanExp .= "  Exp: <code>{$tgl}</code> (Sisa: {$item->jumlah})\n\n";
            }

            foreach ($penerima as $user) {
                $this->sendTelegram($pesanExp, $user->telegram_chat_id);
            }
        }
    }

    protected function prosesKadaluarsa($penerima)
    {
        $dataExpired = Stok::whereDate('tanggal_kadaluarsa', '<=', Carbon::now())
            ->where('jumlah', '>', 0) // Pastikan hanya yang masih ada stoknya
            ->with('obat')
            ->get();

        if ($dataExpired->count() > 0) {
            $pesanExp = "⚠️ <b>PERINGATAN BARANG SUDAH KADALUARSA</b>\n";
            $pesanExp .= "⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯⎯\n";

            foreach ($dataExpired as $item) {
                $nama = $item->obat->nama_obat ?? 'Obat Tidak Diketahui';
                $tgl = Carbon::parse($item->tanggal_kadaluarsa)->translatedFormat('d M Y');

                $pesanExp .= "• <b>{$nama}</b>\n";
                $pesanExp .= "  Batch: <code>{$item->batch_number}</code>\n";
                $pesanExp .= "  Exp: <code>{$tgl}</code> (Sisa: {$item->jumlah})\n\n";
            }

            foreach ($penerima as $user) {
                $this->sendTelegram($pesanExp, $user->telegram_chat_id);
            }
        }
    }

    
    private function sendTelegram($text, $chatId)
    {
        return Http::post("https://api.telegram.org/bot{$this->telegramToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ]);
    }
}