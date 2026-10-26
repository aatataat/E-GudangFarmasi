<?php

namespace App\Livewire\Pimpinan\Lap10Minta;

use Livewire\Component;
use App\Models\PermintaanBarang;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    // Catatan: WithPagination dihapus karena data dibatasi permanen hanya 10 item teratas (Top 10)

    // Properti untuk filter datatable
    public $search = '';
    public $sort = 'total_diminta'; // Default diurutkan berdasarkan jumlah terbanyak
    public $direction = 'desc';

    // Jika Anda ingin memfilter berdasarkan id permintaan tertentu (opsional)
    public $permintaanId;

    public function mount($permintaanId = null)
    {
        $this->permintaanId = $permintaanId;
    }

    public function sortBy($field)
    {
        if ($this->sort === $field) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $field;
            $this->direction = 'asc';
        }
        // Tidak perlu $this->resetPage() karena tidak menggunakan paginasi lagi
    }

    public function render()
    {
        // Jalankan query dengan join ke tabel stok, obat, kategori, dan satuan untuk pengelompokan data
        $query = PermintaanBarang::query()
            ->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
            ->join('obat', 'stok.obat_id', '=', 'obat.id')
            ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
            ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
            ->select(
                'obat.id as id_obat',
                'obat.nama_obat',
                'kategori.nama_kategori',
                'satuan.nama_satuan',
                DB::raw('SUM(permintaan_barang.jumlah_diminta) as total_diminta')
            )
            ->when($this->permintaanId, function ($q) {
                // Filter berdasarkan surat permintaan tertentu jika id-nya dikirim
                $q->where('permintaan_barang.permintaan_id', $this->permintaanId);
            })
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where(function ($subQuery) {
                    $subQuery->where('obat.nama_obat', 'like', '%' . $this->search . '%')
                        ->orWhere('kategori.nama_kategori', 'like', '%' . $this->search . '%')
                        ->orWhere('satuan.nama_satuan', 'like', '%' . $this->search . '%');
                });
            })
            // Kelompokkan berdasarkan data obat, kategori, dan satuan agar jika namanya sama langsung bersatu
            ->groupBy('obat.id', 'obat.nama_obat', 'kategori.nama_kategori', 'satuan.nama_satuan');

        // Logika Pengurutan (Sorting)
        if ($this->sort === 'nama_obat') {
            $query->orderBy('obat.nama_obat', $this->direction ?? 'asc');
        } elseif ($this->sort === 'nama_kategori') {
            $query->orderBy('kategori.nama_kategori', $this->direction ?? 'asc');
        } elseif ($this->sort === 'nama_satuan') {
            $query->orderBy('satuan.nama_satuan', $this->direction ?? 'asc');
        } else {
            // Default: Mengurutkan dari yang paling banyak diminta
            $query->orderBy('total_diminta', $this->direction ?? 'desc');
        }

        // Kunci hasil query hanya untuk mengambil 10 baris data teratas
        $items = $query->take(10)->get();

        return view('livewire.pimpinan.Lap10Minta.index', [
            'tittle' => '10 Barang Paling Banyak Diminta',
            'items' => $items
        ]);
    }

    public function exportPdf()
    {
        // 1. Ambil data top 10 yang sama persis dengan yang ada di grafik
        $items = PermintaanBarang::query()
            ->join('stok', 'permintaan_barang.id_stok', '=', 'stok.id')
            ->join('obat', 'stok.obat_id', '=', 'obat.id')
            ->join('kategori', 'obat.id_kategori', '=', 'kategori.id')
            ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
            ->select(
                'obat.nama_obat',
                'kategori.nama_kategori',
                'satuan.nama_satuan',
                DB::raw('SUM(permintaan_barang.jumlah_diminta) as total_diminta')
            )
            ->groupBy('obat.id', 'obat.nama_obat', 'kategori.nama_kategori', 'satuan.nama_satuan')
            ->orderBy('total_diminta', 'desc')
            ->take(10)
            ->get();

        // 2. Siapkan data untuk dikirim ke template PDF
        $data = [
            'title' => 'Laporan 10 Barang Paling Banyak Diminta',
            'date' => date('d-m-Y H:i'),
            'items' => $items
        ];

        // 3. Load view cetak dan set ukuran kertas (A4 Portrait)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.lap10Minta', $data)
            ->setPaper('a4', 'portrait');

        // 4. Download file PDF secara otomatis menggunakan return download stream
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Top_10_Permintaan_' . date('Ymd_His') . '.pdf');
    }
}
