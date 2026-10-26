<?php

namespace App\Livewire\Superadmin\Stok;

use Livewire\Component;
use App\Exports\StoksExport;
use App\Models\Stok;
use App\Models\Obat;
use App\Models\SumberAnggaran;
use App\Models\Supplier;
use App\Models\RakPenyimpanan;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StoksImport;
use Livewire\WithFileUploads;

class Index extends Component
{
    // Menggunakan trait untuk paginasi dan unggah file
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'nama_obat';
    public $direction = 'asc';

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
        $query = Stok::with(['obat.satuan', 'obat.kategori', 'sumberAnggaran', 'supplier', 'rak']);

        $query->where('stok.jumlah', '>', 0); // Hanya tampilkan stok yang jumlahnya lebih dari 0

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
            } elseif ($this->sort === 'tanggal_input') {
                $query->orderBy('stok.created_at', $this->direction);
            } else {
                $query->orderBy('stok.' . $this->sort, $this->direction);
            }
        } else {
            $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                ->select('stok.*')
                ->orderBy('obat.nama_obat', 'asc');
        }

        $stoks = $query->paginate($this->paginate);

        $data = [
            'tittle' => 'Data Stok Obat',
            'stoks' => $stoks,
            'obats' => Obat::all(),
            'sumberAnggarans' => SumberAnggaran::all(),
            'suppliers' => Supplier::all(),
            'rakPenyimpanans' => RakPenyimpanan::all(),
        ];

        return view('livewire.superadmin.stok.index', $data);
    }

    // ----------------------------------Mereset Validation----------------------------------------------- //
    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);
    }

    // Menghitung total saat jumlah atau harga berubah
    public function updatedJumlah()
    {
        $this->computeTotal();
    }

    public function updatedHarga()
    {
        $this->computeTotal();
    }

    // Metode baru untuk memperbarui jumlah saat jumlah masuk berubah
    public function updatedJumlahMasuk($value)
    {
        // Ambil nilai jumlah yang ada, tambahkan jumlah masuk, dan pastikan nilainya non-negatif
        $this->jumlah = max(0, (int)$this->jumlah + (int)$value);
        $this->computeTotal();
    }

    // Metode baru untuk memperbarui jumlah saat jumlah keluar berubah
    public function updatedJumlahKeluar($value)
    {
        // Ambil nilai jumlah yang ada, kurangi jumlah keluar, dan pastikan nilainya non-negatif
        $this->jumlah = max(0, (int)$this->jumlah - (int)$value);
        $this->computeTotal();
    }

    // Metode untuk menghitung total
    private function computeTotal()
    {
        // Pastikan kedua nilai (jumlah dan harga) adalah angka sebelum dikalikan
        if (is_numeric($this->jumlah) && is_numeric($this->harga)) {
            // Jika jumlah 0, total juga 0. Jika tidak, kalikan jumlah dan harga
            $this->total = ((int)$this->jumlah === 0) ? 0 : $this->jumlah * $this->harga;
        } else {
            // Jika salah satu tidak valid, reset total menjadi null
            $this->total = null;
        }
    }




    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = Stok::with(['obat', 'sumberAnggaran', 'supplier']) // Eager load relasi kecamatan untuk ditampilkan
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where('batch_number', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                    ->orWhereHas('obat', function ($q) {
                        $q->where('nama_obat', 'like', '%' . $this->search . '%');
                    });
            });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $stoksToExport = $query->get();
        $fileName = 'Daftar-Stok_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new StoksExport($stoksToExport), $fileName);
    }

    // ----------------------------------Import Excel----------------------------------------------- //
    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new StoksImport, $this->file); // Ubah ke SuppliersImport
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
        // Dispatch e'show-import-modal'); // Asumsi ada modal import
    }
}
