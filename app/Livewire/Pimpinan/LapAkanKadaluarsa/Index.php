<?php

namespace App\Livewire\Pimpinan\LapAkanKadaluarsa;

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
    public $sort = 'tanggal_kadaluarsa'; // Default sorting diubah agar yang kadaluarsa terdekat muncul duluan
    public $direction = 'asc'; // Urutkan secara ascending (tanggal terdekat)

    public $stok_id;
    // Properti yang disesuaikan dengan migrasi stok terbaru
    public $obat_id, $batch_number, $jumlah, $harga, $total, $tanggal_kadaluarsa, $jumlah_masuk, $harga_masuk, $total_masuk, $jumlah_keluar, $harga_keluar, $total_keluar, $id_sumber_anggaran, $id_supplier, $id_rak;

    public $file;
    public $message = '';

    // Periode batas kadaluarsa dalam hari (120 hari = sekitar 4 bulan)
    private $kadaluarsaDalam = 90; // Ubah ke 90 jika hanya ingin 3 bulan

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
        $tanggalHariIni = Carbon::now()->toDateString();
        $tanggalKadaluarsaBatas = Carbon::now()->addDays($this->kadaluarsaDalam)->toDateString();

        $query = Stok::with(['obat', 'sumberAnggaran', 'supplier', 'rak'])
            // Filter tanggal wajib (Global)
            ->whereDate('tanggal_kadaluarsa', '>', $tanggalHariIni)
            ->whereDate('tanggal_kadaluarsa', '<=', $tanggalKadaluarsaBatas)

            // Filter pencarian harus dikelompokkan (Nested Where)
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where(function ($subQuery) {
                    $subQuery->where('batch_number', 'like', '%' . $this->search . '%')
                        ->orWhere('tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                        ->orWhereHas('obat', function ($q) {
                            $q->where('nama_obat', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('obat.kategori', function ($q2) {
                            $q2->where('nama_kategori', 'like', '%' . $this->search . '%');
                        });
                });
            });


        if ($this->sort === 'kategori') {
            // Tangani kasus khusus relasi terlebih dahulu
            $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                ->orderBy('kategori.nama_kategori', $this->direction ?? 'asc')
                ->select('stok.*'); // Penting agar kolom id tidak tertukar setelah join
        } elseif ($this->sort && $this->direction) {
            // Pengurutan kolom standar di tabel stok
            $query->orderBy($this->sort, $this->direction);
        } else {
            // Default sorting
            $query->orderBy('tanggal_kadaluarsa', 'asc');
        }

        $stoks = $query->paginate($this->paginate);

        $data = [
            'tittle' => "Laporan Barang Akan Kadaluarsa",
            'stoks' => $stoks,
            'obats' => Obat::all(),
            'sumberAnggarans' => SumberAnggaran::all(),
            'suppliers' => Supplier::all(),
            'rakPenyimpanans' => RakPenyimpanan::all(),
        ];

        return view('livewire.Pimpinan.LapAkanKadaluarsa.index', $data);
    }


    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        $tanggalHariIni = Carbon::now()->toDateString();
        $tanggalKadaluarsaBatas = Carbon::now()->addDays($this->kadaluarsaDalam)->toDateString();

        $query = Stok::with(['obat', 'sumberAnggaran', 'supplier', 'rak'])
            // Filter tanggal wajib (Global)
            ->whereDate('tanggal_kadaluarsa', '>', $tanggalHariIni)
            ->whereDate('tanggal_kadaluarsa', '<=', $tanggalKadaluarsaBatas)

            // Filter pencarian harus dikelompokkan (Nested Where)
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where(function ($subQuery) {
                    $subQuery->where('batch_number', 'like', '%' . $this->search . '%')
                        ->orWhere('tanggal_kadaluarsa', 'like', '%' . $this->search . '%')
                        ->orWhereHas('obat', function ($q) {
                            $q->where('nama_obat', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('obat.kategori', function ($q2) {
                            $q2->where('nama_kategori', 'like', '%' . $this->search . '%');
                        });
                });
            });


        if ($this->sort === 'kategori') {
            // Tangani kasus khusus relasi terlebih dahulu
            $query->join('obat', 'stok.obat_id', '=', 'obat.id')
                ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
                ->orderBy('kategori.nama_kategori', $this->direction ?? 'asc')
                ->select('stok.*'); // Penting agar kolom id tidak tertukar setelah join
        } elseif ($this->sort && $this->direction) {
            // Pengurutan kolom standar di tabel stok
            $query->orderBy($this->sort, $this->direction);
        } else {
            // Default sorting
            $query->orderBy('tanggal_kadaluarsa', 'asc');
        }

        $stoksToExport = $query->get();
        $fileName = 'Daftar-Stok-Akan-Kadaluarsa_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        $pdf = Pdf::loadView('pdfs.lapAkanKedaluwarsa', ['stoks' => $stoksToExport]);
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
