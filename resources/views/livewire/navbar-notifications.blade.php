{{-- Ini adalah view untuk komponen Livewire NavbarNotifications --}}
<li class="nav-item dropdown mr-4" wire:poll.keep-alive.60s>
    <a class="nav-link text-white" data-toggle="dropdown" href="#" style="position: relative;">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M21 19v1H3v-1l2-2v-6c0-3.1 2.03-5.83 5-6.71V4a2 2 0 0 1 2-2a2 2 0 0 1 2 2v.29c2.97.88 5 3.61 5 6.71v6zm-7 2a2 2 0 0 1-2 2a2 2 0 0 1-2-2" />
        </svg>
        @if ($notifications->where('status', 'Belum Dibaca')->count() > 0)
            <span class="badge badge-warning navbar-badge"
                style="position: absolute; top: 0; right: 0; transform: translate(50%, 10%);">
                {{ $notifications->where('status', 'Belum Dibaca')->count() }}
            </span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ $notifications->where('status', 'Belum Dibaca')->count() }}
            Notifikasi Belum Dibaca</span>
        <div class="dropdown-divider"></div>
        @if ($notifications->where('status', 'Belum Dibaca')->count() > 0)
            @foreach ($notifications->where('status', 'Belum Dibaca')->take(5) as $notification)
                <a href="#" wire:click="markAsRead({{ $notification->id }})" class="dropdown-item">
                    <i class="fas fa-info-circle mr-2"></i> {{ Str::limit($notification->pesan, 120) }}
                    <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                </a>
                <div class="dropdown-divider"></div>
            @endforeach
            <a href="#" wire:click="markAllAsRead" class="dropdown-item dropdown-footer">Tandai Semua Sudah
                Dibaca</a>
        @else
            <a href="#" class="dropdown-item text-center">
                Tidak ada notifikasi baru.
            </a>
        @endif
        <hr>
        <a wire:navigate href="{{ route('notifikasi.index') }}" class="dropdown-item dropdown-footer text-center">Lihat Semua Notifikasi</a>
    </div>

</li>
