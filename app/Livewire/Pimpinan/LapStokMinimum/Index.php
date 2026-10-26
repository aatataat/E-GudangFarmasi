<?php

namespace App\Livewire\Pimpinan\LapStokMinimum;

use Livewire\Component;
use App\Exports\StoksExport;
use App\Models\Stok;
use App\Models\Obat;
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

    public $stok_id;
    public $file;
    public $message = '';

    /**
     * Mengambil ID Obat yang statusnya kritis berdasarkan rumus dinamis.
     * Digunakan untuk memfilter query di render, PDF, dan Excel.
     */
    private function getKritisObatIds()
    {
        return Obat::all()->filter(function ($obat) {
            return $obat->is_kritis;
        })->pluck('id');
    }

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
                $this->sort = $field;
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
        // Mengambil ID obat yang masuk kriteria kritis/habis dari logic model
        $obatKritisIds = $this->getKritisObatIds();

        $query = Stok::query()
            // Melakukan Join ke master obat untuk mendapatkan Nama Obat
            ->join('obat', 'stok.obat_id', '=', 'obat.id')
            ->whereIn('stok.obat_id', $obatKritisIds)
            ->select(
                'stok.obat_id',
                'obat.nama_obat',
                // Menghitung total stok dari semua batch yang ada
                DB::raw('SUM(stok.jumlah) as total_stok_gabungan')
            )
            // Pengelompokan berdasarkan obat_id agar satu obat hanya muncul satu baris
            ->groupBy('stok.obat_id', 'obat.nama_obat')
            ->when($this->search, function ($q) {
                $q->where('obat.nama_obat', 'like', '%' . $this->search . '%');
            });

        // Sorting berdasarkan nama obat
        if ($this->sort === 'nama_obat') {
            $query->orderBy('obat.nama_obat', $this->direction);
        } elseif ($this->sort === 'status') {
            // Mengurutkan berdasarkan jumlah stok: 0 (Habis) akan terkumpul, lalu Kritis
            $query->orderBy('total_stok_gabungan', $this->direction);
        } else {
            $query->orderBy('obat.nama_obat', 'asc');
        }

        $stoks = $query->paginate($this->paginate);

        return view('livewire.Pimpinan.LapStokMinimum.index', [
            'stoks' => $stoks,
            'tittle' => "Laporan Stok Kritis (Data Stok Riil)"
        ]);
    }

    public function exportExcel()
    {
        $obatKritisIds = $this->getKritisObatIds();

        $query = Stok::query()
            // Melakukan Join ke master obat untuk mendapatkan Nama Obat
            ->join('obat', 'stok.obat_id', '=', 'obat.id')
            ->whereIn('stok.obat_id', $obatKritisIds)
            ->select(
                'stok.obat_id',
                'obat.nama_obat',
                // Menghitung total stok dari semua batch yang ada
                DB::raw('SUM(stok.jumlah) as total_stok_gabungan')
            )
            // Pengelompokan berdasarkan obat_id agar satu obat hanya muncul satu baris
            ->groupBy('stok.obat_id', 'obat.nama_obat')
            ->when($this->search, function ($q) {
                $q->where('obat.nama_obat', 'like', '%' . $this->search . '%');
            });

        // Sorting berdasarkan nama obat
        if ($this->sort === 'nama_obat') {
            $query->orderBy('obat.nama_obat', $this->direction);
        } elseif ($this->sort === 'status') {
            // Mengurutkan berdasarkan jumlah stok: 0 (Habis) akan terkumpul, lalu Kritis
            $query->orderBy('total_stok_gabungan', $this->direction);
        } else {
            $query->orderBy('obat.nama_obat', 'asc');
        }

        $stoksToExport = $query->get();
        $fileName = 'Daftar-Stok-Kritis_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';

        return Excel::download(new StoksExport($stoksToExport), $fileName);
    }

    public function exportPdf()
    {
        $obatKritisIds = $this->getKritisObatIds();

        $query = Stok::query()
            // Melakukan Join ke master obat untuk mendapatkan Nama Obat
            ->join('obat', 'stok.obat_id', '=', 'obat.id')
            ->whereIn('stok.obat_id', $obatKritisIds)
            ->select(
                'stok.obat_id',
                'obat.nama_obat',
                // Menghitung total stok dari semua batch yang ada
                DB::raw('SUM(stok.jumlah) as total_stok_gabungan')
            )
            // Pengelompokan berdasarkan obat_id agar satu obat hanya muncul satu baris
            ->groupBy('stok.obat_id', 'obat.nama_obat')
            ->when($this->search, function ($q) {
                $q->where('obat.nama_obat', 'like', '%' . $this->search . '%');
            });

        // Sorting berdasarkan nama obat
        if ($this->sort === 'nama_obat') {
            $query->orderBy('obat.nama_obat', $this->direction);
        } elseif ($this->sort === 'status') {
            // Mengurutkan berdasarkan jumlah stok: 0 (Habis) akan terkumpul, lalu Kritis
            $query->orderBy('total_stok_gabungan', $this->direction);
        } else {
            $query->orderBy('obat.nama_obat', 'asc');
        }

        $stoksToExport = $query->get();
        $fileName = 'Daftar-Stok-Kritis_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';

        $pdf = Pdf::loadView('pdfs.LapStokMinimum', ['stoks' => $stoksToExport]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new StoksImport, $this->file);
            session()->flash('message', 'Data stok berhasil diimpor!');
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
