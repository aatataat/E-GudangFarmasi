<?php

namespace App\Livewire\Superadmin\Obat;

use Livewire\Component;
use App\Exports\ObatsExport;
use App\Models\Obat;
use App\Models\Kategori;
use App\Models\Satuan;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ObatsImport;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule; // Import Rule untuk validasi unique di update

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $nama_obat, $id_kategori, $id_satuan, $obat_id;

    public $file;
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
        // Mencari berdasarkan nama_obat, atau nama kategori/satuan (melalui relasi)
        $query = Obat::query()->when($this->search, function ($queryBuilder) {
            $queryBuilder->where('nama_obat', 'like', '%' . $this->search . '%')
                ->orWhereHas('kategori', function ($q) {
                    $q->where('nama_kategori', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('satuan', function ($q) {
                    $q->where('nama_satuan', 'like', '%' . $this->search . '%');
                });
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Eager loading relasi untuk performa
        $obats = $query->with(['kategori', 'satuan'])->paginate($this->paginate);
        $kategoris = Kategori::all();
        $satuans = Satuan::all();

        $data = [
            'tittle' => 'Data Barang',
            'obats' => $obats,
            'kategoris' => $kategoris,
            'satuans' => $satuans,
        ];

        return view('livewire.superadmin.obat.index', $data);
    }

    // ----------------------------------Mereset Validation----------------------------------------------- //
    public function updatedNamaObat()
    {
        $this->resetValidation('nama_obat');
    }

    public function updatedIdKategori()
    {
        $this->resetValidation('id_kategori');
    }

    public function updatedIdSatuan()
    {
        $this->resetValidation('id_satuan');
    }

    // ----------------------------------CRUD Data----------------------------------------------- //
    // Tambah
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'nama_obat',
            'id_kategori',
            'id_satuan',
            'obat_id', // Pastikan obat_id juga direset
        ]);
    }

    public function store()
    {
        $this->validate([
            // Regex mengizinkan huruf, angka, spasi, tanda kurung, garis miring, titik, koma, strip, dan plus.
            'nama_obat' => ['required', 'string', 'max:255', 'unique:obat,nama_obat'],
            'id_kategori' => 'required|exists:kategori,id',
            'id_satuan' => 'required|exists:satuan,id',
        ], [
            'nama_obat.required' => 'Nama Obat Tidak Boleh Kosong',
            'nama_obat.string' => 'Nama Obat Harus Berupa Teks',
            'nama_obat.max' => 'Nama Obat Maksimal 255 Karakter',
            'nama_obat.unique' => 'Nama Obat Sudah Ada',
            'id_kategori.required' => 'Kategori Tidak Boleh Kosong',
            'id_kategori.exists' => 'Kategori Tidak Valid',
            'id_satuan.required' => 'Satuan Tidak Boleh Kosong',
            'id_satuan.exists' => 'Satuan Tidak Valid',
        ]);

        $obat = new Obat;
        $obat->nama_obat = $this->nama_obat;
        $obat->id_kategori = $this->id_kategori;
        $obat->id_satuan = $this->id_satuan;
        $obat->save();

        session()->flash('success', 'Data Obat berhasil ditambahkan!');
        $this->dispatch('closeCreateModal');
    }

    // Edit
    public function edit($id)
    {
        $this->resetValidation();
        $obat = Obat::findOrFail($id);
        $this->obat_id = $obat->id;
        $this->nama_obat = $obat->nama_obat;
        $this->id_kategori = $obat->id_kategori;
        $this->id_satuan = $obat->id_satuan;
    }

    public function update($id)
    {
        $obat = Obat::findOrFail($id);
        
        $this->validate([
            'nama_obat' => [
                'required', 
                'string', 
                'max:255', 
                // Menggunakan Rule::unique dengan pengecualian ID
                Rule::unique('obat', 'nama_obat')->ignore($id),
                
            ],
            'id_kategori' => 'required|exists:kategori,id',
            'id_satuan' => 'required|exists:satuan,id',
        ], [
            'nama_obat.required' => 'Nama Obat Tidak Boleh Kosong',
            'nama_obat.string' => 'Nama Obat Harus Berupa Teks',
            'nama_obat.max' => 'Nama Obat Maksimal 255 Karakter',
            'nama_obat.unique' => 'Nama Obat Sudah Ada',
            'id_kategori.required' => 'Kategori Tidak Boleh Kosong',
            'id_kategori.exists' => 'Kategori Tidak Valid',
            'id_satuan.required' => 'Satuan Tidak Boleh Kosong',
            'id_satuan.exists' => 'Satuan Tidak Valid',
        ]);

        $obat->nama_obat = $this->nama_obat;
        $obat->id_kategori = $this->id_kategori;
        $obat->id_satuan = $this->id_satuan;
        $obat->save();

        session()->flash('success', 'Data Obat berhasil diperbarui!');
        $this->dispatch('closeEditModal');
    }

    // Hapus
    public function confirm($id)
    {
        $obat = Obat::findOrFail($id);
        $this->nama_obat = $obat->nama_obat;
        $this->obat_id = $obat->id;
    }

    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();
        session()->flash('success', 'Data Obat berhasil dihapus!');
        $this->dispatch('closeDeleteModal');
    }

    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = Obat::query()->when($this->search, function ($queryBuilder) {
            $queryBuilder->where('nama_obat', 'like', '%' . $this->search . '%')
                ->orWhereHas('kategori', function ($q) {
                    $q->where('nama_kategori', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('satuan', function ($q) {
                    $q->where('nama_satuan', 'like', '%' . $this->search . '%');
                });
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Eager loading untuk data eksport
        $obatsToExport = $query->with(['kategori', 'satuan'])->get();
        $fileName = 'Daftar-Obat_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new ObatsExport($obatsToExport), $fileName);
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        $query = Obat::query()->when($this->search, function ($queryBuilder) {
            $queryBuilder->where('nama_obat', 'like', '%' . $this->search . '%')
                ->orWhereHas('kategori', function ($q) {
                    $q->where('nama_kategori', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('satuan', function ($q) {
                    $q->where('nama_satuan', 'like', '%' . $this->search . '%');
                });
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Eager loading untuk data eksport
        $obatsToExport = $query->with(['kategori', 'satuan'])->get();
        $fileName = 'Daftar-Obat_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        $pdf = Pdf::loadView('pdfs.obats', ['obats' => $obatsToExport]);
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
            Excel::import(new ObatsImport, $this->file);
            session()->flash('success', 'Data obat berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            
            // Membuat pesan error yang lebih informatif
            $errorMessage = 'Gagal mengimpor data. Periksa kembali format file. Detail: ';
            foreach ($failures as $failure) {
                // Menampilkan baris dan pesan kesalahan yang spesifik
                $errorMessage .= " Baris " . $failure->row() . ": " . implode(", ", $failure->errors()) . ". ";
            }
            session()->flash('error', $errorMessage);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->reset('file');
        $this->dispatch('closeImportModal'); // Menutup modal import
    }

    public function importExcel()
    {
        $this->reset(['file', 'message']);
        $this->resetValidation('file'); // Reset validasi file saat membuka modal
        $this->dispatch('show-import-modal');
    }
}