<?php

namespace App\Livewire\Faskes\RiwayatPermintaan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Permintaan;
use App\Models\Stok;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Komponen untuk Faskes: Menampilkan daftar riwayat permintaan yang telah dibuat oleh Faskes tersebut.
 */
class Index extends Component
{
    use WithPagination;

    // --- Properti Umum ---
    protected $paginationTheme = 'bootstrap';
    public $paginate = 10;
    public $search = '';
    // Status Faskes mungkin ingin melihat semua status, bukan hanya 'DIAJUKAN'
    public $filterStatus = '';
    public $sort = 'created_at';
    public $direction = 'desc'; // Permintaan paling baru tampil duluan

    // Properti baru untuk menampung ID Faskes yang sedang login
    public $currentFaskesId;

    // --- Properti Detail & Edit Modal ---
    public $permintaanDetail;
    public $itemsDetail = [];
    public $isEditMode = false;
    public $permintaanId;
    public $nomor_surat, $perihal, $tanggal_surat;

    // --- Properti Tambah Barang di Modal ---
    public $showSearchStok = false;
    public $selectedStokId;
    public $availableStoks;
    // Status yang tersedia untuk filter Faskes
    public $availableStatuses = ['DIAJUKAN', 'DITERIMA', 'DITOLAK'];

    // Sinkronisasi Query String
    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => '']
    ];

    // --- Lifecycle Hook: Mount ---
    public function mount()
    {
        $stoks = Stok::where('jumlah', '>', 0)
            ->with('obat')
            ->get();

        // Lakukan pengelompokan dan agregasi (penjumlahan) total stok
        $this->availableStoks = $stoks
            // 1. Kelompokkan berdasarkan NAMA OBAT
            ->groupBy(function ($stok) {
                return $stok->obat->nama_obat;
            })
            // 2. Petakan/Proses setiap kelompok
            ->map(function ($groupedStoks, $namaObat) {

                // Dapatkan ID Stok PERTAMA dari grup (untuk digunakan sebagai value di dropdown)
                // Catatan: Ini adalah kompromi, karena Anda harus memilih salah satu ID Stok yang akan digunakan.
                $firstStok = $groupedStoks->first();

                // Hitung TOTAL JUMLAH stok dari semua batch obat ini
                $totalStok = $groupedStoks->sum('jumlah');

                return [
                    // ID ini akan menjadi ID STOK dari batch pertama dalam grup
                    'id' => $firstStok->id,

                    // Tampilan yang digabungkan: Nama Obat - Total Stok
                    'display' => $namaObat,

                    // Maksimum stok adalah JUMLAH TOTAL dari semua batch
                    'max_stok' => $totalStok
                ];
            })->sortBy('display')->values()->toArray();

        $this->currentFaskesId = Auth::user()->faskes_id ?? null;

        if (is_null($this->currentFaskesId)) {
            session()->flash('error', 'Anda tidak terhubung dengan Faskes manapun.');
        }

        if (session()->has('show_detail_id')) {
            $this->showDetail(session('show_detail_id'));
        }
    }

    public function removeItem($index)
    {
        unset($this->itemsDetail[$index]);
        $this->itemsDetail = array_values($this->itemsDetail); // Reset index array
    }

    // Reset halaman saat properti berubah
    public function updating($name)
    {
        if (in_array($name, ['search', 'filterStatus', 'paginate'])) {
            $this->resetPage();
        }
    }

    public function sortBy($field)
    {
        if ($this->sort === $field) {
            $this->direction = ($this->direction === 'asc') ? 'desc' : 'asc';
        } else {
            $this->sort = $field;
            $this->direction = 'asc';
        }
    }

    // --- Aksi Gudang Farmasi (Dibutuhkan untuk tampilnya showDetail) ---

    /**
     * Menampilkan detail permintaan di modal.
     */
    public function showDetail($id)
    {
        $this->permintaanDetail = Permintaan::with([
            'faskes',
            'itemPermintaan.stok.obat'
        ])->findOrFail($id);

        $itemsWithRealQty = [];

        foreach ($this->permintaanDetail->itemPermintaan as $item) {
            $idObat = $item->stok->obat_id;
            $idPermintaan = $this->permintaanDetail->id;

            // Ambil jumlah yang benar-benar diberikan dari tabel TransaksiKeluar
            $jumlahDiberikan = TransaksiKeluar::where('id_permintaan', $idPermintaan)
                ->whereHas('stok', function ($q) use ($idObat) {
                    $q->where('obat_id', $idObat);
                })
                ->sum('jumlah');

            // Simpan ke dalam array sementara untuk dikirim ke view
            $itemsWithRealQty[] = [
                'id_stok' => $item->id_stok,
                'nama_obat' => $item->stok->obat->nama_obat ?? 'N/A',
                'jumlah_diminta' => $item->jumlah_diminta,
                'jumlah_diberi' => $jumlahDiberikan, // Data asli dari transaksi
            ];
        }

        $this->itemsDetail = $itemsWithRealQty;
        $this->dispatch('showDetailModal');
    }

    // --- FITUR EDIT & HAPUS ---

    public function editPermintaan($id)
    {
        $this->showDetail($id); // Gunakan fungsi showDetail asli untuk load data

        if ($this->permintaanDetail->status !== 'DIAJUKAN') {
            session()->flash('error', 'Hanya permintaan berstatus DIAJUKAN yang dapat diubah.');
            return;
        }

        $this->permintaanId = $id;
        $this->nomor_surat = $this->permintaanDetail->nomor_surat;
        $this->perihal = $this->permintaanDetail->perihal;
        $this->tanggal_surat = $this->permintaanDetail->tanggal_surat->format('Y-m-d');

        $this->isEditMode = true;
        $this->dispatch('showEditModal');
    }

    public function tambahItemKeDaftar()
    {
        $this->validate([
            'selectedStokId' => 'required'
        ], [
            'selectedStokId.required' => 'Pilih obat terlebih dahulu.'
        ]);

        $stok = Stok::with('obat')->find($this->selectedStokId);
        if ($stok) {
            // Cek duplikat
            foreach ($this->itemsDetail as $item) {
                // Pastikan menggunakan 'stok_id' sesuai dengan struktur array yang Anda buat di bawah
                if ($item['id_stok'] == $this->selectedStokId) {
                    session()->flash('warning', 'Barang ' . $stok->obat->nama_obat . ' sudah ada di daftar.');
                    return;
                }
            }

            $this->itemsDetail[] = [
                'id_stok' => $stok->id,
                'nama_obat' => $stok->obat->nama_obat ?? 'N/A',
                'jumlah_diminta' => 1,
                'jumlah_diberi' => 0,
            ];

            $this->selectedStokId = null;
            $this->showSearchStok = false;
        }
    }

    public function updatePermintaan()
    {
        $this->validate([
            'nomor_surat' => [
                'required',
                Rule::unique('permintaan', 'nomor_surat')
                    ->ignore($this->permintaanId)
                    ->whereNot('status', 'DITOLAK')
            ],
            'perihal' => 'required',
            'tanggal_surat' => 'required|date',
            'itemsDetail.*.jumlah_diminta' => 'required|numeric|min:1',
        ], [
            'nomor_surat.required' => 'Nomor surat tidak boleh kosong.',
            'nomor_surat.unique' => 'Nomor Permintaan sudah digunakan (dan belum ditolak), mohon gunakan nomor lain.',
            'perihal.required' => 'Perihal tidak boleh kosong.',
            'tanggal_surat.required' => 'Tanggal surat tidak boleh kosong.',
            'tanggal_surat.date' => 'Format tanggal surat tidak valid.',
            'itemsDetail.*.jumlah_diminta.required' => 'Jumlah permintaan harus diisi.',
            'itemsDetail.*.jumlah_diminta.numeric' => 'Jumlah permintaan harus berupa angka.',
            'itemsDetail.*.jumlah_diminta.min' => 'Jumlah permintaan minimal adalah 1.',
        ]);

        try {
            DB::transaction(function () {
                $permintaan = Permintaan::findOrFail($this->permintaanId);

                // 1. Update data utama
                $permintaan->update([
                    'nomor_surat' => $this->nomor_surat,
                    'perihal' => $this->perihal,
                    'tanggal_surat' => $this->tanggal_surat,
                ]);

                // 2. Hapus item lama menggunakan nama relasi yang benar: itemPermintaan
                $permintaan->itemPermintaan()->delete();

                // 3. Simpan item baru
                foreach ($this->itemsDetail as $item) {
                    $permintaan->itemPermintaan()->create([
                        'id_stok' => $item['id_stok'],
                        'jumlah_diminta' => $item['jumlah_diminta'],
                    ]);
                }
            });

            session()->flash('success', 'Permintaan berhasil diperbarui.');
            $this->dispatch('hideEditModal');
        } catch (\Exception $e) {
            Log::error("Gagal update permintaan: " . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan sistem saat menyimpan.');
        }
    }

    public function hapusPermintaan($id)
    {
        // Cari data permintaan milik faskes yang sedang login
        $permintaan = Permintaan::where('faskes_id', $this->currentFaskesId)->find($id);

        if (!$permintaan) {
            session()->flash('error', 'Data tidak ditemukan.');
            return;
        }

        // VALIDASI: Hanya status 'DIAJUKAN' yang bisa dihapus
        if ($permintaan->status !== 'DIAJUKAN') {
            // Mengirim notifikasi menggunakan browser event atau session flash
            session()->flash('error', 'Permintaan tidak bisa dihapus karena sudah berstatus ' . $permintaan->status);
            return;
        }

        try {
            DB::transaction(function () use ($permintaan) {
                // Hapus item-itemnya terlebih dahulu
                $permintaan->itemPermintaan()->delete();
                // Hapus data utama
                $permintaan->delete();
            });

            session()->flash('success', 'Permintaan berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    /**
     * Mengubah status permintaan menjadi DITERIMA (disetujui dan siap kirim).
     */
    public function terimaPermintaan($id)
    {
        $permintaan = Permintaan::findOrFail($id);

        if ($permintaan->status !== 'DIAJUKAN') {
            session()->flash('warning', 'Permintaan ini sudah diproses atau ditolak sebelumnya.');
            return;
        }

        try {
            $permintaan->status = 'DITERIMA';
            $permintaan->save();

            session()->flash('success', 'Permintaan Nomor Permintaan ' . $permintaan->nomor_surat . ' berhasil DITERIMA dan status telah diperbarui.');
        } catch (\Exception $e) {
            Log::error("Gagal menerima permintaan: " . $e->getMessage());
            session()->flash('error', 'Gagal memproses. Terjadi kesalahan server.');
        }
    }

    /**
     * Mengubah status permintaan menjadi DITOLAK.
     */
    public function tolakPermintaan($id)
    {
        $permintaan = Permintaan::findOrFail($id);

        if ($permintaan->status !== 'DIAJUKAN') {
            session()->flash('warning', 'Permintaan ini sudah diproses atau ditolak sebelumnya.');
            return;
        }

        try {
            $permintaan->status = 'DITOLAK';
            $permintaan->save();

            session()->flash('success', 'Permintaan Nomor Permintaan ' . $permintaan->nomor_surat . ' berhasil DITOLAK.');
        } catch (\Exception $e) {
            Log::error("Gagal menolak permintaan: " . $e->getMessage());
            session()->flash('error', 'Gagal memproses. Terjadi kesalahan server.');
        }
    }

    // --- Render Component ---
    public function render()
    {
        if (is_null($this->currentFaskesId)) {
            $permintaans = collect([]);
        } else {
            $query = Permintaan::with('faskes')
                ->where('faskes_id', $this->currentFaskesId)
                ->when($this->filterStatus, function ($queryBuilder) {
                    $queryBuilder->where('status', $this->filterStatus);
                })
                ->when($this->search, function ($queryBuilder) {
                    $queryBuilder->where('nomor_surat', 'like', '%' . $this->search . '%');
                });

            $query->orderBy($this->sort, $this->direction);
            $permintaans = $query->paginate($this->paginate);
        }

        $data = [
            'tittle' => 'Riwayat Permintaan Logistik Anda',
            'permintaans' => $permintaans,
        ];

        return view('livewire.faskes.riwayat_permintaan.index', $data);
    }
}
