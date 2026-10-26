<?php

namespace App\Livewire\Faskes\Permintaan;

use Livewire\Component;
use App\Models\Permintaan;
use App\Models\PermintaanBarang;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

/**
 * Komponen Livewire untuk membuat Permintaan Logistik baru (Create).
 * Multi-step Form:
 * Step 1: Input Header Permintaan
 * Step 2: Input Detail Barang Permintaan (Multi-Entry)
 */
class Index extends Component
{
    // --- State Management ---
    public $currentStep = 1; // 1: Header, 2: Items

    // --- Properti Header Permintaan ---
    public $nomor_surat;
    public $perihal;
    public $tanggal_surat;

    // --- Properti Detail Item (Multi-Entry) ---
    // Struktur array: [['id_stok' => null, 'jumlah_diminta' => null, 'nama_satuan' => null]]
    public $items = [];

    // --- Data Referensi ---
    public $availableStoks;

    // --- Lifecycle Hooks ---

    public function mount()
    {
        $stoks = Stok::where('jumlah', '>', 0)
            ->whereDate('tanggal_kadaluarsa', '>=', now()) // Hanya cek yang BELUM kadaluwarsa
            ->whereDate('tanggal_kadaluarsa', '>', now()->addDays(90)) // Hanya cek yang BELUM kadaluwarsa
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
                    'display' => $namaObat . ' (Stok: ' . $totalStok . ')',

                    // Maksimum stok adalah JUMLAH TOTAL dari semua batch
                    'max_stok' => $totalStok
                ];
            })->sortBy('display')->values()->toArray(); // Reset kunci array dan konversi ke array

        // ... (Logika inisialisasi $this->items dan $this->tanggal_surat tetap sama) ...
        if (empty($this->items)) {
            $this->items[] = [
                'id_stok' => null,
                'jumlah_diminta' => null,
                'nama_satuan' => null, // Inisialisasi properti satuan
                'max_stok' => null
            ];
        }

        if (!$this->tanggal_surat) {
            $this->tanggal_surat = now()->toDateString();
        }

        if (!$this->nomor_surat) {
            $user = Auth::user();
            if ($user && $user->faskes) {
                $faskesName = $user->faskes->nama_faskes;
                $words = explode(' ', $faskesName);
                $acronym = '';
                foreach ($words as $w) {
                    if (strlen($w) > 0) {
                        $acronym .= strtoupper(substr($w, 0, 1));
                    }
                }
                
                $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $month = $romanMonths[now()->month - 1];
                $year = now()->year;
                
                $count = \App\Models\Permintaan::where('faskes_id', $user->faskes_id)
                    ->whereYear('created_at', $year)
                    ->count() + 1;
                $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
                
                $this->nomor_surat = "REQ/{$sequence}/{$acronym}/{$month}/{$year}";
            } else {
                $this->nomor_surat = "REQ/" . now()->format('YmdHis');
            }
        }
    }

    /**
     * Hook yang dipanggil ketika properti Livewire berubah.
     * Digunakan untuk mengambil nama satuan saat id_stok berubah.
     */
    public function updated($propertyName, $value)
    {
        // Cek apakah properti yang diupdate adalah id_stok di dalam array items
        if (preg_match('/items\.(\d+)\.id_stok/', $propertyName, $matches)) {
            $index = $matches[1];

            // Pastikan index valid dan value id_stok tidak null
            if (isset($this->items[$index]) && $value) {
                // Find max_stok from availableStoks
                $maxStok = 0;
                foreach ($this->availableStoks as $avail) {
                    if ($avail['id'] == $value) {
                        $maxStok = $avail['max_stok'];
                        break;
                    }
                }
                $this->items[$index]['max_stok'] = $maxStok;

                // Ambil data stok lengkap dengan relasi obat dan satuan
                $stok = Stok::with(['obat.satuan'])->find($value);

                if ($stok && $stok->obat && $stok->obat->satuan) {
                    // Update properti nama_satuan pada item yang bersangkutan
                    $this->items[$index]['nama_satuan'] = $stok->obat->satuan->nama_satuan;
                } else {
                    // Jika stok/relasi tidak ditemukan
                    $this->items[$index]['nama_satuan'] = null;
                }
            } else if (isset($this->items[$index])) {
                // Jika id_stok di-reset (kosong)
                $this->items[$index]['nama_satuan'] = null;
                $this->items[$index]['max_stok'] = null;
            }
        }
    }


    // --- Metode Navigasi Step ---

    public function firstStepSubmit()
    {
        // Validasi Step 1: Header
        $this->validate([
            'nomor_surat' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permintaan', 'nomor_surat')->whereNot('status', 'DITOLAK')
            ],
            'perihal' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date', 'before_or_equal:today'],
        ], [
            'nomor_surat.unique' => 'Nomor Permintaan ini sudah pernah digunakan (dan belum ditolak).'
        ]);

        // Pindah ke Step 2
        $this->currentStep = 2;
    }

    public function secondStepSubmit()
    {
        // Validasi Step 2: Detail Items

        if (count($this->items) === 0) {
            session()->flash('error', 'Anda harus meminta setidaknya satu item barang.');
            return;
        }

        $rules = [];
        foreach ($this->items as $index => $item) {


            // Aturan validasi
            $rules["items.{$index}.id_stok"] = ['required', 'exists:stok,id'];
            
            $maxStok = $item['max_stok'] ?? 0;
            if ($maxStok > 0) {
                $rules["items.{$index}.jumlah_diminta"] = ['required', 'integer', 'min:1', "max:{$maxStok}"];
            } else {
                $rules["items.{$index}.jumlah_diminta"] = ['required', 'integer', 'min:1'];
            }
        }

        // Validasi Duplikasi Item secara Manual
        $selectedStokIds = array_column($this->items, 'id_stok');
        $filteredStokIds = array_filter($selectedStokIds, function ($value) {
            return !is_null($value) && $value !== '';
        });
        $duplicates = array_count_values($filteredStokIds);
        $hasDuplicate = false;

        foreach ($this->items as $index => $item) {
            if (!empty($item['id_stok']) && isset($duplicates[$item['id_stok']]) && $duplicates[$item['id_stok']] > 1) {
                $this->addError("items.{$index}.id_stok", 'Item ini sudah ada di daftar permintaan.');
                $hasDuplicate = true;
            }
        }

        if ($hasDuplicate) {
            return;
        }

        // Jalankan Validasi PHP (kecuali duplikasi)
        $this->validate($rules, [
            'items.*.id_stok.required' => 'Item obat harus dipilih.',
            'items.*.jumlah_diminta.max' => 'Jumlah permintaan melebihi stok yang tersedia.',
            'items.*.jumlah_diminta.min' => 'Jumlah permintaan minimal 1.',
        ]);

        // Jika validasi item berhasil, langsung simpan (store)
        $this->store();
    }

    public function back()
    {
        $this->currentStep = 1;
    }

    // --- Metode Pengelolaan Item ---

    public function addItem()
    {
        $this->items[] = [
            'id_stok' => null,
            'jumlah_diminta' => null,
            'nama_satuan' => null, // Tambahkan properti satuan
            'max_stok' => null
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    // --- Logika Penyimpanan Data Akhir (Final Store) ---

    public function store()
    {
        $user = Auth::user();
        if (is_null($user->faskes_id)) {
            session()->flash('error', 'Akun Anda tidak terhubung dengan Faskes manapun.');
            return;
        }

        try {
            DB::beginTransaction();

            // A. Simpan Header Permintaan
            $permintaan = Permintaan::create([
                'faskes_id' => $user->faskes_id,
                'nomor_surat' => $this->nomor_surat,
                'perihal' => $this->perihal,
                'tanggal_surat' => $this->tanggal_surat,
                'status' => 'DIAJUKAN',
            ]);

            // B. Siapkan dan Simpan Detail Item
            $detailItems = [];
            foreach ($this->items as $item) {
                if ($item['id_stok'] && $item['jumlah_diminta']) {
                    $detailItems[] = [
                        'permintaan_id' => $permintaan->id,
                        'id_stok' => $item['id_stok'],
                        'jumlah_diminta' => $item['jumlah_diminta'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($detailItems)) {
                PermintaanBarang::insert($detailItems);
            }

            DB::commit();

            session()->flash('success', 'Permintaan Logistik berhasil diajukan!');
            session()->flash('show_detail_id', $permintaan->id);
            // Arahkan ke halaman riwayat
            return $this->redirectRoute('faskes.riwayat_permintaan.index', navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal membuat permintaan multi-step: " . $e->getMessage());
            session()->flash('error', 'Gagal mengajukan permintaan. Terjadi kesalahan pada server.');
        }
    }

    public function render()
    {
        $data = [
            'tittle' => 'Data Permintaan',
        ];
        return view('livewire.faskes.permintaan.index', $data);
    }
}
