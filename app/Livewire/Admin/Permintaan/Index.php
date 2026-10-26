<?php

namespace App\Livewire\Admin\Permintaan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Permintaan;
use App\Models\PermintaanBarang;
use App\Models\Stok;
use App\Models\TransaksiKeluar;
use App\Models\Faskes;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $paginate = 10;
    public $search = '';
    public $filterStatus = 'DIAJUKAN';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $filterFaskes = null;
    public $filterTanggal = null;

    public $permintaanDetail;
    public $editableItems = [];

    public $availableStatuses = ['DIAJUKAN', 'DITERIMA', 'DITOLAK'];

    public function showDetail($id)
    {
        $permintaan = Permintaan::with([
            'faskes',
            'itemPermintaan.stok.obat'
        ])->findOrFail($id);

        $this->permintaanDetail = $permintaan;
        $this->editableItems = [];

        $hariIni = now()->toDateString();

        foreach ($permintaan->itemPermintaan as $item) {
            $idObat = $item->stok->obat_id;

            // 1. Hitung stok layak salur (Belum ED)
            $totalStokGudang = Stok::where('obat_id', $idObat)
                ->where('jumlah', '>', 0)
                ->whereDate('tanggal_kadaluarsa', '>=', $hariIni)
                ->sum('jumlah');

            // 2. LOGIKA BARU: Ambil jumlah yang benar-benar diberikan dari TransaksiKeluar
            // Jika status sudah DITERIMA, ambil dari riwayat transaksi.
            // Jika masih DIAJUKAN, defaultnya adalah jumlah_diminta.
            if ($permintaan->status === 'DITERIMA' || $permintaan->status === 'SELESAI') {
                $jumlahYangDiberikan = TransaksiKeluar::where('id_permintaan', $permintaan->id)
                    ->whereHas('stok', function ($q) use ($idObat) {
                        $q->where('obat_id', $idObat);
                    })
                    ->sum('jumlah');
            } else {
                $jumlahYangDiberikan = $item->jumlah_diminta;
            }

            $this->editableItems[] = [
                'id_item_permintaan' => $item->id,
                'nama_obat' => $item->stok->obat->nama_obat ?? 'N/A',
                'jumlah_diminta' => $item->jumlah_diminta,
                'jumlah_diberi' => $jumlahYangDiberikan, // Sekarang mengambil data yang benar
                'total_stok_gudang' => $totalStokGudang,
            ];
        }

        $this->dispatch('showDetailModal');
    }

    public function terimaPermintaan($id)
    {
        $permintaan = Permintaan::findOrFail($id);

        // Validasi: Hanya izinkan jika status masih DIAJUKAN atau sudah DITERIMA (untuk edit)
        if (!in_array($permintaan->status, ['DIAJUKAN', 'DITERIMA'])) {
            return;
        }

        try {
            DB::beginTransaction();
            $hariIni = now()->toDateString();

            /**
             * TAHAP 1: RESET STOK (Jika status sebelumnya sudah DITERIMA)
             * Kita kembalikan stok ke posisi semula sebelum dipotong lagi dengan angka baru.
             */
            if ($permintaan->status === 'DITERIMA') {
                $riwayatLama = TransaksiKeluar::where('id_permintaan', $permintaan->id)->get();

                foreach ($riwayatLama as $lama) {
                    $stokAsal = Stok::find($lama->id_stok);
                    if ($stokAsal) {
                        $jumlahSetelahReset = $stokAsal->jumlah + $lama->jumlah;
                        $jumlahKeluarSetelahReset = $stokAsal->jumlah_keluar - $lama->jumlah;

                        $stokAsal->update([
                            'jumlah'        => $jumlahSetelahReset,
                            'jumlah_keluar' => $jumlahKeluarSetelahReset,
                            'total_keluar'  => $jumlahKeluarSetelahReset * $stokAsal->harga, // Hitung ulang total uang
                        ]);
                    }
                    // Hapus riwayat transaksi lama agar tidak ganda
                    $lama->delete();
                }
            }

            /**
             * TAHAP 2: VALIDASI STOK SEMUA ITEM
             */
            $hasError = false;
            foreach ($this->editableItems as $index => $itemEdit) {
                $itemAsli = PermintaanBarang::find($itemEdit['id_item_permintaan']);
                $idObat = $itemAsli->stok->obat_id;
                $jumlahDiberi = (int)$itemEdit['jumlah_diberi'];

                if ($jumlahDiberi <= 0) continue;

                $stokBatches = Stok::where('obat_id', $idObat)
                    ->where('jumlah', '>', 0)
                    ->whereDate('tanggal_kadaluarsa', '>=', $hariIni)
                    ->lockForUpdate()
                    ->get();

                if ($stokBatches->sum('jumlah') < $jumlahDiberi) {
                    $this->addError('editableItems.' . $index . '.jumlah_diberi', 'Stok tidak mencukupi.');
                    $hasError = true;
                }
            }

            if ($hasError) {
                DB::rollBack();
                return;
            }

            /**
             * TAHAP 3: PROSES PEMOTONGAN STOK
             * Menggunakan FEFO dan memotong stok berdasarkan input 'jumlah_diberi' terbaru.
             */
            foreach ($this->editableItems as $itemEdit) {
                $itemAsli = PermintaanBarang::find($itemEdit['id_item_permintaan']);
                $idObat = $itemAsli->stok->obat_id;
                $jumlahDiberi = (int)$itemEdit['jumlah_diberi'];

                if ($jumlahDiberi <= 0) continue;

                $stokBatches = Stok::where('obat_id', $idObat)
                    ->where('jumlah', '>', 0)
                    ->whereDate('tanggal_kadaluarsa', '>=', $hariIni)
                    ->orderBy('tanggal_kadaluarsa', 'asc')
                    ->get();

                $sisaPotong = $jumlahDiberi;
                foreach ($stokBatches as $batch) {
                    if ($sisaPotong <= 0) break;

                    $diambil = min($sisaPotong, $batch->jumlah);
                    $totalHargaItem = $diambil * $batch->harga;

                    // Catat ke Transaksi Keluar
                    TransaksiKeluar::create([
                        'tanggal_keluar'     => now()->toDateString(),
                        'faskes_id'          => $permintaan->faskes_id,
                        'id_stok'            => $batch->id,
                        'jumlah'             => $diambil,
                        'harga'              => $batch->harga,
                        'total'              => $totalHargaItem,
                        'id_permintaan'      => $permintaan->id,
                        'batch_number'       => $batch->batch_number,
                        'tanggal_kadaluarsa' => $batch->tanggal_kadaluarsa,
                    ]);

                    // Update Tabel Stok (Dengan perbaikan perhitungan total_keluar agar sinkron)
                    $jumlahKeluarBaru = $batch->jumlah_keluar + $diambil;

                    $batch->update([
                        'jumlah'        => $batch->jumlah - $diambil,
                        'harga_keluar'  => $batch->harga,
                        'jumlah_keluar' => $jumlahKeluarBaru,
                        'total_keluar'  => $jumlahKeluarBaru * $batch->harga, // Perbaikan hitungan di sini
                    ]);

                    $sisaPotong -= $diambil;
                }
            }

            // Update status permintaan menjadi DITERIMA
            $permintaan->update(['status' => 'DITERIMA']);

            DB::commit();

            $this->dispatch('closeDetailModal');
            session()->flash('success', 'Verifikasi jumlah berhasil disimpan dan stok telah diperbarui.');
            return $this->redirectRoute('admin.transaksi_keluar.index', navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }



    public function tolakPermintaan($id)
    {
        $permintaan = Permintaan::findOrFail($id);

        // Pastikan hanya permintaan dengan status DIAJUKAN yang bisa ditolak
        if ($permintaan->status !== 'DIAJUKAN') {
            session()->flash('error', 'Hanya permintaan dengan status DIAJUKAN yang dapat ditolak.');
            return;
        }

        try {
            $permintaan->update([
                'status' => 'DITOLAK'
            ]);

            $this->dispatch('closeDetailModal');
            session()->flash('success', 'Permintaan telah ditolak.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menolak permintaan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Permintaan::with('faskes')
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterFaskes, fn($q) => $q->where('faskes_id', $this->filterFaskes))
            ->when($this->filterTanggal, fn($q) => $q->whereDate('tanggal_surat', $this->filterTanggal))
            ->when($this->search, function ($q) {
                $q->where('nomor_surat', 'like', '%' . $this->search . '%')
                    ->orWhereHas('faskes', fn($sq) => $sq->where('nama_faskes', 'like', '%' . $this->search . '%'));
            });

        $finalDirection = ($this->filterStatus === 'DIAJUKAN') ? 'asc' : $this->direction;

        return view('livewire.admin.permintaan.index', [
            'title' => 'Daftar Persetujuan Permintaan Logistik',
            'permintaans' => $query->orderBy($this->sort, $finalDirection)->paginate($this->paginate),
            'allFaskes' => Faskes::orderBy('nama_faskes')->get(),
        ]);
    }
}
