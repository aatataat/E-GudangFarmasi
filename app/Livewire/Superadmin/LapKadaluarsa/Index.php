<?php

namespace App\Livewire\Superadmin\LapKadaluarsa;

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
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class Index extends Component
{
    // Menggunakan trait untuk paginasi dan unggah file
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'tanggal_kadaluarsa'; // Default sorting diubah menjadi tanggal kadaluarsa
    public $direction = 'desc'; // Urutkan secara descending (yang paling lama kadaluarsa di atas)

    public $stok_id;
    // Properti yang disesuaikan dengan migrasi stok terbaru
    public $obat_id, $batch_number, $jumlah, $harga, $total, $tanggal_kadaluarsa, $jumlah_masuk, $harga_masuk, $total_masuk, $jumlah_keluar, $harga_keluar, $total_keluar, $id_sumber_anggaran, $id_supplier, $id_rak;

    public $file;
    public $message = '';

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

    public function render()
    {
        // Eager load relasi yang ada di tabel stok
        $query = Stok::with(['obat.satuan', 'sumberAnggaran', 'supplier', 'rak'])
            ->whereDate('stok.tanggal_kadaluarsa', '<=', Carbon::now()->toDateString())
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where(function ($segregasi) {
                    $segregasi->where('stok.batch_number', 'like', '%' . $this->search . '%')
                        ->orWhere('stok.tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                        // Menggabungkan pencarian obat dan satuan dalam satu orWhereHas agar lebih efisien
                        ->orWhereHas('obat', function ($q) {
                            $q->where('nama_obat', 'like', '%' . $this->search . '%')
                                ->orWhereHas('satuan', function ($sq) {
                                    $sq->where('nama_satuan', 'like', '%' . $this->search . '%');
                                });
                            $q->where('nama_obat', 'like', '%' . $this->search . '%')
                                ->orWhereHas('kategori', function ($sq) {
                                    $sq->where('nama_kategori', 'like', '%' . $this->search . '%');
                                });
                        })
                        ->orWhereHas('sumberAnggaran', function ($q) {
                            $q->where('nama_anggaran', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('supplier', function ($q) {
                            $q->where('nama_supplier', 'like', '%' . $this->search . '%');
                        });
                });
            });

        $query->where('stok.jumlah', '>', 0);

        if ($this->sort && $this->direction) {
            if ($this->sort === 'nama_obat') {
                // Sesuaikan nama tabel join jika perlu (misal: 'obat' atau 'obats')
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->select('stok.*')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'nama_sumber_anggaran') {
                $query->join('sumber_anggaran', 'stok.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->select('stok.*')
                    // Ganti 'nama_sumber_anggaran' menjadi nama kolom yang benar di tabel sumber_anggaran
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction);
            } elseif ($this->sort === 'supplier') {
                $query->join('supplier', 'stok.id_supplier', '=', 'supplier.id')
                    ->select('stok.*')
                    ->orderBy('supplier.nama_supplier', $this->direction);
            } elseif ($this->sort === 'satuan') { // Penambahan sorting satuan yang Anda minta sebelumnya
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction)
                    ->select('stok.*');
            } elseif ($this->sort === 'kategori') { // Penambahan sorting kategori yang Anda minta sebelumnya
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction)
                    ->select('stok.*');
            } else {
                // Gunakan prefix 'stok.' untuk menghindari kolom ambigu
                $query->orderBy('stok.' . $this->sort, $this->direction);
            }
        } else {
            $query->orderBy('stok.tanggal_kadaluarsa', 'desc');
        }

        $stoks = $query->paginate($this->paginate);

        $data = [
            'tittle' => 'Laporan Barang Kadaluarsa',
            'stoks' => $stoks,
            'obats' => Obat::all(),
            'sumberAnggarans' => SumberAnggaran::all(),
            'suppliers' => Supplier::all(),
            'rakPenyimpanans' => RakPenyimpanan::all(),
        ];

        return view('livewire.superadmin.LapKadaluarsa.index', $data);
    }

    // Metode CRUD lainnya dihilangkan untuk keringkasan karena tidak relevan dengan laporan.
    // Asumsi: Metode CRUD (create, store, edit, update, confirm, destroy) yang ada di kode asli Anda tetap berfungsi
    // dan tidak perlu diubah karena laporan ini HANYA UNTUK MENAMPILKAN, bukan mengubah data.

    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = Stok::with(['obat', 'sumberAnggaran', 'supplier', 'rak'])
            // Gunakan 'stok' bukan 'stoks'
            ->whereDate('stok.tanggal_kadaluarsa', '<=', Carbon::now()->toDateString())

            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where(function ($segregasi) {
                    $segregasi->where('stok.batch_number', 'like', '%' . $this->search . '%')
                        ->orWhere('stok.tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                        ->orWhereHas('obat', function ($q) {
                            $q->where('nama_obat', 'like', '%' . $this->search . '%');
                        });
                });
            });

        $query->where('stok.jumlah', '>', 0);

        if ($this->sort && $this->direction) {
            if ($this->sort === 'nama_obat') {
                // Sesuaikan nama tabel join jika perlu (misal: 'obat' atau 'obats')
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->select('stok.*')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'nama_sumber_anggaran') {
                $query->join('sumber_anggaran', 'stok.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->select('stok.*')
                    ->orderBy('sumber_anggaran.nama_sumber_anggaran', $this->direction);
            } elseif ($this->sort === 'satuan') { // Penambahan sorting satuan yang Anda minta sebelumnya
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction)
                    ->select('stok.*');
            } else {
                // Gunakan prefix 'stok.' untuk menghindari kolom ambigu
                $query->orderBy('stok.' . $this->sort, $this->direction);
            }
        } else {
            $query->orderBy('stok.tanggal_kadaluarsa', 'desc');
        }

        $stoksToExport = $query->get();
        $fileName = 'Daftar-Stok-Kadaluarsa_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new StoksExport($stoksToExport), $fileName);
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        // Menyesuaikan eager loading untuk ekspor PDF
        $query = Stok::with(['obat.satuan', 'sumberAnggaran', 'supplier', 'rak'])
            ->whereDate('stok.tanggal_kadaluarsa', '<=', Carbon::now()->toDateString())
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where(function ($segregasi) {
                    $segregasi->where('stok.batch_number', 'like', '%' . $this->search . '%')
                        ->orWhere('stok.tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                        // Menggabungkan pencarian obat dan satuan dalam satu orWhereHas agar lebih efisien
                        ->orWhereHas('obat', function ($q) {
                            $q->where('nama_obat', 'like', '%' . $this->search . '%')
                                ->orWhereHas('satuan', function ($sq) {
                                    $sq->where('nama_satuan', 'like', '%' . $this->search . '%');
                                });
                            $q->where('nama_obat', 'like', '%' . $this->search . '%')
                                ->orWhereHas('kategori', function ($sq) {
                                    $sq->where('nama_kategori', 'like', '%' . $this->search . '%');
                                });
                        })
                        ->orWhereHas('sumberAnggaran', function ($q) {
                            $q->where('nama_anggaran', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('supplier', function ($q) {
                            $q->where('nama_supplier', 'like', '%' . $this->search . '%');
                        });
                });
            });
        $query->where('stok.jumlah', '>', 0);


        if ($this->sort && $this->direction) {
            if ($this->sort === 'nama_obat') {
                // Sesuaikan nama tabel join jika perlu (misal: 'obat' atau 'obats')
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->select('stok.*')
                    ->orderBy('obat.nama_obat', $this->direction);
            } elseif ($this->sort === 'nama_sumber_anggaran') {
                $query->join('sumber_anggaran', 'stok.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->select('stok.*')
                    // Ganti 'nama_sumber_anggaran' menjadi nama kolom yang benar di tabel sumber_anggaran
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction);
            } elseif ($this->sort === 'supplier') {
                $query->join('supplier', 'stok.id_supplier', '=', 'supplier.id')
                    ->select('stok.*')
                    ->orderBy('supplier.nama_supplier', $this->direction);
            } elseif ($this->sort === 'satuan') { // Penambahan sorting satuan yang Anda minta sebelumnya
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction)
                    ->select('stok.*');
            } elseif ($this->sort === 'kategori') { // Penambahan sorting kategori yang Anda minta sebelumnya
                $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction)
                    ->select('stok.*');
            } else {
                // Gunakan prefix 'stok.' untuk menghindari kolom ambigu
                $query->orderBy('stok.' . $this->sort, $this->direction);
            }
        } else {
            $query->orderBy('stok.tanggal_kadaluarsa', 'desc');
        }

        $stoksToExport = $query->get();
        $fileName = 'Daftar-Stok-Kadaluarsa_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        $pdf = Pdf::loadView('pdfs.lapkadaluarsa', ['stoks' => $stoksToExport]);
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

    // Menghitung total dan fungsi CRUD lainnya dari kode asli (diasumsikan Anda memilikinya)
    // ...
}
