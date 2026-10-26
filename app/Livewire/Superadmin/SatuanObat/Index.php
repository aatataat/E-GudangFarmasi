<?php

namespace App\Livewire\Superadmin\SatuanObat;

use Livewire\Component;
use App\Exports\SatuansExport;
use App\Models\Satuan; // Import model Satuan
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SatuansImport;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $nama_satuan, $satuan_id;

    public $file;
    public $message = '';

    // -------------------- Sorting -------------------- //
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

    // -------------------- Render -------------------- //
    public function render()
    {
        $query = Satuan::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_satuan',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $satuan = $query->paginate($this->paginate);

        $data = [
            'tittle' => 'Data Satuan',
            'satuan' => $satuan,
        ];

        return view('livewire.superadmin.satuan-obat.index', $data);
    }

    // -------------------- Reset Validation -------------------- //
    public function updatedNamaSatuan()
    {
        $this->resetValidation('nama_satuan');
    }

    // -------------------- CRUD -------------------- //
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'nama_satuan',
        ]);
    }

    public function store()
    {
        $this->validate(
            [
                'nama_satuan' => ['required', 'string', 'max:255', 'unique:satuan,nama_satuan'],
            ],
            [
                'nama_satuan.required' => 'Nama Satuan Tidak Boleh Kosong',
                'nama_satuan.string' => 'Nama Satuan Harus Berupa Teks',
                'nama_satuan.max' => 'Nama Satuan Maksimal 255 Karakter',
                'nama_satuan.unique' => 'Nama Satuan Sudah Ada',
            ]
        );

        $satuan = new Satuan;
        $satuan->nama_satuan = $this->nama_satuan;
        $satuan->save();

        $this->dispatch('closeCreateModal');
    }

    public function edit($id)
    {
        $this->resetValidation();
        $satuan = Satuan::findOrFail($id);
        $this->satuan_id = $satuan->id;
        $this->nama_satuan = $satuan->nama_satuan;
    }

    public function update($id)
    {
        $satuan = Satuan::findOrFail($id);

        $rules = [
            'nama_satuan' => 'required|unique:satuan,nama_satuan,' . $id . '|string|max:255',
        ];

        $messages = [
            'nama_satuan.required' => 'Nama Satuan Tidak Boleh Kosong',
            'nama_satuan.string' => 'Nama Satuan Harus Berupa Teks',
            'nama_satuan.max' => 'Nama Satuan Maksimal 255 Karakter',
            'nama_satuan.unique' => 'Nama Satuan Sudah Ada',
        ];

        $this->validate($rules, $messages);

        $satuan->nama_satuan = $this->nama_satuan;
        $satuan->save();

        $this->dispatch('closeEditModal');
    }

    public function confirm($id)
    {
        $satuan = Satuan::findOrFail($id);
        $this->nama_satuan = $satuan->nama_satuan;
        $this->satuan_id = $satuan->id;
    }

    public function destroy($id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->delete();
        $this->dispatch('closeDeleteModal');
    }

    // -------------------- Export Excel -------------------- //
    public function exportExcel()
    {
        $query = Satuan::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_satuan',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $satuanToExport = $query->get();
        $fileName = 'Daftar-Satuan_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new SatuansExport($satuanToExport), $fileName);
    }

    // -------------------- Export PDF -------------------- //
    public function exportPdf()
    {
        $query = Satuan::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_satuan',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $satuanToExport = $query->get();
        $fileName = 'Daftar-Satuan_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        $pdf = Pdf::loadView('pdfs.satuan', ['satuan' => $satuanToExport]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    // -------------------- Import Excel -------------------- //
    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SatuansImport, $this->file);
            session()->flash('message', 'Data satuan berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
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
