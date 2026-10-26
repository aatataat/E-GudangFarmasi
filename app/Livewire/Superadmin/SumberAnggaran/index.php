<?php

namespace App\Livewire\Superadmin\SumberAnggaran;

use Livewire\Component;
use App\Exports\SumberAnggaransExport;
use App\Models\SumberAnggaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SumberAnggaransImport;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $nama_anggaran, $sumber_anggaran_id;

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
        $query = SumberAnggaran::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_anggaran',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $sumber_anggaran = $query->paginate($this->paginate);

        $data = [
            'tittle' => 'Data Sumber Anggaran',
            'sumber_anggaran' => $sumber_anggaran,
        ];

        return view('livewire.superadmin.sumber_anggaran.index', $data);
    }

    // ----------------------------------Mereset Validation----------------------------------------------- //
    public function updatedNamaAnggaran()
    {
        $this->resetValidation('nama_anggaran');
    }


    // ----------------------------------CRUD Data----------------------------------------------- //
    // Tambah
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'nama_anggaran',
        ]);
    }

    public function store()
    {
        $this->validate(
            [
            'nama_anggaran' => ['required', 'string', 'max:255','unique:sumber_anggaran,nama_anggaran'],
            ],
            [
                'nama_anggaran.required' => 'Nama Anggaran Tidak Boleh Kosong',
                'nama_anggaran.string' => 'Nama Anggaran Harus Berupa Teks',
                'nama_anggaran.max' => 'Nama Anggaran Maksimal 255 Karakter',
                'nama_anggaran.unique' => 'Nama Anggaran Sudah Ada',
            ]
        );

        $sumber_anggaran = new SumberAnggaran;
        $sumber_anggaran->nama_anggaran = $this->nama_anggaran;
        $sumber_anggaran->save();

        $this->dispatch('closeCreateModal');
    }

    // Edit
    public function edit($id)
    {
        $this->resetValidation();
        $sumber_anggaran = SumberAnggaran::findOrFail($id);
        $this->sumber_anggaran_id = $sumber_anggaran->id;
        $this->nama_anggaran = $sumber_anggaran->nama_anggaran;
    }

    public function update($id)
    {
        $sumber_anggaran = SumberAnggaran::findOrFail($id);

        $rules = [
            'nama_anggaran' => 'required|unique:sumber_anggaran,nama_anggaran,'. $id .'|string| max:255',
        ];

        $messages = [
            'nama_anggaran.required' => 'Nama Anggaran Tidak Boleh Kosong',
            'nama_anggaran.string' => 'Nama Anggaran Harus Berupa Teks',
            'nama_anggaran.max' => 'Nama Anggaran Maksimal 255 Karakter',
            'nama_anggaran.unique' => 'Nama Anggaran Sudah Ada',
        ];

        $this->validate($rules, $messages);

        $sumber_anggaran->nama_anggaran = $this->nama_anggaran;

        $sumber_anggaran->save();

        $this->dispatch('closeEditModal');
    }

    // Hapus
    public function confirm($id)
    {
        $sumber_anggaran = SumberAnggaran::findOrFail($id);
        $this->nama_anggaran = $sumber_anggaran->nama_anggaran;
        $this->sumber_anggaran_id = $sumber_anggaran->id;
    }

    public function destroy($id)
    {
        $sumber_anggaran = SumberAnggaran::findOrFail($id);
        $sumber_anggaran->delete();
        $this->dispatch('closeDeleteModal');
    }

    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = SumberAnggaran::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_anggaran',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $sumber_anggaransToExport = $query->get();
        $fileName = 'Daftar-SumberAnggaran_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new SumberAnggaransExport($sumber_anggaransToExport), $fileName);
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        $query = SumberAnggaran::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_anggaran',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $sumber_anggaransToExport = $query->get();
        $fileName = 'Daftar-SumberAnggaran_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        $pdf = Pdf::loadView('pdfs.sumber_anggarans', ['sumber_anggarans' => $sumber_anggaransToExport]);
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
            Excel::import(new SumberAnggaransImport, $this->file);
            session()->flash('message', 'Data sumber anggaran berhasil diimpor!');
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
