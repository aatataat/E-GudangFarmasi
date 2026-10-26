<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Permintaan; // Import model Permintaan

class SidebarComposer
{
    /**
     * Bind data ke view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Hitung permintaan dengan status 'DIAJUKAN'
        $count = Permintaan::where('status', 'DIAJUKAN')->count();

        // Pass hitungan ke view
        $view->with('countPermintaanBaru', $count);
    }
}