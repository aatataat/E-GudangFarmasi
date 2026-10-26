<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notifikasi;
use Carbon\Carbon;

class NavbarNotifications extends Component
{
    public $notifications;

    // Listener untuk event 'refreshNotifications'.
    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.navbar-notifications');
    }

    /**
     * Memuat notifikasi yang belum Dibaca dari database.
     */
    public function loadNotifications()
    {
        // Sesuaikan query jika notifikasi dikaitkan dengan pengguna (users)
        // Contoh: $this->notifications = auth()->user()->notifications()
        //         ->where('status', 'Belum Dibaca')->orderBy('tanggal', 'desc')->get();
        // Jika tidak, kode yang sudah ada sudah benar.
        $this->notifications = Notifikasi::where('status', 'Belum Dibaca')
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function markAsRead($id)
    {
        $notification = Notifikasi::find($id);
        if ($notification) {
            $notification->update(['status' => 'Dibaca']);
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        Notifikasi::where('status', 'Belum Dibaca')->update(['status' => 'Dibaca']);
        $this->loadNotifications();
    }
}