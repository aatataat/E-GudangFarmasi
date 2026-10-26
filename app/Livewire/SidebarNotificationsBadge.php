<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notifikasi;

class SidebarNotificationsBadge extends Component
{
    public $unreadCount = 0;

    // Menggunakan polling untuk memperbarui jumlah notifikasi yang belum dibaca
    // Interval 60s sama seperti di navbar
    public $listeners = ['refreshNotifications' => 'loadUnreadCount']; 

    public function mount()
    {
        $this->loadUnreadCount();
    }

    public function render()
    {
        return view('livewire.sidebar-notifications-badge');
    }

    /**
     * Memuat jumlah notifikasi yang belum Dibaca.
     */
    public function loadUnreadCount()
    {
        // Logika yang sama dengan NavbarNotifications, hanya mengambil count()
        $this->unreadCount = Notifikasi::where('status', 'Belum Dibaca')->count();
    }
}