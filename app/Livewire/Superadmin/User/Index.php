<?php

namespace App\Livewire\Superadmin\User;

use App\Exports\UsersExport;
use App\Models\Faskes;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $nama, $email, $role, $faskes_id, $password, $password_confirmation, $user_id;

    public $file;
    public $message = '';

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

    public function render()
    {
        $query = User::when($this->search, function ($queryBuilder) {
            $queryBuilder->whereAny([
                'nama',
                'email',
            ], 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $user = $query->paginate($this->paginate);

        $faskesList = Faskes::select('id', 'nama_faskes')->orderBy('nama_faskes', 'asc')->get();


        $data = [
            'tittle' => 'Data User',
            'user' => $user,
            'faskesList' => $faskesList,
        ];

        return view('livewire.superadmin.user.index', $data);
    }

    public function updatedRole()
    {
        $this->resetValidation('role');
        $this->reset('faskes_id');
    }

    public function updatedFaskesId()
    {
        $this->resetValidation('faskes_id');
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'nama',
            'email',
            'role',
            'faskes_id',
            'password',
            'password_confirmation',
        ]);
    }

    public function store()
    {
        $rules = [
            'nama'                  => ['required', 'string', 'max:255'],
            'email'                 => 'required|email:rfc|unique:users,email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'role'                  => 'required|not_in:-- Pilih Role --|in:Admin,Faskes,Pegawai,Pimpinan',
            'password'              => ['required', 'string', 'min:8', Password::defaults()],
            'password_confirmation' => 'required|same:password',
            'faskes_id'             => 'nullable|exists:faskes,id',
        ];

        $messages = [
            'nama.required'                 => 'Nama Tidak Boleh Kosong',
            'nama.string'                   => 'Nama Harus Berupa Teks',
            'nama.max'                      => 'Nama Maksimal 255 Karakter',

            'email.required'                => 'Email Tidak Boleh Kosong',
            'email.email'                   => 'Format Email Tidak Valid (Contoh: user@domain.com)',
            'email.regex'                   => 'Format Email Tidak Valid (Contoh: user@domain.com)',
            'email.unique'                  => 'Email Sudah Terdaftar',
            'email.max'                     => 'Email Maksimal 255 Karakter',

            'role.required'                 => 'Role Tidak Boleh Kosong',
            'role.not_in'                   => 'Role Tidak Boleh Kosong',
            'role.in'                       => 'Role Yang Dipilih Tidak Valid',

            // PERUBAHAN PESAN
            'faskes_id.required_if'         => 'Faskes wajib dipilih untuk role Faskes.',
            'faskes_id.exists'              => 'Faskes yang dipilih tidak valid',

            'password.required'             => 'Password Tidak Boleh Kosong',
            'password.min'                  => 'Password Minimal 8 Karakter',
            'password_confirmation.required' => 'Konfirmasi Password Tidak Boleh Kosong',
            'password_confirmation.same'    => 'Konfirmasi Password Tidak Cocok Dengan Password Utama',
        ];

        // PERBAIKAN LOGIKA 1: Hanya 'Faskes' yang wajib
        if ($this->role === 'Faskes') {
            $rules['faskes_id'] = 'required|exists:faskes,id';
        }

        $this->validate($rules, $messages);

        $user = new User;
        $user->nama      = $this->nama;
        $user->email     = $this->email;
        $user->role      = $this->role;

        // PERBAIKAN LOGIKA 2: Hanya isi faskes_id jika role adalah 'Faskes', jika tidak, NULL
        $user->faskes_id = ($this->role === 'Faskes') ? $this->faskes_id : null;

        $user->password  = Hash::make($this->password);
        $user->save();

        $this->dispatch('closeCreateModal');
    }

    public function edit($id)
    {
        $this->resetValidation();
        $user = User::findOrFail($id);
        $this->user_id             = $user->id;
        $this->nama                = $user->nama;
        $this->email               = $user->email;
        $this->role                = $user->role;
        $this->faskes_id           = $user->faskes_id;
        $this->password            = '';
        $this->password_confirmation = '';
    }

    public function update($id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'nama'                  => ['required', 'string', 'max:255'],
            'email'                 => 'required|email:rfc|unique:users,email,' . $id . '|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'role'                  => 'required|not_in:-- Pilih Role --|in:Admin,Faskes,Pegawai,Pimpinan',
            'faskes_id'             => 'nullable|exists:faskes,id',
        ];

        $messages = [
            // ... (Pesan validasi sama)
            'nama.required'                 => 'Nama Tidak Boleh Kosong',
            'nama.string'                   => 'Nama Harus Berupa Teks',
            'nama.max'                      => 'Nama Maksimal 255 Karakter',

            'email.required'                => 'Email Tidak Boleh Kosong',
            'email.email'                   => 'Format Email Tidak Valid (Contoh: user@domain.com)',
            'email.regex'                   => 'Format Email Tidak Valid (Contoh: user@domain.com)',
            'email.unique'                  => 'Email Sudah Terdaftar',
            'email.max'                     => 'Email Maksimal 255 Karakter',

            'role.required'                 => 'Role Tidak Boleh Kosong',
            'role.not_in'                   => 'Role Tidak Boleh Kosong',
            'role.in'                       => 'Role Yang Dipilih Tidak Valid',

            'faskes_id.required_if'         => 'Faskes wajib dipilih untuk role Faskes.',
            'faskes_id.exists'              => 'Faskes yang dipilih tidak valid',

            'password.required'             => 'Password Tidak Boleh Kosong',
            'password.min'                  => 'Password Minimal 8 Karakter',
            'password_confirmation.required' => 'Konfirmasi Password Tidak Boleh Kosong',
            'password_confirmation.same'    => 'Konfirmasi Password Tidak Cocok Dengan Password Utama',
        ];

        // PERBAIKAN LOGIKA 3: Hanya 'Faskes' yang wajib
        if ($this->role === 'Faskes') {
            $rules['faskes_id'] = 'required|exists:faskes,id';
        } else {
            // PERBAIKAN LOGIKA 4: Set faskes_id menjadi null jika role bukan Faskes
            $this->faskes_id = null;
        }

        if (!empty($this->password)) {
            $rules['password'] = ['required', 'string', 'min:8', Password::defaults()];
            $rules['password_confirmation'] = 'required|same:password';
        }

        $this->validate($rules, $messages);

        $user->nama      = $this->nama;
        $user->email     = $this->email;
        $user->role      = $this->role;
        // LOGIKA PENGISIAN: Gunakan nilai yang sudah disetel ke null di atas (PERBAIKAN LOGIKA 4)
        $user->faskes_id = $this->faskes_id;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }
        $user->save();

        $this->dispatch('closeEditModal');
    }

    public function confirm($id)
    {
        // Mengambil data User berdasarkan ID
        $user = User::findOrFail($id);

        // Menyimpan data ke properti untuk ditampilkan di modal konfirmasi
        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->user_id = $user->id;

        // Jika Anda menggunakan modal, panggil dispatch untuk membukanya (opsional)
        // $this->dispatch('showDeleteModal');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Menggunakan Auth::id() lebih stabil di beberapa versi Laravel
        if ($user->id == Auth::id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            $this->dispatch('closeDeleteModal');
            return;
        }

        $user->delete();
        session()->flash('success', 'Data User berhasil dihapus!');
        $this->dispatch('closeDeleteModal');
    }

    public function exportExcel()
    {
        // Query dasar untuk User
        $query = User::query()->when($this->search, function ($queryBuilder) {
            $queryBuilder->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('role', 'like', '%' . $this->search . '%')
                // Mencari berdasarkan nama Fasilitas Kesehatan di tabel faskes
                ->orWhereHas('faskes', function ($q) {
                    $q->where('nama_faskes', 'like', '%' . $this->search . '%');
                });
        });

        // Logika Pengurutan (Sorting)
        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Eager loading relasi 'faskes' agar tidak berat saat export
        $usersToExport = $query->with(['faskes'])->get();

        // Penamaan File
        $fileName = 'Daftar-User_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';

        // Memanggil class UsersExport yang sudah kita buat sebelumnya
        return Excel::download(new UsersExport($usersToExport), $fileName);
    }

    public function exportPdf()
    {
        // Query dasar untuk User dengan pencarian dinamis
        $query = User::query()->when($this->search, function ($queryBuilder) {
            $queryBuilder->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('role', 'like', '%' . $this->search . '%')
                ->orWhereHas('faskes', function ($q) {
                    $q->where('nama_faskes', 'like', '%' . $this->search . '%');
                });
        });

        // Logika Pengurutan
        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Eager loading relasi faskes agar tidak membebani database
        $usersToExport = $query->with(['faskes'])->get();

        // Penamaan file PDF
        $fileName = 'Daftar-User_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';

        // Memuat view khusus PDF (Pastikan file view ini sudah dibuat)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.users', ['users' => $usersToExport]);

        // Mengatur orientasi kertas ke Landscape agar kolom Faskes yang panjang tidak terpotong
        $pdf->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Menggunakan class UsersImport
            Excel::import(new UsersImport, $this->file);

            session()->flash('success', 'Data user berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errorMessage = 'Gagal mengimpor data. Periksa kembali format file. Detail: ';
            foreach ($failures as $failure) {
                // Memberikan informasi baris mana yang salah (misal: email duplikat atau kolom kosong)
                $errorMessage .= " Baris " . $failure->row() . ": " . implode(", ", $failure->errors()) . ". ";
            }
            session()->flash('error', $errorMessage);
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->reset('file');
        $this->dispatch('closeImportModal'); // Menutup modal sesuai instruksi Anda sebelumnya
    }

    public function importExcel()
    {
        // Reset file dan pesan error sebelum membuka modal
        $this->reset(['file']);
        $this->resetValidation('file');

        // Menampilkan modal import
        $this->dispatch('show-import-modal');
    }
}
