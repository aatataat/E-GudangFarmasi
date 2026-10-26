<?php

namespace App\Livewire\Superadmin\RakPenyimpanan;

use Livewire\Component;
use App\Exports\RakPenyimpanansExport; 
use App\Models\RakPenyimpanan; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel; 
use App\Imports\RakPenyimpanansImport; 
use Livewire\WithFileUploads; 
use Illuminate\Validation\Rule; // Import Rule

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $kode_rak, $lokasi_rak, $rak_penyimpanan_id;

    public $file; // Properti untuk menampung file yang diunggah dari form
    public $message = '';

    // ----------------------------------Menampilkan Data----------------------------------------------- //
    public function sortBy($field)
    {
        if ($this->sort === $field) {
            $this->direction = $this->direction === 'asc' ? 'desc' : ($this->direction === 'desc' ? null : 'asc');
            $this->sort = $this->direction === null ? null : $field;
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
        $query = RakPenyimpanan::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'kode_rak',
                'lokasi_rak',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $rak_penyimpanan = $query->paginate($this->paginate); 

        $data = [
            'tittle' => 'Data Rak Penyimpanan',
            'rak_penyimpanan' => $rak_penyimpanan, 
        ];

        return view('livewire.superadmin.rak-penyimpanan.index', $data); 
    }

    // ----------------------------------Mereset Validation----------------------------------------------- //
    // Memperbaiki nama method agar sesuai dengan nama properti
    public function updatedKodeRak()
    {
        $this->resetValidation('kode_rak');
    }

    public function updatedLokasiRak()
    {
        $this->resetValidation('lokasi_rak');
    }


    // ----------------------------------CRUD Data----------------------------------------------- //
    // Tambah
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'kode_rak',
            'lokasi_rak',
            'rak_penyimpanan_id',
        ]);
    }

    public function store()
    {
        $this->validate(
            [
                // Regex mengizinkan Huruf, Angka, Spasi, Strip, Garis Miring, Titik
                'kode_rak' => ['required', 'string', 'max:255', 'unique:rak_penyimpanan,kode_rak', 'regex:/^[a-zA-Z0-9\s\-\/\.]+$/'],
                'lokasi_rak' => ['required', 'string', 'max:255', 'unique:rak_penyimpanan,lokasi_rak', 'regex:/^[a-zA-Z0-9\s\.\,\-]+$/'],
            ],
            [
                'kode_rak.required' => 'Kode Rak Tidak Boleh Kosong',
                'kode_rak.regex' => 'Kode Rak hanya Boleh Berisi Huruf, Angka, Spasi, Strip, Garis Miring, dan Titik',
                'kode_rak.string' => 'Kode Rak Harus Berupa Teks',
                'kode_rak.max' => 'Kode Rak Maksimal 255 Karakter',
                'kode_rak.unique' => 'Kode Rak Sudah Ada',

                'lokasi_rak.required' => 'Lokasi Rak Tidak Boleh Kosong',
                'lokasi_rak.regex' => 'Lokasi Rak hanya Boleh Berisi Huruf, Angka, Spasi, Titik, Koma, dan Strip',
                'lokasi_rak.string' => 'Lokasi Rak Harus Berupa Teks',
                'lokasi_rak.max' => 'Lokasi Rak Maksimal 255 Karakter',
                'lokasi_rak.unique' => 'Lokasi Rak Sudah Ada',
            ]
        );

        $rak_penyimpanan = new RakPenyimpanan; 
        $rak_penyimpanan->kode_rak = $this->kode_rak;
        $rak_penyimpanan->lokasi_rak = $this->lokasi_rak;
        $rak_penyimpanan->save();

        session()->flash('success', 'Data Rak Penyimpanan berhasil ditambahkan!');
        $this->dispatch('closeCreateModal'); 
    }

    // Edit
    public function edit($id)
    {
        $this->resetValidation();
        $rak_penyimpanan = RakPenyimpanan::findOrFail($id); 
        $this->rak_penyimpanan_id = $rak_penyimpanan->id;
        $this->kode_rak = $rak_penyimpanan->kode_rak;
        $this->lokasi_rak = $rak_penyimpanan->lokasi_rak;
    }

    public function update($id)
    {
        $rak_penyimpanan = RakPenyimpanan::findOrFail($id); 
        
        // Menggunakan Rule::unique untuk pengecualian ID
        $rules = [
            'kode_rak' => [
                'required', 'string', 'max:255',
                Rule::unique('rak_penyimpanan', 'kode_rak')->ignore($id),
                'regex:/^[a-zA-Z0-9\s\-\/\.]+$/'
            ],
            'lokasi_rak' => [
                'required', 'string', 'max:255',
                Rule::unique('rak_penyimpanan', 'lokasi_rak')->ignore($id),
                'regex:/^[a-zA-Z0-9\s\.\,\-]+$/'
            ],
        ];

        $messages = [
            'kode_rak.required' => 'Kode Rak Tidak Boleh Kosong',
            'kode_rak.regex' => 'Kode Rak hanya Boleh Berisi Huruf, Angka, Spasi, Strip, Garis Miring, dan Titik',
            'kode_rak.string' => 'Kode Rak Harus Berupa Teks',
            'kode_rak.max' => 'Kode Rak Maksimal 255 Karakter',
            'kode_rak.unique' => 'Kode Rak Sudah Ada',

            'lokasi_rak.required' => 'Lokasi Rak Tidak Boleh Kosong',
            'lokasi_rak.regex' => 'Lokasi Rak hanya Boleh Berisi Huruf, Angka, Spasi, Titik, Koma, dan Strip',
            'lokasi_rak.string' => 'Lokasi Rak Harus Berupa Teks',
            'lokasi_rak.max' => 'Lokasi Rak Maksimal 255 Karakter',
            'lokasi_rak.unique' => 'Lokasi Rak Sudah Ada',
        ];

        $this->validate($rules, $messages);

        $rak_penyimpanan->kode_rak = $this->kode_rak;
        $rak_penyimpanan->lokasi_rak = $this->lokasi_rak;

        $rak_penyimpanan->save();

        session()->flash('success', 'Data Rak Penyimpanan berhasil diperbarui!');
        $this->dispatch('closeEditModal'); 
    }

    // Hapus
    public function confirm($id)
    {
        $rak_penyimpanan = RakPenyimpanan::findOrFail($id); 
        $this->kode_rak = $rak_penyimpanan->kode_rak;
        $this->lokasi_rak = $rak_penyimpanan->lokasi_rak;
        $this->rak_penyimpanan_id = $rak_penyimpanan->id;
    }

    public function destroy($id)
    {
        $rak_penyimpanan = RakPenyimpanan::findOrFail($id); 
        $rak_penyimpanan->delete();
        session()->flash('success', 'Data Rak Penyimpanan berhasil dihapus!');
        $this->dispatch('closeDeleteModal'); 
    }

    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = RakPenyimpanan::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'kode_rak',
                'lokasi_rak',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $rak_penyimpananToExport = $query->get();
        $fileName = 'Daftar-RakPenyimpanans_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new RakPenyimpanansExport($rak_penyimpananToExport), $fileName);
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        $query = RakPenyimpanan::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'kode_rak',
                'lokasi_rak',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $rak_penyimpananToExport = $query->get();
        $fileName = 'Daftar-RakPenyimpanans_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        // Pastikan view pdfs.rak_penyimpanans tersedia
        $pdf = Pdf::loadView('pdfs.rak_penyimpanans', ['rak_penyimpanan' => $rak_penyimpananToExport]);
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
            Excel::import(new RakPenyimpanansImport, $this->file);
            session()->flash('success', 'Data Rak Penyimpanan berhasil diimpor!'); // Flash message success
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            
            // Membuat pesan error yang lebih informatif
            $errorMessage = 'Gagal mengimpor data. Periksa kembali format file. Detail: ';
            foreach ($failures as $failure) {
                // Menampilkan baris dan pesan kesalahan yang spesifik
                $errorMessage .= " Baris " . $failure->row() . ": " . implode(", ", $failure->errors()) . ". ";
            }
            session()->flash('error', $errorMessage); // Flash message error detail
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->reset('file');
        $this->dispatch('closeImportModal'); // Event untuk menutup modal
    }

    public function importExcel()
    {
        $this->reset(['file', 'message']);
        $this->resetValidation('file'); // Reset validasi file saat membuka modal
        $this->dispatch('show-import-modal'); // Event untuk membuka modal
    }
}