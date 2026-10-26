<?php

namespace App\Livewire\Superadmin\Kecamatan;

use Livewire\Component;
use App\Exports\KecamatansExport; // Ubah ke KecamatansExport (Anda perlu membuatnya)
use App\Models\Kecamatan; // Import model Kecamatan
use Barryvdh\DomPDF\Facade\Pdf; // Untuk ekspor PDF
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel; // Untuk fungsionalitas Excel
use App\Imports\KecamatansImport; // Ubah ke KecamatansImport (Anda perlu membuatnya)
use Livewire\WithFileUploads; // Penting untuk menangani unggahan file

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $nama_kecamatan,$kecamatan_id;

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
        // Mengubah dari model User ke model Kecamatan
        $query = Kecamatan::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_kecamatan',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $kecamatan = $query->paginate($this->paginate); // Mengubah nama variabel

        $data = [
            'tittle' => 'Data Kecamatan',
            'kecamatan' => $kecamatan, // Mengubah nama variabel
        ];

        return view('livewire.superadmin.kecamatan.index', $data); // Perhatikan path view
    }

    // ----------------------------------Mereset Validation----------------------------------------------- //
    public function updatedNamaKecamatan() // Sesuaikan nama method
    {
        $this->resetValidation('nama_kecamatan');
    }


    // ----------------------------------CRUD Data----------------------------------------------- //
    // Tambah
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'nama_kecamatan',
        ]);
    }

    public function store()
    {
        $this->validate(
            [
            'nama_kecamatan' => ['required', 'string', 'max:255','unique:kecamatan,nama_kecamatan'],                
            ],
            [
                'nama_kecamatan.required' => 'Nama Kecamatan Tidak Boleh Kosong',
                'nama_kecamatan.regex'                    => 'Nama Tidak Boleh Mengandung Simbol,Nomor Selain Titik dan Apostrof',
                'nama_kecamatan.string' => 'Nama Kecamatan Harus Berupa Teks',
                'nama_kecamatan.max' => 'Nama Kecamatan Maksimal 255 Karakter',
                'nama_kecamatan.unique' => 'Nama Kecamatan Sudah Ada',
            ]
        );

        $kecamatan = new Kecamatan; // Mengubah dari User ke Kecamatan
        $kecamatan->nama_kecamatan = $this->nama_kecamatan;
        // Hapus baris password, karena tabel kecamatan tidak memiliki kolom password
        $kecamatan->save();

        $this->dispatch('closeCreateModal'); // Event untuk menutup modal
    }

    // Edit
    public function edit($id)
    {
        $this->resetValidation();
        $kecamatan = Kecamatan::findOrFail($id); // Mengubah dari User ke Kecamatan
        $this->kecamatan_id = $kecamatan->id;
        $this->nama_kecamatan = $kecamatan->nama_kecamatan;
    }

    public function update($id)
    {
        $kecamatan = Kecamatan::findOrFail($id); // Mengubah dari User ke Kecamatan

        $rules = [
            'nama_kecamatan' => 'required|unique:kecamatan,nama_kecamatan,'. $id .'|string| max:255',
        ];

        $messages = [
            'nama_kecamatan.required' => 'Nama Kecamatan Tidak Boleh Kosong',
            'nama_kecamatan.string' => 'Nama Kecamatan Harus Berupa Teks',
            'nama_kecamatan.max' => 'Nama Kecamatan Maksimal 255 Karakter',
            'nama_kecamatan.unique' => 'Nama Kecamatan Sudah Ada',
        ];

        $this->validate($rules, $messages);

        $kecamatan->nama_kecamatan = $this->nama_kecamatan;

        $kecamatan->save();

        $this->dispatch('closeEditModal'); // Event untuk menutup modal
    }

    // Hapus
    public function confirm($id)
    {
        $kecamatan = Kecamatan::findOrFail($id); // Mengubah dari User ke Kecamatan
        $this->nama_kecamatan = $kecamatan->nama_kecamatan;
        $this->kecamatan_id = $kecamatan->id;
    }

    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id); // Mengubah dari User ke Kecamatan
        $kecamatan->delete();
        $this->dispatch('closeDeleteModal'); // Event untuk menutup modal
    }

    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = Kecamatan::when($this->search, function ($queryBuilder) { // Mengubah dari User ke Kecamatan
            $queryBuilder->whereAny([
                'nama_kecamatan',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $kecamatanToExport = $query->get(); // Mengubah nama variabel
        $fileName = 'Daftar-Kecamatans_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx'; // Nama file
        return Excel::download(new KecamatansExport($kecamatanToExport), $fileName); // Ubah ke KecamatansExport
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        $query = Kecamatan::when($this->search, function ($queryBuilder) { // Mengubah dari User ke Kecamatan
            $queryBuilder->whereAny([
                'nama_kecamatan',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $kecamatanToExport = $query->get(); // Mengubah nama variabel
        $fileName = 'Daftar-Kecamatans_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf'; // Nama file
        $pdf = Pdf::loadView('pdfs.kecamatans', ['kecamatan' => $kecamatanToExport]); // Ubah ke pdfs.kecamatan dan variabel kecamatan
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
            Excel::import(new KecamatansImport, $this->file); // Ubah ke KecamatansImport
            session()->flash('message', 'Data kecamatan berhasil diimpor!');
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
