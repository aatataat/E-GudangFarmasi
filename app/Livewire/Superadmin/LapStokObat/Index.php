<?php

namespace App\Livewire\Superadmin\LapStokObat;

use Livewire\Component;
use App\Exports\StoksExport;
use App\Models\Stok;
use App\Models\Obat;
use App\Models\SumberAnggaran;
use App\Models\Supplier;
use App\Models\RakPenyimpanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StoksImport;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'nama_obat';
    public $direction = 'asc';
    public $filterTanggal = null; // Tambahkan ini

    public $stok_id;
    public $obat_id, $batch_number, $jumlah, $harga, $total, $tanggal_kadaluarsa, $jumlah_masuk, $harga_masuk, $total_masuk, $jumlah_keluar, $harga_keluar, $total_keluar, $id_sumber_anggaran, $id_supplier, $id_rak;

    public $file;
    public $message = '';

    public function sortBy($field)
    {
        if ($this->sort === $field) {
            $this->direction = ($this->direction === 'asc') ? 'desc' : (($this->direction === 'desc') ? null : 'asc');
            if (!$this->direction) $this->sort = null;
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

    public function render()
    {
        $query = Stok::with(['obat.satuan', 'obat.kategori', 'sumberAnggaran', 'supplier', 'rak']);



        // 2. Filter Pencarian Utama
        $query->when($this->search, function ($queryBuilder) {
            $queryBuilder->where(function ($q) {
                $q->where('batch_number', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                    ->orWhereHas('obat', function ($q2) {
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('sumberAnggaran', function ($q2) { // Sesuaikan nama relasi
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('obat.kategori', function ($q2) {
                        $q2->where('nama_kategori', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 3. Logika Sorting
        if ($this->sort && $this->direction) {
            $query->select('stok.*'); // Mencegah ID tertukar

            if ($this->sort === 'nama_obat') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'kategori') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->select('stok.*')
                    ->orderBy('kategori.nama_kategori', $this->direction);
            } elseif ($this->sort === 'sumber_anggaran') {
                $query->join('sumber_anggaran', 'stok.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction);
            } elseif ($this->sort === 'supplier') {
                $query->join('supplier', 'stok.id_supplier', '=', 'supplier.id')
                    ->orderBy('supplier.nama_supplier', $this->direction);
            } elseif ($this->sort === 'satuan') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction);
            } elseif ($this->sort === 'nama_rak') {
                $query->join('rak', 'stok.id_rak', '=', 'rak.id')
                    ->orderBy('rak.nama_rak', $this->direction);
            } elseif ($this->sort === 'kategori') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction);
            } else {
                $query->orderBy('stok.' . $this->sort, $this->direction);
            }
        } else {
            $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                ->select('stok.*')
                ->orderBy('obat.nama_obat', 'asc');
        }

        return view('livewire.superadmin.LapstokObat.index', [
            'tittle' => 'Data Stok Obat',
            'stoks' => $query->paginate($this->paginate),
            'obats' => Obat::all(),
            'sumberAnggarans' => SumberAnggaran::all(),
            'suppliers' => Supplier::all(),
            'rakPenyimpanans' => RakPenyimpanan::all(),
        ]);
    }

    public function resetToAwal()
    {
        try {
            DB::transaction(function () {
                // 1. Ambil semua stok saat ini
                $semuaStok = \App\Models\Stok::all();

                foreach ($semuaStok as $stok) {
                    // Jika jumlah stok sudah 0, hapus datanya dari tabel stok
                    if ($stok->jumlah <= 0) {
                        $stok->delete();
                    } else {
                        // Jika masih ada sisa, jadikan jumlah tersebut sebagai stok awal baru
                        // dengan mengosongkan riwayat masuk dan keluar periode ini
                        $stok->update([
                            'jumlah_masuk' => 0,
                            'harga_masuk' => 0,
                            'total_masuk' => 0,
                            'jumlah_keluar' => 0,
                            'harga_keluar' => 0,
                            'total_keluar' => 0,
                        ]);
                    }
                }

                // 2. Kosongkan semua tabel riwayat transaksi agar laporan dinamika bersih
                DB::table('transaksi_masuk')->truncate();
                DB::table('transaksi_keluar')->truncate();
                DB::table('transaksi_return')->truncate();

                session()->flash('message', 'Berhasil! Stok kosong telah dihapus dan sisa stok lainnya telah direset menjadi Stok Awal.');
            });
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mereset: ' . $e->getMessage());
        }

        $this->dispatch('closeResetModal');
    }

    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = Stok::with(['obat.satuan', 'sumberAnggaran', 'supplier', 'rak']);

        // 1. Filter Tanggal Kadaluarsa (Fleksibel: Tahun/Bulan/Hari)
        $query->when($this->search, function ($queryBuilder) {
            $queryBuilder->where(function ($q) {
                $q->where('batch_number', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                    ->orWhereHas('obat', function ($q2) {
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('sumberAnggaran', function ($q2) { // Sesuaikan nama relasi
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('obat.kategori', function ($q2) {
                        $q2->where('nama_kategori', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 3. Logika Sorting
        if ($this->sort && $this->direction) {
            $query->select('stok.*'); // Mencegah ID tertukar

            if ($this->sort === 'nama_obat') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'kategori') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->select('stok.*')
                    ->orderBy('kategori.nama_kategori', $this->direction);
            } elseif ($this->sort === 'sumber_anggaran') {
                $query->join('sumber_anggaran', 'stok.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction);
            } elseif ($this->sort === 'supplier') {
                $query->join('supplier', 'stok.id_supplier', '=', 'supplier.id')
                    ->orderBy('supplier.nama_supplier', $this->direction);
            } elseif ($this->sort === 'satuan') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction);
            } elseif ($this->sort === 'nama_rak') {
                $query->join('rak', 'stok.id_rak', '=', 'rak.id')
                    ->orderBy('rak.nama_rak', $this->direction);
            } elseif ($this->sort === 'kategori') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction);
            } else {
                $query->orderBy('stok.' . $this->sort, $this->direction);
            }
        } else {
            $query->orderBy('stok.created_at', 'desc');
        }

        $stoksToExport = $query->get();
        $fileName = 'Daftar-Stok_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new StoksExport($stoksToExport), $fileName);
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        // Pastikan logika pencarian dan eager loading sama dengan render()
        $query = Stok::with(['obat.satuan', 'obat.kategori', 'sumberAnggaran', 'supplier', 'rak']);


        // 2. Filter Pencarian Utama
        $query->when($this->search, function ($queryBuilder) {
            $queryBuilder->where(function ($q) {
                $q->where('batch_number', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                    ->orWhereHas('obat', function ($q2) {
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('sumberAnggaran', function ($q2) { // Sesuaikan nama relasi
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('obat.kategori', function ($q2) {
                        $q2->where('nama_kategori', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 3. Logika Sorting
        if ($this->sort && $this->direction) {
            $query->select('stok.*'); // Mencegah ID tertukar

            if ($this->sort === 'nama_obat') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'kategori') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->select('stok.*')
                    ->orderBy('kategori.nama_kategori', $this->direction);
            } elseif ($this->sort === 'sumber_anggaran') {
                $query->join('sumber_anggaran', 'stok.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction);
            } elseif ($this->sort === 'supplier') {
                $query->join('supplier', 'stok.id_supplier', '=', 'supplier.id')
                    ->orderBy('supplier.nama_supplier', $this->direction);
            } elseif ($this->sort === 'satuan') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction);
            } elseif ($this->sort === 'nama_rak') {
                $query->join('rak', 'stok.id_rak', '=', 'rak.id')
                    ->orderBy('rak.nama_rak', $this->direction);
            } elseif ($this->sort === 'kategori') {
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction);
            } else {
                $query->orderBy('stok.' . $this->sort, $this->direction);
            }
        } else {
            $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                ->select('stok.*')
                ->orderBy('obat.nama_obat', 'asc');
        }

        $stoksToExport = $query->get();
        $fileName = 'Daftar-Stok_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        // Pastikan view name adalah 'pdfs.stoks' dan variabel adalah 'stoks'
        $pdf = Pdf::loadView('pdfs.lapStokObat', ['stoks' => $stoksToExport]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    // ----------------------------------Import Excel----------------------------------------------- //
    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new StoksImport, $this->file);
            session()->flash('message', 'Data stok berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('error', 'Gagal mengimpor data. Periksa kembali format file.');
            // Anda bisa menambahkan logika untuk menampilkan error per baris dari $failures
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->reset('file');
    }

    public function importExcel()
    {
        $this->reset(['file', 'message']);
    }
}
