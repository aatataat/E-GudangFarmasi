<?php

namespace App\Livewire\Superadmin\LapTransaksiKeluar;

use App\Models\Faskes;
use Livewire\Component;
use App\Models\TransaksiKeluar;
use App\Models\Stok;
use App\Models\SumberAnggaran;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;

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

    public $transaksi_keluar_id;
    public $tanggal_keluar, $id_stok, $jumlah, $harga, $total;

    // Properti untuk menampilkan data terkait dari stok
    public $batch_number_display;
    public $supplier_display;
    public $sumber_anggaran_display;
    public $jumlah_stok_tersedia;

    // Menambahkan properti untuk mengatasi error database
    public $batch_number;
    public $tanggal_kadaluarsa, $id_sumber_anggaran, $id_supplier;

    // 🔴 PERBAIKAN KRUSIAL DI SINI:
    // Mengganti $id_faskes menjadi $faskes_id agar sesuai dengan wire:model="faskes_id" di view.
    public $faskes_id; // 🟢 PROPERTI BARU YANG BENAR

    // ----------------------------------Menampilkan Data----------------------------------------------- //
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
                    ->orWhereHas('stok', function ($q2) {
                        $q2->where('batch_number', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.obat', function ($q2) {
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            // Menambahkan pencarian berdasarkan satuan juga
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('kategori', function ($q3) {
                                $q3->where('nama_kategori', 'like', '%' . $this->search . '%');
                            });
                            
                    })
                    ->orWhereHas('permintaan.faskes', function ($q2) {
                        $q2->where('nama_faskes', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.sumberAnggaran', function ($q2) {
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 4. Logika Sorting
        if ($this->sort && $this->direction) {
            // Gunakan select untuk menghindari bentrokan ID antar tabel
            $query->select('transaksi_keluar.*');
            if ($this->sort === 'sumber_anggaran') {
                $query->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->join('sumber_anggaran', 'stok.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction);
            } elseif ($this->sort === 'supplier') {
                $query->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->join('supplier', 'stok.id_supplier', '=', 'supplier.id')
                    ->orderBy('supplier.nama_supplier', $this->direction);
            } elseif ($this->sort === 'nama_obat') {
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
            } elseif ($this->sort === 'kategori') {
                // Tambahan sorting berdasarkan kategori untuk Transaksi Keluar
                $query->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction);
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
            ->get();

        $allFaskes = Faskes::orderBy('nama_faskes')->get();

        $data = [
            'tittle' => 'Data Transaksi Keluar',
            'transaksiKeluars' => $transaksiKeluars,
            'stoks' => Stok::all(),
            'sumberAnggarans' => SumberAnggaran::all(),
            'suppliers' => Supplier::all(),
            'faskesList' => Faskes::orderBy('nama_faskes')->get(),
            'availableStoks' => $availableStoks,
            'allFaskes' => $allFaskes,
        ];

        return view('livewire.superadmin.Laptransaksi_keluar.index', $data);
    }

    // ----------------------------------Mereset Validation----------------------------------------------- //
    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);
    }

    // Menghitung total saat jumlah atau harga berubah
    public function updatedJumlah($value)
    {
        $this->computeTotal();
    }

    public function updatedHarga($value)
    {
        $this->computeTotal();
    }

    private function computeTotal()
    {
        if (is_numeric($this->jumlah) && is_numeric($this->harga)) {
            $this->total = $this->jumlah * $this->harga;
        } else {
            $this->total = null;
        }
    }

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
            $this->computeTotal();
        }
    }

    // ----------------------------------CRUD Data----------------------------------------------- //
    // Tambah
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'tanggal_keluar',
            'id_stok',
            'jumlah',
            'harga',
            'total',
            'batch_number',
            'tanggal_kadaluarsa',
            'id_sumber_anggaran',
            'id_supplier',
            'supplier_display',
            'sumber_anggaran_display',
            'jumlah_stok_tersedia',
            'faskes_id' // 🟢 Perbaikan: Menggunakan $faskes_id
        ]);
    }

    public function store()
    {
        $stok = Stok::find($this->id_stok);

        $this->validate(
            [
                'tanggal_keluar' => 'required|date',
                'id_stok' => 'required|exists:stok,id',
                'jumlah' => 'required|integer|min:1|max:' . ($stok ? $stok->jumlah : 0),
                'harga' => 'required|integer',
                'total' => 'required|integer',
                'batch_number' => 'required|string',
                'tanggal_kadaluarsa' => 'required|date',
                // 🔴 Perbaikan: Menggunakan faskes_id untuk validasi
                'faskes_id' => 'required|exists:faskes,id'
            ],
            [
                'tanggal_keluar.required' => 'Tanggal Keluar tidak boleh kosong',
                'id_stok.required' => 'Stok tidak boleh kosong',
                'id_stok.exists' => 'Stok tidak ditemukan',
                'jumlah.required' => 'Jumlah tidak boleh kosong',
                'jumlah.integer' => 'Jumlah harus berupa angka',
                'jumlah.min' => 'Jumlah minimal 1',
                'jumlah.max' => 'Jumlah stok tidak mencukupi untuk transaksi ini.',
                'harga.required' => 'Harga tidak boleh kosong',
                'harga.integer' => 'Harga harus berupa angka',
                'total.required' => 'Total tidak boleh kosong',
                'total.integer' => 'Total harus berupa angka',
                'batch_number.required' => 'Batch Number tidak boleh kosong',
                'tanggal_kadaluarsa.required' => 'Tanggal Kadaluarsa tidak boleh kosong',
                // 🔴 Perbaikan: Menggunakan faskes_id untuk pesan error
                'faskes_id.exists' => 'Faskes tujuan tidak ditemukan.'
            ]
        );

        $transaksiKeluar = new TransaksiKeluar;
        $transaksiKeluar->tanggal_keluar = $this->tanggal_keluar;
        $transaksiKeluar->id_stok = $this->id_stok;
        $transaksiKeluar->jumlah = $this->jumlah;
        $transaksiKeluar->harga = $this->harga;
        $transaksiKeluar->total = $this->total;
        $transaksiKeluar->batch_number = $this->batch_number;
        $transaksiKeluar->tanggal_kadaluarsa = $this->tanggal_kadaluarsa;
        $transaksiKeluar->id_sumber_anggaran = $this->id_sumber_anggaran;
        $transaksiKeluar->id_supplier = $this->id_supplier;
        // 🔴 Perbaikan: Menggunakan $this->faskes_id
        $transaksiKeluar->faskes_id = $this->faskes_id;
        $transaksiKeluar->save();

        // Perbarui data stok
        $stok->jumlah -= $this->jumlah;
        $stok->jumlah_keluar += $this->jumlah;
        $stok->harga_keluar = $this->harga;
        $stok->total_keluar += $this->total;
        $stok->total = $stok->jumlah * $stok->harga;
        $stok->save();

        $this->dispatch('closeCreateModal');
        session()->flash('message', 'Transaksi Keluar berhasil ditambahkan.');
    }

    // Edit
    public function edit($id)
    {
        $this->resetValidation();
        $transaksiKeluar = TransaksiKeluar::with(['stok.supplier', 'stok.sumberAnggaran', 'faskes'])->findOrFail($id);
        $this->transaksi_keluar_id = $transaksiKeluar->id;
        $this->tanggal_keluar = $transaksiKeluar->tanggal_keluar->format('Y-m-d');
        $this->id_stok = $transaksiKeluar->id_stok;
        $this->jumlah = $transaksiKeluar->jumlah;
        $this->harga = $transaksiKeluar->harga;
        $this->total = $transaksiKeluar->total;
        $this->batch_number = $transaksiKeluar->batch_number;
        $this->tanggal_kadaluarsa = $transaksiKeluar->tanggal_kadaluarsa->format('Y-m-d');

        // 🔴 Perbaikan: Muat faskes_id ke properti $faskes_id
        $this->faskes_id = $transaksiKeluar->faskes_id;

        $stok = $transaksiKeluar->stok;
        $this->jumlah_stok_tersedia = $stok->jumlah + $this->jumlah; // Hitung ulang sisa stok untuk validasi

        $this->id_supplier = $stok->id_supplier;
        $this->id_sumber_anggaran = $stok->id_sumber_anggaran;
        $this->supplier_display = $stok->supplier->nama_supplier ?? 'N/A';
        $this->sumber_anggaran_display = $stok->sumberAnggaran->nama_sumber_anggaran ?? 'N/A';
    }

    public function update($id)
    {
        $transaksiKeluar = TransaksiKeluar::findOrFail($id);
        $originalJumlah = $transaksiKeluar->jumlah;
        $originalIdStok = $transaksiKeluar->id_stok;

        $stok = Stok::find($this->id_stok);
        $stok_tersedia = $stok->jumlah;
        if ($originalIdStok == $this->id_stok) {
            $stok_tersedia += $originalJumlah;
        }

        $this->validate(
            [
                'tanggal_keluar' => 'required|date',
                'id_stok' => 'required|exists:stok,id',
                'jumlah' => 'required|integer|min:1|max:' . $stok_tersedia,
                'harga' => 'required|integer',
                'total' => 'required|integer',
                'batch_number' => 'required|string',
                'tanggal_kadaluarsa' => 'required|date',
                // 🔴 Perbaikan: Menggunakan faskes_id untuk validasi
                'faskes_id' => 'required|exists:faskes,id'
            ],
            [
                'tanggal_keluar.required' => 'Tanggal Keluar tidak boleh kosong',
                'id_stok.required' => 'Stok tidak boleh kosong',
                'id_stok.exists' => 'Stok tidak ditemukan',
                'jumlah.required' => 'Jumlah tidak boleh kosong',
                'jumlah.integer' => 'Jumlah harus berupa angka',
                'jumlah.min' => 'Jumlah minimal 1',
                'jumlah.max' => 'Jumlah stok tidak mencukupi untuk transaksi ini.',
                'harga.required' => 'Harga tidak boleh kosong',
                'harga.integer' => 'Harga harus berupa angka',
                'total.required' => 'Total tidak boleh kosong',
                'total.integer' => 'Total harus berupa angka',
                'batch_number.required' => 'Batch Number tidak boleh kosong',
                'tanggal_kadaluarsa.required' => 'Tanggal Kadaluarsa tidak boleh kosong',
                // 🔴 Perbaikan: Menggunakan faskes_id untuk pesan error
                'faskes_id.exists' => 'Faskes tujuan tidak ditemukan.'
            ]
        );

        if ($originalIdStok != $this->id_stok) {
            $originalStok = Stok::find($originalIdStok);
            $originalStok->jumlah += $originalJumlah;
            $originalStok->jumlah_keluar -= $originalJumlah;
            $originalStok->total_keluar -= $transaksiKeluar->total;
            $originalStok->total = $originalStok->jumlah * $originalStok->harga;
            $originalStok->save();

            $stok->jumlah -= $this->jumlah;
            $stok->jumlah_keluar += $this->jumlah;
            $stok->total_keluar += $this->total;
            $stok->total = $stok->jumlah * $stok->harga;
            $stok->save();
        } else {
            $diffJumlah = $this->jumlah - $originalJumlah;
            $stok->jumlah -= $diffJumlah;
            $stok->jumlah_keluar += $diffJumlah;
            $stok->total_keluar += ($this->total - $transaksiKeluar->total);
            $stok->total = $stok->jumlah * $stok->harga;
            $stok->save();
        }

        $transaksiKeluar->tanggal_keluar = $this->tanggal_keluar;
        $transaksiKeluar->id_stok = $this->id_stok;
        $transaksiKeluar->jumlah = $this->jumlah;
        $transaksiKeluar->harga = $this->harga;
        $transaksiKeluar->total = $this->total;
        $transaksiKeluar->batch_number = $this->batch_number;
        $transaksiKeluar->tanggal_kadaluarsa = $this->tanggal_kadaluarsa;
        // 🔴 Perbaikan: Update faskes_id menggunakan $this->faskes_id
        $transaksiKeluar->faskes_id = $this->faskes_id;
        $transaksiKeluar->save();

        $this->dispatch('closeEditModal');
        session()->flash('message', 'Transaksi Keluar berhasil diperbarui.');
    }

    // Hapus
    public function confirm($id)
    {
        // Mengubah TransaksiMasuk menjadi TransaksiKeluar
        $transaksiKeluar = TransaksiKeluar::with(['stok.supplier', 'stok.sumberAnggaran', 'faskes'])->findOrFail($id);

        // Muat semua properti yang dibutuhkan modal
        $this->transaksi_keluar_id = $transaksiKeluar->id;
        $this->tanggal_keluar = $transaksiKeluar->tanggal_keluar; // Format di-handle di view
        $this->jumlah = $transaksiKeluar->jumlah;
        $this->harga = $transaksiKeluar->harga;
        $this->total = $transaksiKeluar->total;
        $this->tanggal_kadaluarsa = $transaksiKeluar->tanggal_kadaluarsa; // Format di-handle di view
        $this->faskes_id = $transaksiKeluar->faskes_id; // Untuk menampilkan Faskes Tujuan

        // Properti dari Relasi Stok
        $this->batch_number_display = $transaksiKeluar->stok->batch_number;
        $this->supplier_display = $transaksiKeluar->stok->supplier->nama_supplier ?? 'N/A';

        // Catatan: Variabel lain seperti obat dan sumber anggaran bisa diakses melalui relasi jika dibutuhkan, 
        // tapi modal di atas sudah dimodifikasi untuk mencari Faskes dan menggunakan properti display yang ada.
    }

    public function destroy($id)
    {
        $transaksiKeluar = TransaksiKeluar::findOrFail($id);
        $stok = $transaksiKeluar->stok;

        // Kembalikan jumlah stok yang sudah dikeluarkan
        $stok->jumlah += $transaksiKeluar->jumlah;
        $stok->jumlah_keluar -= $transaksiKeluar->jumlah;
        $stok->total_keluar -= $transaksiKeluar->total;
        $stok->total = $stok->jumlah * $stok->harga;
        $stok->save();

        $transaksiKeluar->delete();
        $this->dispatch('closeDeleteModal');
        session()->flash('message', 'Transaksi Keluar berhasil dihapus.');
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
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
                    ->orWhereHas('stok', function ($q2) {
                        $q2->where('batch_number', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.obat', function ($q2) {
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            // Menambahkan pencarian berdasarkan satuan juga
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('kategori', function ($q3) {
                                $q3->where('nama_kategori', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('permintaan.faskes', function ($q2) {
                        $q2->where('nama_faskes', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.sumberAnggaran', function ($q2) {
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 4. Logika Sorting
        if ($this->sort && $this->direction) {
            // Gunakan select untuk menghindari bentrokan ID antar tabel
            $query->select('transaksi_keluar.*');
            if ($this->sort === 'sumber_anggaran') {
                $query->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->join('sumber_anggaran', 'stok.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction);
            } elseif ($this->sort === 'supplier') {
                $query->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->join('supplier', 'stok.id_supplier', '=', 'supplier.id')
                    ->orderBy('supplier.nama_supplier', $this->direction);
            } elseif ($this->sort === 'nama_obat') {
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
            } elseif ($this->sort === 'kategori') {
                // Tambahan sorting berdasarkan kategori untuk Transaksi Keluar
                $query->join('stok', 'transaksi_keluar.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction);
            } else {
                $query->orderBy('transaksi_keluar.' . $this->sort, $this->direction);
            }
        } else {
            $query->orderBy('transaksi_keluar.created_at', 'desc');
        }

        $transaksi_keluarsToExport = $query->get();
        $fileName = 'Daftar-TransaksiKeluar_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        $pdf = Pdf::loadView('pdfs.Laptransaksi_keluars', ['transaksi_keluar' => $transaksi_keluarsToExport]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }
}
