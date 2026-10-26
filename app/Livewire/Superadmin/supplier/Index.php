<?php

namespace App\Livewire\Superadmin\Supplier; // Perhatikan namespace yang disesuaikan

use App\Exports\SuppliersExport; // Ubah ke SuppliersExport (Anda perlu membuatnya)
use App\Models\Supplier; // Import model Supplier
use Barryvdh\DomPDF\Facade\Pdf; // Untuk ekspor PDF
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel; // Untuk fungsionalitas Excel
use App\Imports\SuppliersImport; // Ubah ke SuppliersImport (Anda perlu membuatnya)
use Livewire\WithFileUploads; // Penting untuk menangani unggahan file

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $nama_supplier, $alamat, $no_hp, $supplier_id;

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
        // Mengubah dari model User ke model Supplier
        $query = Supplier::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama_supplier',
                'alamat',
                'no_hp', // Tambahkan no_hp untuk pencarian
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $suppliers = $query->paginate($this->paginate); // Mengubah nama variabel

        $data = [
            'tittle' => 'Data Penyedia',
            'suppliers' => $suppliers, // Mengubah nama variabel
        ];

        return view('livewire.superadmin.supplier.index', $data); // Perhatikan path view
    }

    // ----------------------------------Mereset Validation----------------------------------------------- //
    public function updatedNamaSupplier() // Sesuaikan nama method
    {
        $this->resetValidation('nama_supplier');
    }

    public function updatedAlamat()
    {
        $this->resetValidation('alamat');
    }

    public function updatedNoHp() // Sesuaikan nama method
    {
        $this->resetValidation('no_hp');
    }


    // ----------------------------------CRUD Data----------------------------------------------- //
    // Tambah
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'nama_supplier',
            'alamat',
            'no_hp',
        ]);
    }

    public function store()
    {
        $this->validate(
            [
                'nama_supplier' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\']+$/'], // Regex untuk nama lebih sederhana
                'alamat' => 'required|string|max:255', // Validasi alamat sebagai string, bukan email, tanpa unique
                'no_hp' => ['required','string','max:15','min:9'], // Validasi no_hp sebagai string, bukan role
            ],
            [
                'nama_supplier.required' => 'Nama Supplier Tidak Boleh Kosong',
                'nama_supplier.regex'                    => 'Nama Tidak Boleh Mengandung Simbol,Nomor Selain Titik dan Apostrof',
                'nama_supplier.string' => 'Nama Supplier Harus Berupa Teks',
                'nama_supplier.max' => 'Nama Supplier Maksimal 255 Karakter',

                'alamat.required' => 'Alamat Tidak Boleh Kosong',
                'alamat.string' => 'Alamat Harus Berupa Teks',
                'alamat.max' => 'Alamat Maksimal 255 Karakter',

                'no_hp.required' => 'Nomor HP tidak boleh kosong. Mohon diisi.',
                'no_hp.string' => 'Nomor HP harus berupa teks atau angka. Periksa kembali input Anda.',
                'no_hp.max' => 'Nomor HP terlalu panjang. Maksimal 15 karakter.',
                
                'no_hp.min' => 'Nomor HP terlalu pendek. Minimal 9 digit diperlukan.'
            ]
        );

        $supplier = new Supplier; // Mengubah dari User ke Supplier
        $supplier->nama_supplier = $this->nama_supplier;
        $supplier->alamat = $this->alamat;
        $supplier->no_hp = $this->no_hp;
        // Hapus baris password, karena tabel supplier tidak memiliki kolom password
        $supplier->save();

        $this->dispatch('closeCreateModal'); // Event untuk menutup modal
    }

    // Edit
    public function edit($id)
    {
        $this->resetValidation();
        $supplier = Supplier::findOrFail($id); // Mengubah dari User ke Supplier
        $this->supplier_id = $supplier->id;
        $this->nama_supplier = $supplier->nama_supplier;
        $this->alamat = $supplier->alamat; // Memperbaiki typo, sebelumnya $this->no_hp = $supplier->alamat
        $this->no_hp = $supplier->no_hp;
    }

    public function update($id)
    {
        $supplier = Supplier::findOrFail($id); // Mengubah dari User ke Supplier

        $rules = [
            'nama_supplier' => ['required', 'string', 'max:255'],
            'alamat' => 'required|string|max:255',
            'no_hp' => ['required', 'string', 'max:15', 'min:9'],        ];

        $messages = [
            'nama_supplier.required' => 'Nama Supplier Tidak Boleh Kosong',
            'nama_supplier.string' => 'Nama Supplier Harus Berupa Teks',
            'nama_supplier.max' => 'Nama Supplier Maksimal 255 Karakter',

            'alamat.required' => 'Alamat Tidak Boleh Kosong',
            'alamat.string' => 'Alamat Harus Berupa Teks',
            'alamat.max' => 'Alamat Maksimal 255 Karakter',

            'no_hp.required' => 'Nomor HP tidak boleh kosong. Mohon diisi.',
            'no_hp.string' => 'Nomor HP harus berupa teks atau angka. Periksa kembali input Anda.',
            'no_hp.max' => 'Nomor HP terlalu panjang. Maksimal 15 karakter.',
            
            'no_hp.min' => 'Nomor HP terlalu pendek. Minimal 9 digit diperlukan.'
        ];

        $this->validate($rules, $messages);

        $supplier->nama_supplier = $this->nama_supplier;
        $supplier->alamat = $this->alamat;
        $supplier->no_hp = $this->no_hp;

        $supplier->save();

        $this->dispatch('closeEditModal'); // Event untuk menutup modal
    }

    // Hapus
    public function confirm($id)
    {
        $supplier = Supplier::findOrFail($id); // Mengubah dari User ke Supplier
        $this->nama_supplier = $supplier->nama_supplier;
        $this->alamat = $supplier->alamat;
        $this->no_hp = $supplier->no_hp;
        $this->supplier_id = $supplier->id;
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id); // Mengubah dari User ke Supplier
        $supplier->delete();
        $this->dispatch('closeDeleteModal'); // Event untuk menutup modal
    }

    // ----------------------------------Eksport Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = Supplier::when($this->search, function ($queryBuilder) { // Mengubah dari User ke Supplier
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

        $suppliersToExport = $query->get(); // Mengubah nama variabel
        $fileName = 'Daftar-Suppliers_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx'; // Nama file
        return Excel::download(new SuppliersExport($suppliersToExport), $fileName); // Ubah ke SuppliersExport
    }

    // ----------------------------------Eksport PDF----------------------------------------------- //
    public function exportPdf()
    {
        $query = Supplier::when($this->search, function ($queryBuilder) { // Mengubah dari User ke Supplier
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

        $suppliersToExport = $query->get(); // Mengubah nama variabel
        $fileName = 'Daftar-Suppliers_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf'; // Nama file
        $pdf = Pdf::loadView('pdfs.suppliers', ['suppliers' => $suppliersToExport]); // Ubah ke pdfs.suppliers dan variabel suppliers
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
            Excel::import(new SuppliersImport, $this->file); // Ubah ke SuppliersImport
            session()->flash('message', 'Data supplier berhasil diimpor!');
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
