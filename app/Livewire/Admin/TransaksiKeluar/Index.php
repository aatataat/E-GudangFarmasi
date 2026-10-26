<?php

namespace App\Livewire\admin\TransaksiKeluar;

use App\Models\Faskes;
use Livewire\Component;
use App\Models\TransaksiKeluar;
use App\Models\Permintaan;
use App\Models\Stok;
use App\Models\SumberAnggaran;
use App\Models\Supplier;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    // Properti untuk Filter
    public $filterTanggal = null;
    public $filterFaskes = null;

    // Properti Header Transaksi Multi-Item
    public $tanggal_keluar;
    public $faskes_id;

    // Properti Multi-Item (Digunakan untuk CREATE)
    public $items = [];
    public $grand_total = 0;

    // Properti Display (Digunakan untuk EDIT/UPDATE LAMA dan Tabel)
    public $transaksi_keluar_id;
    public $id_stok, $jumlah, $harga, $total; // <--- Digunakan di mode EDIT
    public $batch_number_display;
    public $supplier_display;
    public $sumber_anggaran_display;
    public $jumlah_stok_tersedia;
    public $batch_number;
    public $tanggal_kadaluarsa, $id_sumber_anggaran, $id_supplier, $id_permintaan;

    // ----------------------------------Single Item Edit Logic 🛠️----------------------------------------------- //

    // Dipanggil saat input 'jumlah' di modal Edit berubah
    public function updatedJumlah()
    {
        $this->computeTotal();
    }

    // Dipanggil saat input 'harga' di modal Edit berubah
    public function updatedHarga()
    {
        $this->computeTotal();
    }

    // Fungsi untuk menghitung total single item (Mode Edit)
    private function computeTotal()
    {
        // Pastikan nilai adalah integer untuk menghindari error 'string * int'
        $jumlah = (int) ($this->jumlah ?? 0);
        $harga = (int) ($this->harga ?? 0);

        $this->total = $jumlah * $harga;
    }

    // ----------------------------------Multi-Item Logic (CREATE) 🚀----------------------------------------------- //

    public function addItem()
    {
        $this->items[] = [
            'id_stok' => null,
            'jumlah' => 1,
            'harga' => 0,
            'total' => 0,
            'batch_number' => null,
            'tanggal_kadaluarsa' => null,
            'id_supplier' => null,
            'id_sumber_anggaran' => null,
            'stok_tersedia_for_item' => 0,
        ];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->calculateGrandTotal();
        } else {
            session()->flash('error', 'Minimal harus ada 1 item transaksi.');
        }
    }

    public function updatedItems($value, $key)
    {
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if ($field === 'id_stok' && $value) {
            $stok = Stok::find($value);
            if ($stok) {
                $this->items[$index]['harga'] = $stok->harga;
                $this->items[$index]['batch_number'] = $stok->batch_number;
                $this->items[$index]['tanggal_kadaluarsa'] = $stok->tanggal_kadaluarsa;
                $this->items[$index]['id_supplier'] = $stok->id_supplier;
                $this->items[$index]['id_sumber_anggaran'] = $stok->id_sumber_anggaran;
                $this->items[$index]['stok_tersedia_for_item'] = $stok->jumlah;
            } else {
                $this->items[$index]['harga'] = 0;
                $this->items[$index]['stok_tersedia_for_item'] = 0;
            }
        }

        // Mengubah nilai menjadi integer memastikan perkalian aman.
        $jumlah = (int) ($this->items[$index]['jumlah'] ?? 0);
        $harga = (int) ($this->items[$index]['harga'] ?? 0);

        $this->items[$index]['total'] = $jumlah * $harga;

        $this->calculateGrandTotal();
    }

    private function calculateGrandTotal()
    {
        $this->grand_total = 0;
        foreach ($this->items as $item) {
            $this->grand_total += (int) ($item['total'] ?? 0);
        }
    }

    // Dipakai untuk single item (untuk memuat harga saat id_stok di modal Edit/Update berubah)
    public function updatedIdStok($value)
    {
        $stok = Stok::with(['supplier', 'sumberAnggaran'])->find($value);
        if ($stok) {
            $this->harga = $stok->harga;
            $this->batch_number = $stok->batch_number;
            $this->tanggal_kadaluarsa = $stok->tanggal_kadaluarsa;
            $this->jumlah_stok_tersedia = $stok->jumlah;

            $this->id_supplier = $stok->id_supplier;
            $this->id_sumber_anggaran = $stok->id_sumber_anggaran;
            $this->supplier_display = $stok->supplier->nama_supplier ?? 'N/A';
            $this->sumber_anggaran_display = $stok->sumberAnggaran->nama_sumber_anggaran ?? 'N/A';
            // PENTING: Panggil computeTotal() setelah harga dan jumlah diatur
            $this->computeTotal();
        }
    }


    // ----------------------------------FUNGSI UTAMA LAINNYA----------------------------------------------- //

    public function sortBy($field)
    {
        if ($this->sort === $field) {
            if ($this->direction === 'asc') {
                $this->direction = 'desc';
            } elseif ($this->direction === 'desc') {
                $this->direction = null;
                $this->sort = null;
            } else {
                $this->direction = 'asc';
            }
        } else {
            $this->sort = $field;
            $this->direction = 'asc';
        }
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTanggal()
    {
        $this->resetPage();
    }

    public function updatingFilterFaskes()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = TransaksiKeluar::with(['stok.obat', 'stok.supplier', 'stok.sumberAnggaran', 'permintaan.faskes']);

        // 1. Filter Berdasarkan Tanggal/Bulan/Tahun (Jika diisi)
        $query->when($this->filterTanggal, function ($queryBuilder) {
            if (str_contains($this->filterTanggal, '-')) {
                // Jika formatnya Y-m-d (contoh: 2026-01-20)
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->filterTanggal)) {
                    $queryBuilder->whereDate('tanggal_keluar', $this->filterTanggal);
                } else {
                    // Jika formatnya Y-m (contoh: 2026-01)
                    $queryBuilder->whereMonth('tanggal_keluar', date('m', strtotime($this->filterTanggal)))
                        ->whereYear('tanggal_keluar', date('Y', strtotime($this->filterTanggal)));
                }
            } else {
                // Jika hanya angka 4 digit (contoh: 2026)
                $queryBuilder->whereYear('tanggal_keluar', $this->filterTanggal);
            }
        });

        // 2. Filter Berdasarkan Faskes (Jika dipilih)
        $query->when($this->filterFaskes, function ($queryBuilder) {
            $queryBuilder->whereHas('permintaan', function ($q) {
                $q->where('faskes_id', $this->filterFaskes);
            });
        });

        // 3. Filter Berdasarkan Input Pencarian (Kerjasama dengan filter lainnya)
        $query->when($this->search, function ($queryBuilder) {
            // Dibungkus closure agar OR tidak mematalkan filter tanggal/faskes di atas
            $queryBuilder->where(function ($q) {
                $q->where('tanggal_keluar', 'like', '%' . $this->search . '%')
                ->orWhere('tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                    ->orWhereHas('stok', function ($q2) {
                        $q2->where('batch_number', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.obat', function ($q2) {
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            // Menambahkan pencarian berdasarkan satuan juga
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('permintaan.faskes', function ($q2) {
                        $q2->where('nama_faskes', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 4. Logika Sorting
        if ($this->sort && $this->direction) {
            // Gunakan select untuk menghindari bentrokan ID antar tabel
            $query->select('transaksi_keluar.*');

            if ($this->sort === 'nama_obat') {
                $query->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'nama_faskes') {
                $query->join('permintaan', 'transaksi_keluar.id_permintaan', '=', 'permintaan.id')
                    ->join('faskes', 'permintaan.faskes_id', '=', 'faskes.id')
                    ->orderBy('faskes.nama_faskes', $this->direction);
            } elseif ($this->sort === 'satuan') {
                // Tambahan sorting berdasarkan satuan untuk Transaksi Keluar
                $query->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction);
            } else {
                $query->orderBy('transaksi_keluar.' . $this->sort, $this->direction);
            }
        } else {
            $query->orderBy('transaksi_keluar.created_at', 'desc');
        }

        $transaksiKeluars = $query->paginate($this->paginate);

        $availableStoks = Stok::where('jumlah', '>', 0)
            ->whereDate('tanggal_kadaluarsa', '>', now())
            ->with(['obat', 'supplier', 'sumberAnggaran'])
            ->orderBy('obat_id', 'asc')
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->get();

        $allFaskes = Faskes::orderBy('nama_faskes')->get();

        if (empty($this->items)) {
            $this->addItem();
        }

        $data = [
            'tittle' => 'Data Transaksi Keluar',
            'transaksiKeluars' => $transaksiKeluars,
            'permintaans' => Permintaan::all(),
            'stoks' => Stok::all(),
            'sumberAnggarans' => SumberAnggaran::all(),
            'suppliers' => Supplier::all(),
            'faskesList' => Faskes::orderBy('nama_faskes')->get(),
            'availableStoks' => $availableStoks,
            'allFaskes' => $allFaskes,
        ];

        return view('livewire.admin.transaksi_keluar.index', $data);
    }

    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);
    }

    // ----------------------------------CREATE MULTI-ITEM----------------------------------------------- //
    public function create()
    {
        $this->resetValidation();
        $this->reset(['tanggal_keluar', 'faskes_id', 'jumlah', 'harga', 'total']); // Reset properti single item juga
        $this->items = [];
        $this->addItem();
        $this->grand_total = 0;
    }

    public function store()
    {
        $rules = [
            'tanggal_keluar' => 'required|date',
            'faskes_id' => 'required|exists:faskes,id',
            'items' => 'required|array|min:1',
            'items.*.id_stok' => 'required|exists:stok,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|integer|min:0',
        ];

        foreach ($this->items as $index => $item) {
            $stok = Stok::find($item['id_stok']);
            $max_stok = $stok ? $stok->jumlah : 0;
            $rules['items.' . $index . '.jumlah'] = 'required|integer|min:1|max:' . $max_stok;
        }

        $messages = [
            'tanggal_keluar.required' => 'Tanggal Keluar wajib diisi.',
            'faskes_id.required' => 'Tujuan Faskes wajib diisi.',
            'items.min' => 'Minimal harus ada 1 item transaksi.',
            'items.*.id_stok.required' => 'Obat/Stok di baris :attribute wajib diisi.',
            'items.*.jumlah.required' => 'Jumlah di baris :attribute wajib diisi.',
            'items.*.jumlah.max' => 'Jumlah stok (tersedia :max) tidak mencukupi untuk baris ini.',
            'items.*.harga.required' => 'Harga di baris :attribute wajib diisi.',
        ];

        $this->validate($rules, $messages);

        DB::transaction(function () {
            foreach ($this->items as $item) {
                $stok = Stok::lockForUpdate()->find($item['id_stok']);

                if (!$stok || $stok->jumlah < $item['jumlah']) {
                    throw new \Exception('Stok tidak mencukupi untuk item.');
                }

                $transaksiKeluar = new TransaksiKeluar;
                $transaksiKeluar->tanggal_keluar = $this->tanggal_keluar;
                $transaksiKeluar->faskes_id = $this->faskes_id;
                $transaksiKeluar->id_stok = $item['id_stok'];
                $transaksiKeluar->jumlah = $item['jumlah'];
                $transaksiKeluar->harga = $item['harga'];
                $transaksiKeluar->total = $item['total'];
                $transaksiKeluar->batch_number = $item['batch_number'];
                $transaksiKeluar->tanggal_kadaluarsa = $item['tanggal_kadaluarsa'];
                $transaksiKeluar->id_sumber_anggaran = $item['id_sumber_anggaran'];
                $transaksiKeluar->id_supplier = $item['id_supplier'];
                $transaksiKeluar->save();

                $stok->jumlah -= $item['jumlah'];
                $stok->jumlah_keluar += $item['jumlah'];
                $stok->harga_keluar = $item['harga'];
                $stok->total_keluar += $item['total'];
                $stok->total = $stok->jumlah * $stok->harga;
                $stok->save();
            }
        });

        $this->dispatch('closeCreateModal');
        session()->flash('message', 'Transaksi Keluar (Multi-Item) berhasil ditambahkan.');
    }

    // ----------------------------------CRUD Data (LAMA)----------------------------------------------- //

    public function edit($id)
    {
        $this->resetValidation();
        $this->reset(['items', 'grand_total']);

        // Pastikan memuat relasi permintaan.faskes dan stok.obat
        $transaksiKeluar = TransaksiKeluar::with([
            'stok.obat',
            'stok.supplier',
            'stok.sumberAnggaran',
            'permintaan.faskes'
        ])->findOrFail($id);

        $this->transaksi_keluar_id = $transaksiKeluar->id;
        $this->tanggal_keluar = $transaksiKeluar->tanggal_keluar->format('Y-m-d');
        $this->id_stok = $transaksiKeluar->id_stok;
        $this->jumlah = $transaksiKeluar->jumlah;
        $this->harga = $transaksiKeluar->harga;
        $this->total = $transaksiKeluar->total;

        // Ambil data untuk tampilan modal agar tidak (N/A)
        $stok = $transaksiKeluar->stok;
        $namaObat = $stok->obat->nama_obat ?? 'N/A';
        $this->batch_number_display = $namaObat . " - " . $stok->batch_number; // Ini yang muncul di modal

        $this->batch_number = $transaksiKeluar->batch_number;
        $this->tanggal_kadaluarsa = $transaksiKeluar->tanggal_kadaluarsa->format('Y-m-d');

        // Ambil Faskes ID dari relasi permintaan
        $this->faskes_id = $transaksiKeluar->permintaan->faskes_id ?? null;

        $this->jumlah_stok_tersedia = $stok->jumlah + $this->jumlah;

        $this->id_supplier = $stok->id_supplier;
        $this->id_sumber_anggaran = $stok->id_sumber_anggaran;
        $this->supplier_display = $stok->supplier->nama_supplier ?? 'N/A';
        $this->sumber_anggaran_display = $stok->sumberAnggaran->nama_sumber_anggaran ?? 'N/A';

        $this->computeTotal();
    }

    public function update($id)
    {
        $transaksiKeluar = TransaksiKeluar::findOrFail($id);
        $originalJumlah = $transaksiKeluar->jumlah;
        $originalIdStok = $transaksiKeluar->id_stok;

        $stok = Stok::findOrFail($this->id_stok);
        $stok_tersedia = $stok->jumlah;
        if ($originalIdStok == $this->id_stok) {
            $stok_tersedia += $originalJumlah;
        }

        $this->computeTotal();

        $this->validate([
            'tanggal_keluar' => 'required|date',
            'id_stok' => 'required|exists:stok,id',
            'jumlah' => 'required|integer|min:1|max:' . $stok_tersedia,
            'harga' => 'required|integer',
            'total' => 'required|integer',
            'batch_number' => 'required|string',
            'tanggal_kadaluarsa' => 'required|date',
            // faskes_id dihapus dari validasi ini jika tidak ingin diubah
        ], [
            'jumlah.max' => 'Jumlah stok tidak mencukupi untuk transaksi ini.',
        ]);

        try {
            DB::beginTransaction();

            // Logika Update Stok (Sesuai kode asli Anda)
            if ($originalIdStok != $this->id_stok) {
                $originalStok = Stok::find($originalIdStok);
                if ($originalStok) {
                    $originalStok->jumlah += $originalJumlah;
                    $originalStok->jumlah_keluar -= $originalJumlah;
                    $originalStok->total_keluar -= $transaksiKeluar->total;
                    $originalStok->total = $originalStok->jumlah * $originalStok->harga;
                    $originalStok->save();
                }

                $stok->jumlah -= $this->jumlah;
                $stok->increment('jumlah_keluar', $this->jumlah);
                $stok->increment('total_keluar', $this->total);
            } else {
                $diffJumlah = $this->jumlah - $originalJumlah;
                $stok->jumlah -= $diffJumlah;
                $stok->jumlah_keluar += $diffJumlah;
                $stok->total_keluar += ($this->total - $transaksiKeluar->total);
            }
            $stok->total = $stok->jumlah * $stok->harga;
            $stok->save();

            // Simpan ke TransaksiKeluar (Tanpa faskes_id)
            $transaksiKeluar->update([

                'jumlah'            => $this->jumlah,
                'harga'             => $this->harga,
                'total'             => $this->total,

                // 'faskes_id' dihilangkan karena tidak ada di tabel ini
            ]);

            DB::commit();
            $this->dispatch('closeEditModal');
            session()->flash('message', 'Transaksi Keluar berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function confirm($id)
    {
        $transaksiKeluar = TransaksiKeluar::with(['stok.supplier', 'stok.sumberAnggaran', 'faskes'])->findOrFail($id);
        $this->transaksi_keluar_id = $transaksiKeluar->id;
        $this->tanggal_keluar = $transaksiKeluar->tanggal_keluar;
        $this->jumlah = $transaksiKeluar->jumlah;
        $this->harga = $transaksiKeluar->harga;
        $this->total = $transaksiKeluar->total;
        $this->tanggal_kadaluarsa = $transaksiKeluar->tanggal_kadaluarsa;
        $this->faskes_id = $transaksiKeluar->faskes_id;
        $this->batch_number_display = $transaksiKeluar->stok->batch_number;
        $this->supplier_display = $transaksiKeluar->stok->supplier->nama_supplier ?? 'N/A';
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $transaksiKeluar = TransaksiKeluar::findOrFail($id);
            $stok = Stok::lockForUpdate()->find($transaksiKeluar->id_stok);

            if ($stok) {
                // 1. Kembalikan jumlah sisa stok
                $stok->jumlah += $transaksiKeluar->jumlah;

                // 2. Kurangi akumulasi jumlah yang sudah keluar
                $stok->jumlah_keluar -= $transaksiKeluar->jumlah;

                // 3. Kurangi akumulasi total nilai keluar
                // Kita gunakan max(0, ...) untuk memastikan nilai tidak pernah negatif
                $stok->total_keluar = max(0, $stok->total_keluar - $transaksiKeluar->total);

                // 4. Hitung ulang total sisa nilai aset stok (Sisa Jumlah * Harga Satuan)
                $stok->total = $stok->jumlah * $stok->harga;

                // Jika jumlah_keluar kembali ke 0, sebaiknya harga_keluar juga di-reset (opsional)
                if ($stok->jumlah_keluar <= 0) {
                    $stok->jumlah_keluar = 0;
                    $stok->harga_keluar = 0;
                }

                $stok->save();
            }

            // Hapus data transaksi
            $transaksiKeluar->delete();

            DB::commit();
            $this->dispatch('closeDeleteModal');
            session()->flash('message', 'Transaksi Keluar berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
