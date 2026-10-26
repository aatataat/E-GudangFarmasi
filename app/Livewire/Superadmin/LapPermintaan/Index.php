<?php

namespace App\Livewire\SuperAdmin\LapPermintaan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PermintaanBarang;
use App\Models\Faskes;
use App\Models\Stok;
use App\Models\SumberAnggaran;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $paginate = 10;

    // Properti Pencarian & Filter
    public $search = '';
    public $filterStatus = '';
    public $filterFaskes = null;
    public $filterTanggal = null;

    // Properti Pengurutan / Sorting
    public $sort = 'created_at';
    public $direction = 'desc';

    // Reset halaman pagination jika filter berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    public function updatingFilterFaskes()
    {
        $this->resetPage();
    }
    public function updatingFilterTanggal()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sort === $column) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $column;
            $this->direction = 'asc';
        }
    }

    public function render()
    {
        $query = PermintaanBarang::with([
            'permintaan.faskes',
            'stok.obat.kategori',
            'stok.obat.satuan',
            'stok.supplier',
            'stok.sumberAnggaran'
        ]);

        // 1. Filter Berdasarkan Tanggal/Bulan/Tahun (Jika diisi)
        $query->when($this->filterTanggal, function ($queryBuilder) {
            $queryBuilder->whereHas('permintaan', function ($q) {
                if (str_contains($this->filterTanggal, '-')) {
                    // Jika format Y-m-d
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->filterTanggal)) {
                        $q->whereDate('tanggal_surat', $this->filterTanggal);
                    } else {
                        // Jika format Y-m
                        $q->whereMonth('tanggal_surat', date('m', strtotime($this->filterTanggal)))
                            ->whereYear('tanggal_surat', date('Y', strtotime($this->filterTanggal)));
                    }
                } else {
                    // Jika format Y (Tahun Saja)
                    $q->whereYear('tanggal_surat', $this->filterTanggal);
                }
            });
        });

        // 2. Filter Berdasarkan Faskes (Jika dipilih)
        $query->when($this->filterFaskes, function ($queryBuilder) {
            $queryBuilder->whereHas('permintaan', function ($q) {
                $q->where('faskes_id', $this->filterFaskes);
            });
        });

        // 3. Filter Berdasarkan Status Permintaan (Opsi tambahan dropdown Anda)
        $query->when($this->filterStatus, function ($queryBuilder) {
            $queryBuilder->whereHas('permintaan', function ($q) {
                $q->where('status', $this->filterStatus);
            });
        });

        // 4. Filter Berdasarkan Input Pencarian (Searching Semua Kecuali Angka Jumlah)
        $query->when($this->search, function ($queryBuilder) {
            $queryBuilder->where(function ($q) {
                $q->whereHas('permintaan', function ($q2) {
                    $q2->where('nomor_surat', 'like', '%' . $this->search . '%')
                        ->orWhere('tanggal_surat', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('permintaan.faskes', function ($q2) {
                        $q2->where('nama_faskes', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.obat', function ($q2) {
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('kategori', function ($q3) {
                                $q3->where('nama_kategori', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('stok.supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.sumberAnggaran', function ($q2) {
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 5. Logika Sorting (Menggunakan struktur Join manual persis kode TransaksiKeluar Anda)
        if ($this->sort && $this->direction) {
            // Mencegah bentrokan ID field dengan memaksa select tabel utama
            $query->select('permintaan_barang.*');

            if ($this->sort === 'nomor_surat') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->orderBy('permintaan.nomor_surat', $this->direction);
            } elseif ($this->sort === 'tanggal_surat') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->orderBy('permintaan.tanggal_surat', $this->direction);
            } elseif ($this->sort === 'nama_faskes') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->join('faskes', 'permintaan.faskes_id', '=', 'faskes.id')
                    ->orderBy('faskes.nama_faskes', $this->direction);
            } elseif ($this->sort === 'status') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->orderBy('permintaan.status', $this->direction);
            } elseif ($this->sort === 'nama_obat') {
                $query->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'kategori') {
                $query->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction);
            } elseif ($this->sort === 'satuan') {
                $query->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction);
            } elseif ($this->sort === 'harga_satuan') {
                $query->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
                    ->orderBy('stok.harga', $this->direction);
            } elseif ($this->sort === 'created_at') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->orderBy('permintaan.created_at', $this->direction);
            } else {
                $query->orderBy('permintaan_barang.' . $this->sort, $this->direction);
            }
        } else {
            // Default Sorting jika properti kosong
            $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                ->orderBy('permintaan.created_at', 'desc')
                ->select('permintaan_barang.*');
        }

        // Ambil hasil data ber-pagination
        $permintaanItems = $query->paginate($this->paginate);

        // Kumpulan data array pendukung persis gaya response kode Anda
        $data = [
            'tittle' => 'Laporan Permintaan Logistik',
            'permintaanItems' => $permintaanItems,
            'stoks' => Stok::all(),
            'sumberAnggarans' => SumberAnggaran::all(),
            'suppliers' => Supplier::all(),
            'faskesList' => Faskes::orderBy('nama_faskes')->get(),
            'allFaskes' => Faskes::orderBy('nama_faskes')->get(),
            'availableStatuses' => ['DIAJUKAN', 'DITERIMA', 'DITOLAK']
        ];

        return view('livewire.superadmin.Lappermintaan.index', $data);
    }


    public function exportPdf()
    {
        // Kode ekspor mengikuti buildQuery yang baru agar filter pencarian tetap ikut tercetak
        $query = PermintaanBarang::with([
            'permintaan.faskes',
            'stok.obat.kategori',
            'stok.obat.satuan',
            'stok.supplier',
            'stok.sumberAnggaran'
        ]);

        // 1. Filter Berdasarkan Tanggal/Bulan/Tahun (Jika diisi)
        $query->when($this->filterTanggal, function ($queryBuilder) {
            $queryBuilder->whereHas('permintaan', function ($q) {
                if (str_contains($this->filterTanggal, '-')) {
                    // Jika format Y-m-d
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->filterTanggal)) {
                        $q->whereDate('tanggal_surat', $this->filterTanggal);
                    } else {
                        // Jika format Y-m
                        $q->whereMonth('tanggal_surat', date('m', strtotime($this->filterTanggal)))
                            ->whereYear('tanggal_surat', date('Y', strtotime($this->filterTanggal)));
                    }
                } else {
                    // Jika format Y (Tahun Saja)
                    $q->whereYear('tanggal_surat', $this->filterTanggal);
                }
            });
        });

        // 2. Filter Berdasarkan Faskes (Jika dipilih)
        $query->when($this->filterFaskes, function ($queryBuilder) {
            $queryBuilder->whereHas('permintaan', function ($q) {
                $q->where('faskes_id', $this->filterFaskes);
            });
        });

        // 3. Filter Berdasarkan Status Permintaan (Opsi tambahan dropdown Anda)
        $query->when($this->filterStatus, function ($queryBuilder) {
            $queryBuilder->whereHas('permintaan', function ($q) {
                $q->where('status', $this->filterStatus);
            });
        });

        // 4. Filter Berdasarkan Input Pencarian (Searching Semua Kecuali Angka Jumlah)
        $query->when($this->search, function ($queryBuilder) {
            $queryBuilder->where(function ($q) {
                $q->whereHas('permintaan', function ($q2) {
                    $q2->where('nomor_surat', 'like', '%' . $this->search . '%')
                        ->orWhere('tanggal_surat', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('permintaan.faskes', function ($q2) {
                        $q2->where('nama_faskes', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.obat', function ($q2) {
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('kategori', function ($q3) {
                                $q3->where('nama_kategori', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('stok.supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stok.sumberAnggaran', function ($q2) {
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 5. Logika Sorting (Menggunakan struktur Join manual persis kode TransaksiKeluar Anda)
        if ($this->sort && $this->direction) {
            // Mencegah bentrokan ID field dengan memaksa select tabel utama
            $query->select('permintaan_barang.*');

            if ($this->sort === 'nomor_surat') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->orderBy('permintaan.nomor_surat', $this->direction);
            } elseif ($this->sort === 'tanggal_surat') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->orderBy('permintaan.tanggal_surat', $this->direction);
            } elseif ($this->sort === 'nama_faskes') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->join('faskes', 'permintaan.faskes_id', '=', 'faskes.id')
                    ->orderBy('faskes.nama_faskes', $this->direction);
            } elseif ($this->sort === 'status') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->orderBy('permintaan.status', $this->direction);
            } elseif ($this->sort === 'nama_obat') {
                $query->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'kategori') {
                $query->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction);
            } elseif ($this->sort === 'satuan') {
                $query->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
                    ->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction);
            } elseif ($this->sort === 'harga_satuan') {
                $query->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
                    ->orderBy('stok.harga', $this->direction);
            } elseif ($this->sort === 'created_at') {
                $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                    ->orderBy('permintaan.created_at', $this->direction);
            } else {
                $query->orderBy('permintaan_barang.' . $this->sort, $this->direction);
            }
        } else {
            // Default Sorting jika properti kosong
            $query->join('permintaan', 'permintaan_barang.permintaan_id', '=', 'permintaan.id')
                ->orderBy('permintaan.created_at', 'desc')
                ->select('permintaan_barang.*');
        }

        $itemsToExport = $query->get();
        $fileName = 'Laporan-Permintaan_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';

        $pdf = Pdf::loadView('pdfs.lapPermintaan', ['permintaanItems' => $itemsToExport]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }
}
