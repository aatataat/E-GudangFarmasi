<?php

namespace App\Livewire\Pimpinan\LapTransaksiMasuk;

use Livewire\Component;
use App\Exports\LapTransaksiMasuksExport;
use App\Models\TransaksiMasuk;
use App\Models\Obat;
use App\Models\SumberAnggaran;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransaksiMasuksImport;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';
    public $filterTanggal = null;

    public $transaksi_masuk_id;
    public $no, $obat_id, $tanggal_masuk, $batch_number, $tanggal_kadaluarsa, $jumlah, $harga, $total, $id_sumber_anggaran, $id_supplier;

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
        // Hanya mencari berdasarkan tanggal_kadaluarsa
        $query = TransaksiMasuk::query();

        // 1. Filter Berdasarkan Tanggal/Bulan/Tahun (Jika diisi)
        $query->when($this->filterTanggal, function ($queryBuilder) {
            if (str_contains($this->filterTanggal, '-')) {
                // Jika formatnya Y-m-d (contoh: 2026-01-20)
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->filterTanggal)) {
                    $queryBuilder->whereDate('tanggal_masuk', $this->filterTanggal);
                } else {
                    // Jika formatnya Y-m (contoh: 2026-01)
                    $queryBuilder->whereMonth('tanggal_masuk', date('m', strtotime($this->filterTanggal)))
                        ->whereYear('tanggal_masuk', date('Y', strtotime($this->filterTanggal)));
                }
            } else {
                // Jika hanya angka 4 digit (contoh: 2026)
                $queryBuilder->whereYear('tanggal_masuk', $this->filterTanggal);
            }
        });

        // 2. Filter Berdasarkan Input Pencarian (Kerjasama dengan Filter Tanggal)
        $query->when($this->search, function ($queryBuilder) {
            // PENTING: Menggunakan closure function ($q) agar semua OR di bawah ini 
            // dianggap sebagai satu kesatuan filter yang harus memenuhi filter tanggal di atas.
            $queryBuilder->where(function ($q) {
                $q->where('batch_number', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal_masuk', 'like', '%' . $this->search . '%')
                    ->orWhere('no', 'like', '%' . $this->search . '%')
                    ->orWhereHas('obat', function ($q2) {
                        // Mencari nama obat DAN satuan
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            });
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('kategori', function ($q3) {
                                $q3->where('nama_kategori', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('sumberAnggaran', function ($q2) {
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 3. Logika Sorting (Tetap utuh seperti kode Anda)
        if ($this->sort && $this->direction) {
            if ($this->sort === 'sumber_anggaran') {
                $query->join('sumber_anggaran', 'transaksi_masuk.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction)
                    ->select('transaksi_masuk.*'); // Penting agar kolom tidak bentrok setelah join
            } elseif ($this->sort === 'obat') {
                $query->join('obat', 'transaksi_masuk.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction)
                    ->select('transaksi_masuk.*');
            } elseif ($this->sort === 'supplier') {
                $query->join('supplier', 'transaksi_masuk.id_supplier', '=', 'supplier.id')
                    ->orderBy('supplier.nama_supplier', $this->direction)
                    ->select('transaksi_masuk.*');
            } elseif ($this->sort === 'satuan') { // Penambahan sorting satuan yang Anda minta sebelumnya
                $query->join('obat', 'transaksi_masuk.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction)
                    ->select('transaksi_masuk.*');
            } elseif ($this->sort === 'kategori') { // Penambahan sorting kategori yang Anda minta sebelumnya
                $query->join('obat', 'transaksi_masuk.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction)
                    ->select('transaksi_masuk.*');
            } else {
                $query->orderBy($this->sort, $this->direction);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $transaksi_masuk = $query->paginate($this->paginate);

        $data = [
            'tittle' => 'Data Transaksi Masuk',
            'transaksi_masuk' => $transaksi_masuk,
            'obats' => Obat::orderBy('nama_obat', 'asc')->get(),
            'sumberAnggarans' => SumberAnggaran::orderBy('nama_anggaran', 'asc')->get(),
            'suppliers' => Supplier::orderBy('nama_supplier', 'asc')->get(),
        ];

        return view('livewire.Pimpinan.Laptransaksi_masuk.index', $data);
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

    // Metode untuk menghitung total
    private function computeTotal()
    {
        if (is_numeric($this->jumlah) && is_numeric($this->harga)) {
            $this->total = $this->jumlah * $this->harga;
        } else {
            $this->total = null;
        }
    }

    // ----------------------------------CRUD Data----------------------------------------------- //


    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = TransaksiMasuk::with(['obat', 'sumberAnggaran', 'supplier'])
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where('tanggal_kadaluarsa', 'like', '%' . $this->search . '%');
            });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $transaksi_masuksToExport = $query->get();
        $fileName = 'Daftar-TransaksiMasuk_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new LapTransaksiMasuksExport($transaksi_masuksToExport), $fileName);
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        $query = TransaksiMasuk::query();

        // 1. Filter Berdasarkan Tanggal/Bulan/Tahun (Jika diisi)
        $query->when($this->filterTanggal, function ($queryBuilder) {
            if (str_contains($this->filterTanggal, '-')) {
                // Jika formatnya Y-m-d (contoh: 2026-01-20)
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->filterTanggal)) {
                    $queryBuilder->whereDate('tanggal_masuk', $this->filterTanggal);
                } else {
                    // Jika formatnya Y-m (contoh: 2026-01)
                    $queryBuilder->whereMonth('tanggal_masuk', date('m', strtotime($this->filterTanggal)))
                        ->whereYear('tanggal_masuk', date('Y', strtotime($this->filterTanggal)));
                }
            } else {
                // Jika hanya angka 4 digit (contoh: 2026)
                $queryBuilder->whereYear('tanggal_masuk', $this->filterTanggal);
            }
        });

        // 2. Filter Berdasarkan Input Pencarian (Kerjasama dengan Filter Tanggal)
        $query->when($this->search, function ($queryBuilder) {
            // PENTING: Menggunakan closure function ($q) agar semua OR di bawah ini 
            // dianggap sebagai satu kesatuan filter yang harus memenuhi filter tanggal di atas.
            $queryBuilder->where(function ($q) {
                $q->where('batch_number', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal_masuk', 'like', '%' . $this->search . '%')
                    ->orWhere('no', 'like', '%' . $this->search . '%')
                    ->orWhereHas('obat', function ($q2) {
                        // Mencari nama obat DAN satuan
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            });
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('kategori', function ($q3) {
                                $q3->where('nama_kategori', 'like', '%' . $this->search . '%');
                            });

                    })
                    ->orWhereHas('supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('sumberAnggaran', function ($q2) {
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 3. Logika Sorting (Tetap utuh seperti kode Anda)
        if ($this->sort && $this->direction) {
            if ($this->sort === 'sumber_anggaran') {
                $query->join('sumber_anggaran', 'transaksi_masuk.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction)
                    ->select('transaksi_masuk.*'); // Penting agar kolom tidak bentrok setelah join
            } elseif ($this->sort === 'obat') {
                $query->join('obat', 'transaksi_masuk.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction)
                    ->select('transaksi_masuk.*');
            } elseif ($this->sort === 'supplier') {
                $query->join('supplier', 'transaksi_masuk.id_supplier', '=', 'supplier.id')
                    ->orderBy('supplier.nama_supplier', $this->direction)
                    ->select('transaksi_masuk.*');
            } elseif ($this->sort === 'satuan') { // Penambahan sorting satuan yang Anda minta sebelumnya
                $query->join('obat', 'transaksi_masuk.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction)
                    ->select('transaksi_masuk.*');
            } elseif ($this->sort === 'kategori') { // Penambahan sorting kategori yang Anda minta sebelumnya
                $query->join('obat', 'transaksi_masuk.obat_id', '=', 'obat.id')
                    ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                    ->orderBy('kategori.nama_kategori', $this->direction)
                    ->select('transaksi_masuk.*');
            } else {
                $query->orderBy($this->sort, $this->direction);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $transaksi_masuksToExport = $query->get();
        $fileName = 'Daftar-TransaksiMasuk_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        $pdf = Pdf::loadView('pdfs.Laptransaksi_masuks', ['transaksi_masuk' => $transaksi_masuksToExport]);
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
            Excel::import(new TransaksiMasuksImport, $this->file);
            session()->flash('message', 'Data transaksi masuk berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('error', 'Gagal mengimpor data. Periksa kembali format file.');
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
