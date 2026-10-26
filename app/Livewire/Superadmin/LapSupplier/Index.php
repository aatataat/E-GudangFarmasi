<?php

namespace App\Livewire\Superadmin\LapSupplier;

use App\Exports\SuppliersExport;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SuppliersImport;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $file;
    public $message = '';

    // ----------------------------------Menampilkan Data & Sorting----------------------------------------------- //
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
        $query = Supplier::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_supplier',
                'alamat',
                'no_hp',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $suppliers = $query->paginate($this->paginate);

        $data = [
            'tittle' => 'Laporan Data Penyedia',
            'suppliers' => $suppliers,
        ];

        return view('livewire.superadmin.LapSupplier.index', $data);
    }

    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = Supplier::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_supplier',
                'alamat',
                'no_hp',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $suppliersToExport = $query->get();
        $fileName = 'Daftar-Suppliers_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new SuppliersExport($suppliersToExport), $fileName);
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        $query = Supplier::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_supplier',
                'alamat',
                'no_hp',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $suppliersToExport = $query->get();
        $fileName = 'Daftar-Suppliers_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';

        // Memanggil view PDF yang kita buat sebelumnya
        $pdf = Pdf::loadView('pdfs.suppliers', ['suppliers' => $suppliersToExport])
            ->setPaper('a4', 'portrait');

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
            Excel::import(new SuppliersImport, $this->file);
            session()->flash('message', 'Data supplier berhasil diimpor!');
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
