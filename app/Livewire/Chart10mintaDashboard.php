<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PermintaanBarang;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class Chart10mintaDashboard extends Component
{
    public $chartLabels = [];
    public $chartData = [];
    public $chartImage; // Properti baru untuk menampung gambar dari JS
    public $filterTahun;

    public function mount()
    {
        $this->filterTahun = date('Y');
    }

    public function updatedFilterTahun()
    {
        $this->dispatch('updateTopRequestedChart', labels: $this->chartLabels, values: $this->chartData);
    }

    public function exportPdf()
{
    if (!$this->chartImage) {
        return;
    }

    $query = PermintaanBarang::query()
        ->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
        ->join('obat', 'stok.obat_id', '=', 'obat.id')
        ->select('obat.nama_obat', DB::raw('SUM(permintaan_barang.jumlah_diminta) as total_diminta'));

    if ($this->filterTahun) {
        $query->whereYear('permintaan_barang.created_at', $this->filterTahun);
    }

    $items = $query->groupBy('obat.id', 'obat.nama_obat')
        ->orderBy('total_diminta', 'desc')
        ->take(10)
        ->get();

    $data = [
        'title' => 'LAPORAN 10 BARANG PALING BANYAK DIMINTA ' . ($this->filterTahun ? 'TAHUN ' . $this->filterTahun : ''),
        'chartImage' => $this->chartImage,
        'items' => $items
    ];

    // UBAH PORTRAIT MENJADI LANDSCAPE DI SINI
    $pdf = Pdf::loadView('pdfs.lapchart10minta', $data)
              ->setPaper('a4', 'landscape'); 

    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->output();
    }, 'Laporan_Grafik_Permintaan_Landscape_' . date('Ymd_His') . '.pdf');
}

    public function render()
    {
        $query = PermintaanBarang::query()
            ->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
            ->join('obat', 'stok.obat_id', '=', 'obat.id')
            ->select('obat.nama_obat', DB::raw('SUM(permintaan_barang.jumlah_diminta) as total_diminta'));

        if ($this->filterTahun) {
            $query->whereYear('permintaan_barang.created_at', $this->filterTahun);
        }

        $items = $query->groupBy('obat.id', 'obat.nama_obat')
            ->orderBy('total_diminta', 'desc')
            ->take(10)
            ->get();

        $this->chartLabels = $items->pluck('nama_obat')->toArray();
        $this->chartData = $items->pluck('total_diminta')->toArray();

        $years = PermintaanBarang::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        if (empty($years)) {
            $years = [date('Y')];
        }

        return view('livewire.chart10mintaDashboard', [
            'years' => $years
        ]);
    }
}