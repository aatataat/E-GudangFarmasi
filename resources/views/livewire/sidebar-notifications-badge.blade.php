{{-- Wire poll untuk update setiap 60 detik (sama seperti di navbar) --}}
<span wire:poll.keep-alive.60s>
    @if ($unreadCount > 0)
        {{-- Badge yang ditampilkan di sidebar. Menggunakan class 'right badge' untuk AdminLTE --}}
        <span class="right badge badge-warning badge-sm" style="position: absolute; top: 0; right: 0; transform: translate(-20%, 50%);">
            {{ $unreadCount }}
        </span>
    @endif
</span>