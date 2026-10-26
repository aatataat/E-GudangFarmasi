<?php

namespace App\Livewire\Superadmin\Faskes;

use App\Exports\FaskesExport;
use App\Models\Faskes;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FaskesImport;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule; // <-- Sudah ada, tapi penting untuk Rule::unique

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    // Properti LENGKAP untuk form CRUD Faskes
    public $nama_faskes, $alamat, $telp, $email, $nama_kepala, $nip_kepala, $nama_apoteker, $nip_apoteker, $faskes_id; 
    public $file; // Properti untuk import excel

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
        // Query untuk pencarian, menggunakan nama_faskes, alamat, dan nama_kepala
        $query = Faskes::query()
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where('nama_faskes', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%')
                    ->orWhere('nama_kepala', 'like', '%' . $this->search . '%');
            });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $faskes = $query->paginate($this->paginate);

        $data = [
            'tittle' => 'Data Faskes (Fasilitas Kesehatan)',
            'faskes' => $faskes,
        ];

        return view('livewire.superadmin.faskes.index', $data);
    }

    // ----------------------------------Mereset Validation----------------------------------------------- //
    // Menambahkan method untuk mereset validasi untuk setiap properti secara individual
    public function updatedNamaFaskes() {$this->resetValidation('nama_faskes');}
    public function updatedAlamat() {$this->resetValidation('alamat');}
    public function updatedTelp() {$this->resetValidation('telp');}
    public function updatedEmail() {$this->resetValidation('email');}
    public function updatedNamaKepala() {$this->resetValidation('nama_kepala');}
    public function updatedNipKepala() {$this->resetValidation('nip_kepala');}
    public function updatedNamaApoteker() {$this->resetValidation('nama_apoteker');}
    public function updatedNipApoteker() {$this->resetValidation('nip_apoteker');}
    
    public function resetFormValidation()
    {
        $this->resetValidation([
            'nama_faskes',
            'alamat',
            'telp',
            'email',
            'nama_kepala',
            'nip_kepala',
            'nama_apoteker',
            'nip_apoteker',
        ]);
    }

    // ----------------------------------CRUD Data----------------------------------------------- //
    public function create()
    {
        $this->resetFormValidation();
        // Mereset semua properti form
        $this->reset([
            'nama_faskes',
            'alamat',
            'telp',
            'email',
            'nama_kepala',
            'nip_kepala',
            'nama_apoteker',
            'nip_apoteker',
            'faskes_id'
        ]);
    }

    public function store()
    {
        $this->validate(
            [
                // Unique Name Faskes
                'nama_faskes' => ['required', 'string', 'max:255', 'unique:faskes,nama_faskes', 'regex:/^[a-zA-Z0-9\s\.\,\-\(\)\/]+$/'],
                'alamat' => 'required|string|max:255',
                // Unique Telp. Max 15 digit.
                'telp' => ['required','max:15', 'unique:faskes,telp', 'regex:/^[0-9]+$/'], 
                // Unique Email
                'email' => ['required', 'email', 'max:255', 'unique:faskes,email'], 
                // Nama Kepala: Hanya Huruf, Spasi, Titik, Apostrof.
                'nama_kepala' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\']+$/'],
                // NIP Kepala: Unique, Angka, Min/Max untuk NIP.
                'nip_kepala' => ['required', 'string', 'min:15', 'max:25', 'unique:faskes,nip_kepala', 'regex:/^[0-9]+$/'], 
                // Nama Apoteker: Hanya Huruf, Spasi, Titik, Apostrof.
                'nama_apoteker' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\']+$/'],
                // NIP Apoteker: Unique, Angka, Min/Max untuk NIP.
                'nip_apoteker' => ['required', 'string', 'min:15', 'max:25', 'unique:faskes,nip_apoteker', 'regex:/^[0-9]+$/'], 
            ],
            [
                'nama_faskes.required' => 'Nama Faskes Tidak Boleh Kosong',
                'nama_faskes.unique' => 'Nama Faskes Sudah Ada',
                'nama_faskes.regex' => 'Nama Faskes Mengandung Karakter Yang Tidak Diizinkan',
                
                'alamat.required' => 'Alamat Tidak Boleh Kosong',
                
                'telp.required' => 'Nomor Telepon Tidak Boleh Kosong',
                'telp.unique' => 'Nomor Telepon Sudah Terdaftar.', // <-- Pesan Unique
                'telp.max' => 'Nomor Telepon Maksimal 15 Angka.',
                'telp.regex' => 'Nomor Telepon Hanya Boleh Berisi Angka',
                
                'email.required' => 'Email Tidak Boleh Kosong',
                'email.email' => 'Format Email Tidak Valid',
                'email.unique' => 'Email Sudah Terdaftar.', // <-- Pesan Unique

                'nama_kepala.required' => 'Nama Kepala Faskes Tidak Boleh Kosong',
                'nama_kepala.regex' => 'Nama Kepala Faskes Hanya Boleh Berisi Huruf dan Karakter Dasar',

                'nip_kepala.required' => 'NIP Kepala Faskes Tidak Boleh Kosong',
                'nip_kepala.unique' => 'NIP Kepala Faskes Sudah Terdaftar.', // <-- Pesan Unique
                'nip_kepala.min' => 'NIP Kepala Faskes Minimal 15 Angka.',
                'nip_kepala.regex' => 'NIP Kepala Faskes Hanya Boleh Berisi Angka',

                'nama_apoteker.required' => 'Nama Apoteker Tidak Boleh Kosong',
                'nama_apoteker.regex' => 'Nama Apoteker Hanya Boleh Berisi Huruf dan Karakter Dasar',

                'nip_apoteker.required' => 'NIP Apoteker Tidak Boleh Kosong',
                'nip_apoteker.unique' => 'NIP Apoteker Sudah Terdaftar.', // <-- Pesan Unique
                'nip_apoteker.min' => 'NIP Apoteker Minimal 15 Angka.',
                'nip_apoteker.regex' => 'NIP Apoteker Hanya Boleh Berisi Angka',
            ]
        );

        $faskes = new Faskes;
        $faskes->nama_faskes = $this->nama_faskes;
        $faskes->alamat = $this->alamat;
        $faskes->telp = $this->telp;
        $faskes->email = $this->email;
        $faskes->nama_kepala = $this->nama_kepala;
        $faskes->nip_kepala = $this->nip_kepala;
        $faskes->nama_apoteker = $this->nama_apoteker;
        $faskes->nip_apoteker = $this->nip_apoteker;
        
        $faskes->save();

        session()->flash('success', 'Data Faskes berhasil ditambahkan!');
        $this->dispatch('closeCreateModal');
    }

    public function edit($id)
    {
        $this->resetFormValidation();
        $faskes = Faskes::findOrFail($id);
        
        $this->faskes_id = $faskes->id;
        $this->nama_faskes = $faskes->nama_faskes;
        $this->alamat = $faskes->alamat;
        
        // Memuat semua properti Faskes
        $this->telp = $faskes->telp;
        $this->email = $faskes->email;
        $this->nama_kepala = $faskes->nama_kepala;
        $this->nip_kepala = $faskes->nip_kepala;
        $this->nama_apoteker = $faskes->nama_apoteker;
        $this->nip_apoteker = $faskes->nip_apoteker;
    }

    public function update($id)
    {
        $faskes = Faskes::findOrFail($id);

        $rules = [
            'nama_faskes' => [
                'required', 'string', 'max:255',
                Rule::unique('faskes', 'nama_faskes')->ignore($faskes->id), // Unique kecuali Faskes ini
                
            ],
            'alamat' => 'required|string|max:255',
            // Unique Telp dikecualikan
            'telp' => ['required', 'max:15', Rule::unique('faskes', 'telp')->ignore($faskes->id)], 
            // Unique Email dikecualikan
            'email' => ['required', 'email', 'max:255', Rule::unique('faskes', 'email')->ignore($faskes->id)], 
            // Nama Kepala: Hanya Huruf
            'nama_kepala' => ['required', 'string', 'max:255'],
            // Unique NIP Kepala dikecualikan
            'nip_kepala' => ['required', 'string', 'min:15', 'max:25', Rule::unique('faskes', 'nip_kepala')->ignore($faskes->id), 'regex:/^[0-9]+$/'], 
            // Nama Apoteker: Hanya Huruf
            'nama_apoteker' => ['required', 'string', 'max:255'],
            // Unique NIP Apoteker dikecualikan
            'nip_apoteker' => ['required', 'string', 'min:15', 'max:25', Rule::unique('faskes', 'nip_apoteker')->ignore($faskes->id), 'regex:/^[0-9]+$/'], 
        ];

        $messages = [
            'nama_faskes.required' => 'Nama Faskes Tidak Boleh Kosong',
            'nama_faskes.unique' => 'Nama Faskes Sudah Ada',

            'alamat.required' => 'Alamat Tidak Boleh Kosong',

            'telp.required' => 'Nomor Telepon Tidak Boleh Kosong',
            'telp.unique' => 'Nomor Telepon Sudah Terdaftar.',
            'telp.max' => 'Nomor Telepon Maksimal 15 Angka.',
            'telp.regex' => 'Nomor Telepon Hanya Boleh Berisi Angka',

            'email.required' => 'Email Tidak Boleh Kosong',
            'email.email' => 'Format Email Tidak Valid',
            'email.unique' => 'Email Sudah Terdaftar.',

            'nama_kepala.required' => 'Nama Kepala Faskes Tidak Boleh Kosong',

            'nip_kepala.required' => 'NIP Kepala Faskes Tidak Boleh Kosong',
            'nip_kepala.unique' => 'NIP Kepala Faskes Sudah Terdaftar.',
            'nip_kepala.min' => 'NIP Kepala Faskes Minimal 15 Angka.',

            'nama_apoteker.required' => 'Nama Apoteker Tidak Boleh Kosong',

            'nip_apoteker.required' => 'NIP Apoteker Tidak Boleh Kosong',
            'nip_apoteker.unique' => 'NIP Apoteker Sudah Terdaftar.',
            'nip_apoteker.min' => 'NIP Apoteker Minimal 15 Angka.',
        ];

        $this->validate($rules, $messages);

        $faskes->nama_faskes = $this->nama_faskes;
        $faskes->alamat = $this->alamat;
        // Memperbarui semua properti Faskes
        $faskes->telp = $this->telp;
        $faskes->email = $this->email;
        $faskes->nama_kepala = $this->nama_kepala;
        $faskes->nip_kepala = $this->nip_kepala;
        $faskes->nama_apoteker = $this->nama_apoteker;
        $faskes->nip_apoteker = $this->nip_apoteker;
        
        $faskes->save();

        session()->flash('success', 'Data Faskes berhasil diperbarui!');
        $this->dispatch('closeEditModal');
    }

    public function confirm($id)
    {
        $faskes = Faskes::findOrFail($id);
        
        $this->nama_faskes = $faskes->nama_faskes;
        $this->alamat = $faskes->alamat;
        
        // Memuat semua properti Faskes untuk konfirmasi
        $this->telp = $faskes->telp;
        $this->email = $faskes->email;
        $this->nama_kepala = $faskes->nama_kepala;
        $this->nip_kepala = $faskes->nip_kepala;
        $this->nama_apoteker = $faskes->nama_apoteker;
        $this->nip_apoteker = $faskes->nip_apoteker;
        
        $this->faskes_id = $faskes->id;
    }

    public function destroy($id)
    {
        $faskes = Faskes::findOrFail($id);
        $faskes->delete();
        session()->flash('success', 'Data Faskes berhasil dihapus!');
        $this->dispatch('closeDeleteModal');
    }

    // ----------------------------------Export Excel----------------------------------------------- //
    public function exportExcel()
    {
        $query = Faskes::query()
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where('nama_faskes', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%')
                    ->orWhere('nama_kepala', 'like', '%' . $this->search . '%');
            });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $faskesToExport = $query->get();
        $fileName = 'Daftar-Faskes_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new FaskesExport($faskesToExport), $fileName);
    }

    // ----------------------------------Export PDF----------------------------------------------- //
    public function exportPdf()
    {
        $query = Faskes::query()
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where('nama_faskes', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%')
                    ->orWhere('nama_kepala', 'like', '%' . $this->search . '%');
            });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $faskesToExport = $query->get();
        $fileName = 'Daftar-Faskes_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        // Pastikan Anda telah membuat view Blade di resources/views/pdfs/faskes.blade.php
        $pdf = Pdf::loadView('pdfs.faskes', ['faskes' => $faskesToExport]);
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
            Excel::import(new FaskesImport, $this->file);
            session()->flash('message', 'Data Faskes berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessage = 'Gagal mengimpor data. Periksa kembali format file. Kesalahan: ';
            foreach ($failures as $failure) {
                $errorMessage .= " Baris " . $failure->row() . ": " . implode(", ", $failure->errors()) . ". ";
            }
            session()->flash('error', $errorMessage);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->reset('file');
        $this->dispatch('closeImportModal');
    }

    public function importExcel()
    {
        $this->reset(['file']);
        $this->resetValidation('file');
        $this->dispatch('show-import-modal');
    }
}