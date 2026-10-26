<?php

namespace App\Livewire\Superadmin\TransaksiReturn;

use Livewire\Component;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiReturn;
use App\Models\Stok;
use App\Models\Faskes;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Properti Filter & Tabel
    public $search = '';
    public $paginate = 10;
    public $sort = 'created_at';
    public $direction = 'desc';

    // Properti Form Shared
    public $faskes_id;
    public $tanggal_return;
    public $keterangan;

    // Properti Create (Multi-item)
    public $detailItems = [];
    public $listTransaksiKeluars = [];

    // Properti Edit & Delete
    public $return_id;
    public $id_transaksi_keluar;
    public $jumlah_return;
    public $status_stok;
    public $max_jumlah_return = 0;
    public $nama_obat_display;
    public $batch_number;
    public $filterTanggal=null;
    public $filterFaskes=null;

    public function mount()
    {
        $this->tanggal_return = Carbon::now()->format('Y-m-d');
        $this->addItem();
    }

    // ==========================================
    // LOGIKA RELASI DATA (BARANG PER FASKES)
    // ==========================================

    /**
     * Trigger saat Faskes dipilih di Modal
     */
    public function updatedFaskesId($value)
    {
        $this->faskes_id = $value ?: null;

        // Kosongkan detail item yang sedang diinput agar tidak terjadi 
        // pengiriman barang faskes A ke faskes B
        $this->detailItems = [];
        $this->addItem();

        // Muat barang yang hanya pernah dikeluarkan untuk faskes ini
        $this->loadTransaksiKeluars();
    }

    /**
     * Memuat daftar barang yang pernah keluar ke faskes terpilih
     */
    public function loadTransaksiKeluars()
    {
        if (!$this->faskes_id) {
            $this->listTransaksiKeluars = [];
            return;
        }

        // 1. Ambil semua transaksi keluar untuk faskes ini
        $allTransactions = TransaksiKeluar::with(['stok.obat', 'permintaan'])
            ->whereHas('permintaan', function ($q) {
                $q->where('faskes_id', $this->faskes_id);
            })
            ->get();

        // 2. Kelompokkan berdasarkan id_stok (Obat & Batch yang sama)
        $this->listTransaksiKeluars = $allTransactions->groupBy('id_stok')
            ->map(function ($group) {
                // Ambil sampel data pertama dari grup untuk info nama & batch
                $first = $group->first();

                // Hitung total jumlah keluar untuk batch ini
                $totalKeluar = $group->sum('jumlah');

                // Hitung total yang sudah diretur untuk semua ID transaksi dalam grup ini
                $idsDalamGrup = $group->pluck('id')->toArray();
                $sudahRetur = TransaksiReturn::whereIn('id_transaksi_keluar', $idsDalamGrup)->sum('jumlah');

                $sisa = $totalKeluar - $sudahRetur;

                // Tentukan ID yang akan digunakan sebagai value pada <option>.
                // Jika sedang dalam mode edit dan id_transaksi_keluar ada di dalam grup ini, gunakan ID tersebut.
                $idOption = in_array($this->id_transaksi_keluar, $idsDalamGrup) ? $this->id_transaksi_keluar : $first->id;

                return [
                    // Gunakan ID yang sudah dicocokkan
                    'id' => $idOption,
                    'nama_obat' => $first->stok->obat->nama_obat ?? 'N/A',
                    'batch_number' => $first->batch_number,
                    'sisa' => $sisa,
                    'label' => ($first->stok->obat->nama_obat ?? 'N/A') .
                        " - Batch: " . $first->batch_number .
                        " (Total Sisa: $sisa)"
                ];
            })
            ->sortBy('nama_obat')
            // Tetap tampilkan jika ada sisa, ATAU jika ID-nya sama dengan yang sedang diedit
            ->filter(fn($i) => $i['sisa'] > 0 || $i['id'] == $this->id_transaksi_keluar)
            ->values()
            ->toArray(); // Konversi ke array asosiatif agar lebih aman dari isu binding Livewire
    }

    // ==========================================
    // MANAJEMEN BARIS (MULTI-ITEM)
    // ==========================================

    public function addItem()
    {
        $this->detailItems[] = [
            'id_transaksi_keluar' => '',
            'jumlah_return' => 1,
            'status_stok' => 'Expired',
            'max_jumlah_return' => 0
        ];
    }

    public function removeItem($index)
    {
        if (count($this->detailItems) > 1) {
            unset($this->detailItems[$index]);
            $this->detailItems = array_values($this->detailItems);
        }
    }

    /**
     * Update batas maksimum input jumlah saat barang dipilih di baris
     */
    public function updated($property, $value)
    {
        // Tangkap perubahan pada properti bersarang detailItems.*.id_transaksi_keluar
        if (str_starts_with($property, 'detailItems.') && str_ends_with($property, '.id_transaksi_keluar')) {
            $parts = explode('.', $property);
            $index = $parts[1];
            
            if (!$value) {
                $this->detailItems[$index]['max_jumlah_return'] = 0;
                $this->detailItems[$index]['jumlah_return'] = 1;
                return;
            }

            $match = collect($this->listTransaksiKeluars)->firstWhere('id', $value);
            if ($match) {
                $this->detailItems[$index]['max_jumlah_return'] = $match['sisa'];
                
                // Jika input jumlah sebelumnya melebihi max sisa, sesuaikan
                if ($this->detailItems[$index]['jumlah_return'] > $match['sisa']) {
                    $this->detailItems[$index]['jumlah_return'] = $match['sisa'];
                }
            }
        }
    }

    // ==========================================
    // PROSES CRUD
    // ==========================================

    public function create()
    {
        $this->resetValidation();
        $this->reset(['faskes_id', 'keterangan', 'return_id', 'id_transaksi_keluar', 'jumlah_return', 'status_stok', 'listTransaksiKeluars']);
        $this->tanggal_return = Carbon::now()->format('Y-m-d');
        $this->detailItems = [
            ['id_transaksi_keluar' => '', 'jumlah_return' => 1, 'status_stok' => 'Expired', 'max_jumlah_return' => 0]
        ];
    }

    public function store()
    {
        // 1. Validasi input dasar
        $this->validate([
            'faskes_id' => 'required',
            'tanggal_return' => 'required|date',
            'detailItems.*.id_transaksi_keluar' => 'required',
            'detailItems.*.jumlah_return' => 'required|numeric|min:1',
        ]);

        // 2. LOGIKA VALIDASI BARANG GANDA (DUPLIKASI)
        $ids = collect($this->detailItems)->pluck('id_transaksi_keluar')->filter()->toArray();

        // Jika jumlah ID unik tidak sama dengan jumlah total ID yang dipilih, berarti ada duplikat
        if (count($ids) !== count(array_unique($ids))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'duplicate_item' => ' Anda memilih barang yang sama di beberapa baris. Silakan hapus salah satu atau gabungkan jumlahnya.'
            ]);
        }

        try {
            DB::transaction(function () {
                foreach ($this->detailItems as $item) {
                    $tk = TransaksiKeluar::findOrFail($item['id_transaksi_keluar']);

                    // Cek sisa batas retur
                    $sudahRetur = TransaksiReturn::where('id_transaksi_keluar', $item['id_transaksi_keluar'])->sum('jumlah');
                    if ($item['jumlah_return'] > ($tk->jumlah - $sudahRetur)) {
                        throw new \Exception("Jumlah melebihi sisa untuk: " . $tk->stok->obat->nama_obat);
                    }

                    // Simpan ke DB (Perhatikan 'K' kapital pada Keterangan sesuai SQL Anda)
                    TransaksiReturn::create([
                        'id_transaksi_keluar' => $item['id_transaksi_keluar'],
                        'tanggal_return'      => $this->tanggal_return,
                        'jumlah'              => $item['jumlah_return'],
                        'keterangan'          => $this->keterangan,
                        'status_stok'         => $item['status_stok'],
                    ]);

                    // Update Stok
                    $stok = Stok::findOrFail($tk->id_stok);
                    $nominalReturn = $item['jumlah_return'] * $stok->harga; // Hitung nominal uang

                    $stok->jumlah += $item['jumlah_return'];
                    $stok->jumlah_keluar -= $item['jumlah_return'];

                    // PENTING: Update total_keluar di sini
                    $stok->total_keluar = $stok->jumlah_keluar * $stok->harga;

                    $stok->total = $stok->jumlah * $stok->harga;
                    $stok->save();
                }
            });

            $this->dispatch('closeCreateModal');
            $this->reset(['faskes_id', 'detailItems', 'keterangan']);
            $this->addItem();
            session()->flash('message', 'Transaksi Return berhasil disimpan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $retur = TransaksiReturn::with(['transaksiKeluar.permintaan'])->findOrFail($id);

        $this->return_id = $retur->id;
        $this->faskes_id = $retur->transaksiKeluar->permintaan->faskes_id ?? null;
        $this->id_transaksi_keluar = $retur->id_transaksi_keluar;
        $this->tanggal_return = \Carbon\Carbon::parse($retur->tanggal_return)->format('Y-m-d');
        $this->jumlah_return = $retur->jumlah;

        // Gunakan K kapital 'Keterangan' sesuai SQL Anda agar muncul di form
        $this->keterangan = $retur->keterangan;
        $this->status_stok = $retur->status_stok;

        // 1. Muat dulu daftar barang
        $this->loadTransaksiKeluars();

        // 2. Cari barang di list gabungan
        $match = collect($this->listTransaksiKeluars)->firstWhere('id', $this->id_transaksi_keluar);

        if ($match) {
            /** * PERBAIKAN: 
             * Batas maks = Sisa stok saat ini + jumlah yang sedang kita edit.
             * Contoh: Sisa di sistem 8, kita sedang edit record yang jumlahnya 2.
             * Maka user boleh mengedit maksimal sampai 10.
             */
            $this->max_jumlah_return = $match['sisa'] + $retur->jumlah;
        }
    }

    /**
     * Otomatis terpanggil saat id_transaksi_keluar diubah di modal EDIT
     */
    public function updatedIdTransaksiKeluar($value)
    {
        if (!$value) {
            $this->max_jumlah_return = 0;
            $this->jumlah_return = 0;
            return;
        }

        // Cari barang yang dipilih dari daftar yang sudah ada
        $match = collect($this->listTransaksiKeluars)->firstWhere('id', $value);

        if ($match) {
            // Update batas maksimum sesuai sisa barang tersebut
            $this->max_jumlah_return = $match['sisa'];

            // Jika jumlah yang diinput sebelumnya melebihi batas baru, reset ke batas maks
            if ($this->jumlah_return > $this->max_jumlah_return) {
                $this->jumlah_return = $this->max_jumlah_return;
            }
        }
    }

    public function update()
    {
        $this->validate([
            'id_transaksi_keluar' => 'required',
            'tanggal_return' => 'required|date',
            'jumlah_return' => 'required|numeric|min:1|max:' . $this->max_jumlah_return,
        ]);

        try {
            DB::transaction(function () {
                $retur = TransaksiReturn::findOrFail($this->return_id);

                // Simpan ID stok lama untuk proses rollback
                $tkLama = TransaksiKeluar::findOrFail($retur->id_transaksi_keluar);
                $stokLama = Stok::findOrFail($tkLama->id_stok);

                // 1. ROLLBACK STOK LAMA (Kembalikan ke kondisi sebelum ada return ini)
                $stokLama->jumlah -= $retur->jumlah;
                $stokLama->jumlah_keluar += $retur->jumlah;
                // Hitung ulang total_keluar & total stok agar akurat
                $stokLama->total_keluar = $stokLama->jumlah_keluar * $stokLama->harga;
                $stokLama->total = $stokLama->jumlah * $stokLama->harga;
                $stokLama->save();

                // 2. UPDATE DATA RETURN
                $retur->update([
                    'id_transaksi_keluar' => $this->id_transaksi_keluar,
                    'tanggal_return' => $this->tanggal_return,
                    'jumlah' => $this->jumlah_return,
                    'keterangan' => $this->keterangan,
                    'status_stok' => $this->status_stok,
                ]);

                // 3. APPLY STOK BARU (Gunakan info dari transaksi keluar yang baru dipilih)
                $tkBaru = TransaksiKeluar::findOrFail($this->id_transaksi_keluar);
                $stokBaru = Stok::findOrFail($tkBaru->id_stok);

                $stokBaru->jumlah += $this->jumlah_return;
                $stokBaru->jumlah_keluar -= $this->jumlah_return;

                // PENTING: Gunakan perkalian langsung untuk menjamin total_keluar sinkron
                $stokBaru->total_keluar = $stokBaru->jumlah_keluar * $stokBaru->harga;
                $stokBaru->total = $stokBaru->jumlah * $stokBaru->harga;
                $stokBaru->save();
            });

            $this->dispatch('closeEditModal');
            session()->flash('message', 'Data berhasil diperbarui dan stok telah disinkronkan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal: ' . $e->getMessage());
        }
    }
    public function confirm($id)
    {
        $retur = TransaksiReturn::with(['transaksiKeluar.stok.obat'])->findOrFail($id);

        $this->return_id = $retur->id;
        $this->nama_obat_display = $retur->transaksiKeluar->stok->obat->nama_obat ?? 'N/A';
        $this->batch_number = $retur->transaksiKeluar->batch_number ?? '-';
        $this->tanggal_return = $retur->tanggal_return;
        $this->jumlah_return = $retur->jumlah;
        $this->status_stok = $retur->status_stok;
        // Gunakan K kapital
        $this->keterangan = $retur->keterangan;
    }

    public function destroy()
    {
        try {
            DB::transaction(function () {
                $retur = TransaksiReturn::findOrFail($this->return_id);
                $tk = TransaksiKeluar::find($retur->id_transaksi_keluar);

                if ($tk) {
                    $stok = Stok::find($tk->id_stok);
                    if ($stok) {
                        // 1. Kurangi jumlah stok di gudang (karena return dibatalkan)
                        $stok->jumlah -= $retur->jumlah;

                        // 2. Tambah kembali jumlah yang berada di luar (faskes)
                        $stok->jumlah_keluar += $retur->jumlah;

                        // 3. SINKRONISASI TOTAL (Paling Penting)
                        // Hitung ulang total_keluar berdasarkan perkalian terbaru
                        $stok->total_keluar = $stok->jumlah_keluar * $stok->harga;

                        // Hitung ulang total aset di gudang
                        $stok->total = $stok->jumlah * $stok->harga;

                        $stok->save();
                    }
                }

                // 4. Hapus data return
                $retur->delete();
            });

            $this->dispatch('closeDeleteModal');
            session()->flash('message', 'Data return berhasil dihapus dan stok luar telah disinkronkan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function resetValidationState()
    {
        $this->resetValidation();
    }

    public function sortBy($field)
    {
        if ($this->sort === $field) {
            if ($this->direction === 'asc') {
                $this->direction = 'desc';
            } elseif ($this->direction === 'desc') {
                // Reset ke default jika sudah klik 3x
                $this->direction = 'desc';
                $this->sort = 'created_at';
            } else {
                $this->direction = 'asc';
            }
        } else {
            $this->sort = $field;
            $this->direction = 'asc';
        }
        $this->resetPage();
    }



    public function render()
    {
        $query = TransaksiReturn::with([
            'transaksiKeluar.stok.obat',
            'transaksiKeluar.permintaan.faskes'
        ]);

        // 1. Filter Berdasarkan Tanggal/Bulan/Tahun (Jika diisi)
        $query->when($this->filterTanggal, function ($queryBuilder) {
            if (str_contains($this->filterTanggal, '-')) {
                // Jika formatnya Y-m-d (contoh: 2026-03-04)
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->filterTanggal)) {
                    $queryBuilder->whereDate('tanggal_return', $this->filterTanggal);
                } else {
                    // Jika formatnya Y-m (contoh: 2026-03)
                    $queryBuilder->whereMonth('tanggal_return', date('m', strtotime($this->filterTanggal)))
                        ->whereYear('tanggal_return', date('Y', strtotime($this->filterTanggal)));
                }
            } else {
                // Jika hanya angka 4 digit (contoh: 2026)
                $queryBuilder->whereYear('tanggal_return', $this->filterTanggal);
            }
        });

        // 2. Filter Berdasarkan Faskes (Jika dipilih)
        $query->when($this->filterFaskes, function ($queryBuilder) {
            $queryBuilder->whereHas('transaksiKeluar.permintaan', function($q) {
                 $q->where('faskes_id', $this->filterFaskes);
            });
        });

        // 3. Filter Pencarian Utama (Gunakan Closure agar filter tanggal tidak rusak oleh OR)
        $query->when($this->search, function ($queryBuilder) {
            $queryBuilder->where(function ($q) {
                $q->where('tanggal_return', 'like', '%' . $this->search . '%')
                    ->orWhere('status_stok', 'like', '%' . $this->search . '%')
                    ->orWhereHas('transaksiKeluar.stok.obat', function ($q2) {
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('transaksiKeluar.stok', function ($q2) {
                        $q2->where('batch_number', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('transaksiKeluar.permintaan.faskes', function ($q2) {
                        $q2->where('nama_faskes', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 3. Logika Sorting
        if ($this->sort && $this->direction) {
            // Gunakan select agar ID return tidak tertukar dengan ID tabel join
            $query->select('transaksi_return.*');

            if ($this->sort === 'faskes') {
                $query->join('transaksi_keluar', 'transaksi_return.id_transaksi_keluar', '=', 'transaksi_keluar.id')
                    ->join('permintaan', 'transaksi_keluar.id_permintaan', '=', 'permintaan.id')
                    ->join('faskes', 'permintaan.faskes_id', '=', 'faskes.id')
                    ->orderBy('faskes.nama_faskes', $this->direction);
            } elseif ($this->sort === 'obat') {
                $query->join('transaksi_keluar', 'transaksi_return.id_transaksi_keluar', '=', 'transaksi_keluar.id')
                    ->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'batch') {
                $query->join('transaksi_keluar', 'transaksi_return.id_transaksi_keluar', '=', 'transaksi_keluar.id')
                    ->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->orderBy('stok.batch_number', $this->direction);
            } else {
                // Untuk kolom asli di tabel transaksi_return (tanggal_return, jumlah, dll)
                $query->orderBy('transaksi_return.' . $this->sort, $this->direction);
            }
        } else {
            $query->orderBy('transaksi_return.created_at', 'desc');
        }


        return view('livewire.superadmin.transaksi_return.index', [
            'transaksiReturns' => $query->paginate($this->paginate),
            'listFaskes' => Faskes::orderBy('nama_faskes', 'asc')->get(),
            'tittle' => 'Transaksi Return Barang'
        ]);
    }
}
