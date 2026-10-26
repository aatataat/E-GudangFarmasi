<?php

namespace App\Livewire\Superadmin\KategoriObat;

use Livewire\Component;
use App\Exports\KategorisExport;
use App\Models\Kategori; // Import model Kategori
use Barryvdh\DomPDF\Facade\Pdf; // Untuk ekspor PDF
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel; // Untuk fungsionalitas Excel
use App\Imports\KategorisImport; // Ubah ke KategorisImport (Anda perlu membuatnya)
use Livewire\WithFileUploads; // Penting untuk menangani unggahan file

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $nama_kategori, $kategori_id;

    public $file; // Properti untuk menampung file yang diunggah dari form
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
        // Mengubah dari model User ke model Kategori
        $query = Kategori::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_kategori',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $kategori = $query->paginate($this->paginate); // Mengubah nama variabel

        $data = [
            'tittle' => 'Data Kategori',
            'kategori' => $kategori, // Mengubah nama variabel
        ];

        return view('livewire.superadmin.kategori-obat.index', $data); // Perhatikan path view
    }

    // ----------------------------------Mereset Validation----------------------------------------------- //
    public function updatedNamaKategori() // Sesuaikan nama method
    {
        $this->resetValidation('nama_kategori');
    }


    // ----------------------------------CRUD Data----------------------------------------------- //
    // Tambah
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'nama_kategori',
        ]);
    }

    public function store()
    {
        $this->validate(
            [
                'nama_kategori' => ['required', 'string', 'max:255', 'unique:kategori,nama_kategori'],
            ],
            [
                'nama_kategori.required' => 'Nama Kategori Tidak Boleh Kosong',
                'nama_kategori.regex'                    => 'Nama Tidak Boleh Mengandung Simbol,Nomor Selain Titik dan Apostrof',
                'nama_kategori.string' => 'Nama Kategori Harus Berupa Teks',
                'nama_kategori.max' => 'Nama Kategori Maksimal 255 Karakter',
                'nama_kategori.unique' => 'Nama Kategori Sudah Ada',
            ]
        );

        $kategori = new Kategori; // Mengubah dari User ke Kategori
        $kategori->nama_kategori = $this->nama_kategori;
        // Hapus baris password, karena tabel kategori tidak memiliki kolom password
        $kategori->save();

        $this->dispatch('closeCreateModal'); // Event untuk menutup modal
    }

    // Edit
    public function edit($id)
    {
        $this->resetValidation();
        $kategori = Kategori::findOrFail($id); // Mengubah dari User ke Kategori
        $this->kategori_id = $kategori->id;
        $this->nama_kategori = $kategori->nama_kategori;
    }

    public function update($id)
    {
        $kategori = Kategori::findOrFail($id); // Mengubah dari User ke Kategori

        $rules = [
            'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $id . '|string| max:255',
        ];

        $messages = [
            'nama_kategori.required' => 'Nama Kategori Tidak Boleh Kosong',
            'nama_kategori.string' => 'Nama Kategori Harus Berupa Teks',
            'nama_kategori.max' => 'Nama Kategori Maksimal 255 Karakter',
            'nama_kategori.unique' => 'Nama Kategori Sudah Ada',
        ];

        $this->validate($rules, $messages);

        $kategori->nama_kategori = $this->nama_kategori;

        $kategori->save();

        $this->dispatch('closeEditModal'); // Event untuk menutup modal
    }

    // Hapus
    public function confirm($id)
    {
        $kategori = Kategori::findOrFail($id); // Mengubah dari User ke Kategori
        $this->nama_kategori = $kategori->nama_kategori;
        $this->kategori_id = $kategori->id;
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id); // Mengubah dari User ke Kategori
        $kategori->delete();
        $this->dispatch('closeDeleteModal'); // Event untuk menutup modal
    }

    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = Kategori::when($this->search, function ($queryBuilder) { // Mengubah dari User ke Kategori
            $queryBuilder->whereAny([
                'nama_kategori',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $kategoriToExport = $query->get(); // Mengubah nama variabel
        $fileName = 'Daftar-Kategoris_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx'; // Nama file
        return Excel::download(new KategorisExport($kategoriToExport), $fileName); // Ubah ke KategorisExport
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        $query = Kategori::when($this->search, function ($queryBuilder) { // Mengubah dari User ke Kategori
            $queryBuilder->whereAny([
                'nama_kategori',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $kategoriToExport = $query->get(); // Mengubah nama variabel
        $fileName = 'Daftar-Kategoris_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf'; // Nama file
        $pdf = Pdf::loadView('pdfs.kategoris', ['kategori' => $kategoriToExport]); // Ubah ke pdfs.kategori dan variabel kategori
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
            Excel::import(new KategorisImport, $this->file); // Ubah ke KategorisImport
            session()->flash('message', 'Data kategori berhasil diimpor!');
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
