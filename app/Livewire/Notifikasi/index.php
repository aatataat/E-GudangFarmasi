<?php

namespace App\Livewire\Notifikasi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notifikasi;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $paginate = 10;
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    // Variabel yang digunakan untuk modal create/edit
    public $notifikasi_id;
    public $pesan, $tipe, $status;

    // Menambahkan method untuk reset pagination saat ada perubahan search
    public function updatedSearch()
    {
        $this->resetPage();
    }

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

    // -------------------- Render --------------------
    public function render()
    {
        $query = Notifikasi::when($this->search, function ($queryBuilder) {
            $queryBuilder->where('pesan', 'like', '%' . $this->search . '%')
                ->orWhere('tipe', 'like', '%' . $this->search . '%')
                ->orWhere('status', 'like', '%' . $this->search . '%')
                ->orWhere('tanggal', 'like', '%' . $this->search . '%');
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->latest();
        }

        return view('livewire.notifikasi.index', [
            'tittle'     => 'Data Notifikasi',
            'notifikasi' => $query->paginate($this->paginate),
        ]);
    }

    // -------------------- Reset Validation --------------------
    public function updated($property)
    {
        $this->resetValidation($property);
    }

    // -------------------- Create --------------------
    public function create()
    {
        $this->reset(['pesan', 'tipe', 'status', 'notifikasi_id']);
    }

    public function store()
    {
        $this->validate([
            'pesan'  => 'required|string|max:255',
            'tipe'   => 'required|string|max:50',
            'status' => 'required|string|max:20',
        ]);

        Notifikasi::create([
            'pesan'   => $this->pesan,
            'tipe'    => $this->tipe,
            'status'  => $this->status,
            'tanggal' => now(),
        ]);

        $this->dispatch('closeCreateModal');
    }

    // -------------------- Edit --------------------
    public function edit($id)
    {
        $notif = Notifikasi::findOrFail($id);

        $this->notifikasi_id = $notif->id;
        $this->pesan  = $notif->pesan;
        $this->tipe   = $notif->tipe;
        $this->status = $notif->status;
    }

    public function update()
    {
        $this->validate([
            'pesan'  => 'required|string|max:255',
            'tipe'   => 'required|string|max:50',
            'status' => 'required|string|max:20',
        ]);

        $notif = Notifikasi::findOrFail($this->notifikasi_id);
        $notif->update([
            'pesan'  => $this->pesan,
            'tipe'   => $this->tipe,
            'status' => $this->status,
        ]);

        $this->dispatch('closeEditModal');
    }

    // -------------------- Delete --------------------
    public function confirm($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $this->pesan = $notif->pesan;
        $this->notifikasi_id = $notif->id;
    }

    public function destroy()
    {
        $notif = Notifikasi::findOrFail($this->notifikasi_id);
        $notif->delete();

        $this->dispatch('closeDeleteModal');
    }

    // -------------------- Mark as Read --------------------
    public function markAsRead($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->update(['status' => 'Dibaca']);

        $this->dispatch('success', 'Notifikasi berhasil ditandai sebagai sudah dibaca! ✅');
    }
}