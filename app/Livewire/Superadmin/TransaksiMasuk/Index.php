<?php

namespace App\Livewire\Superadmin\TransaksiMasuk;

use Livewire\Component;
use App\Exports\TransaksiMasuksExport;
use App\Models\TransaksiMasuk;
use App\Models\Stok;
use App\Models\Obat;
use App\Models\SumberAnggaran;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransaksiMasuksImport;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sort = 'created_at';
    public $direction = 'desc';

    public $transaksi_masuk_id;

    // Properti Master (Faktur Header) dan Single Detail
    public $no, $tanggal_masuk, $id_sumber_anggaran, $id_supplier;
    // Properti Detail untuk mode Edit/Single Record
    public $batch_number, $tanggal_kadaluarsa, $jumlah, $harga, $total, $obat_id;

    // Properti Detail (Array of Items) - Untuk mode Create Multi-Item
    public $items = [];
    public $grandTotal = 0;

    public $file;
    public $message = '';

    public $filterTanggal = null; // Properti untuk filter tanggal
    // ----------------------------------Menampilkan Data----------------------------------------------- //
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Mencari berdasarkan nama obat, supplier, sumber anggaran, atau TANGGAL KADALUARSA
        // Query pencarian untuk Transaksi Masuk sesuai struktur DB e-gudang
        $query = TransaksiMasuk::query();

        // 1. Filter Berdasarkan Tanggal/Bulan/Tahun (Jika diisi)
        $query->when($this->filterTanggal, function ($queryBuilder) {
            if (str_contains($this->filterTanggal, '-')) {
                // Jika formatnya Y-m-d (contoh: 2026-01-20)
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->filterTanggal)) {
                    $queryBuilder->whereDate('tanggal_masuk', $this->filterTanggal);
                } else {
                    // Jika formatnya Y-m (contoh: 2026-01)
                    $queryBuilder->whereMonth('tanggal_masuk', date('m', strtotime($this->filterTanggal)))
                        ->whereYear('tanggal_masuk', date('Y', strtotime($this->filterTanggal)));
                }
            } else {
                // Jika hanya angka 4 digit (contoh: 2026)
                $queryBuilder->whereYear('tanggal_masuk', $this->filterTanggal);
            }
        });

        // 2. Filter Berdasarkan Input Pencarian (Kerjasama dengan Filter Tanggal)
        $query->when($this->search, function ($queryBuilder) {
            // PENTING: Menggunakan closure function ($q) agar semua OR di bawah ini 
            // dianggap sebagai satu kesatuan filter yang harus memenuhi filter tanggal di atas.
            $queryBuilder->where(function ($q) {
                $q->where('batch_number', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal_masuk', 'like', '%' . $this->search . '%')
                    ->orWhere('no', 'like', '%' . $this->search . '%')
                    ->orWhereHas('obat', function ($q2) {
                        // Mencari nama obat DAN satuan
                        $q2->where('nama_obat', 'like', '%' . $this->search . '%')
                            ->orWhereHas('satuan', function ($q3) {
                                $q3->where('nama_satuan', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->orWhereHas('supplier', function ($q2) {
                        $q2->where('nama_supplier', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('sumberAnggaran', function ($q2) {
                        $q2->where('nama_anggaran', 'like', '%' . $this->search . '%');
                    });
            });
        });

        // 3. Logika Sorting (Tetap utuh seperti kode Anda)
        if ($this->sort && $this->direction) {
            if ($this->sort === 'sumber_anggaran') {
                $query->join('sumber_anggaran', 'transaksi_masuk.id_sumber_anggaran', '=', 'sumber_anggaran.id')
                    ->orderBy('sumber_anggaran.nama_anggaran', $this->direction)
                    ->select('transaksi_masuk.*'); // Penting agar kolom tidak bentrok setelah join
            } elseif ($this->sort === 'obat') {
                $query->join('obat', 'transaksi_masuk.obat_id', '=', 'obat.id')
                    ->orderBy('obat.nama_obat', $this->direction)
                    ->select('transaksi_masuk.*');
            } elseif ($this->sort === 'supplier') {
                $query->join('supplier', 'transaksi_masuk.id_supplier', '=', 'supplier.id')
                    ->orderBy('supplier.nama_supplier', $this->direction)
                    ->select('transaksi_masuk.*');
            } elseif ($this->sort === 'satuan') { // Penambahan sorting satuan yang Anda minta sebelumnya
                $query->join('obat', 'transaksi_masuk.obat_id', '=', 'obat.id')
                    ->join('satuan', 'obat.id_satuan', '=', 'satuan.id')
                    ->orderBy('satuan.nama_satuan', $this->direction)
                    ->select('transaksi_masuk.*');
            } else {
                $query->orderBy($this->sort, $this->direction);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $transaksi_masuk = $query->paginate($this->paginate);

        $data = [
            'tittle' => 'Data Transaksi Masuk',
            'transaksi_masuk' => $transaksi_masuk,
            'obats' => Obat::orderBy('nama_obat', 'asc')->get(),
            'sumberAnggarans' => SumberAnggaran::orderBy('nama_anggaran', 'asc')->get(),
            'suppliers' => Supplier::orderBy('nama_supplier', 'asc')->get(),
        ];

        return view('livewire.superadmin.transaksi_masuk.index', $data);
    }

    // ----------------------------------Mereset Validation & Hitungan----------------------------------------------- //
    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);

        // Logika untuk menghitung total item dan grand total (Mode Create Multi-Item)
        if (preg_match('/^items\.(\d+)\.(jumlah|harga)$/', $propertyName, $matches)) {
            $index = $matches[1];
            $this->computeItemTotal($index);
            $this->computeGrandTotal();
        } elseif (in_array($propertyName, ['jumlah', 'harga']) && $this->transaksi_masuk_id) {
            // ⭐ LOGIKA BARU: Hitung ulang total untuk Mode Edit Single Item
            $this->computeSingleTotal();
        } elseif (preg_match('/^items\.(\d+)\.(obat_id|batch_number|tanggal_kadaluarsa)$/', $propertyName)) {
            // Jika field detail lain diubah, tidak perlu hitung total
        } else {
            // Logika untuk properti Master
        }
    }

    // Metode untuk menghitung total per item (Mode Create Multi-Item)
    private function computeItemTotal($index)
    {
        $jumlah = (float) $this->items[$index]['jumlah'];
        $harga = (float) $this->items[$index]['harga'];

        if (is_numeric($jumlah) && is_numeric($harga)) {
            $this->items[$index]['total'] = $jumlah * $harga;
        } else {
            $this->items[$index]['total'] = 0;
        }
    }

    // Metode untuk menghitung total saat Edit (Single Record)
    private function computeSingleTotal()
    {
        $jumlah = (float) $this->jumlah;
        $harga = (float) $this->harga;

        if (is_numeric($jumlah) && is_numeric($harga)) {
            $this->total = $jumlah * $harga;
        } else {
            $this->total = 0;
        }
    }

    // Metode untuk menghitung total keseluruhan faktur
    private function computeGrandTotal()
    {
        $this->grandTotal = array_sum(array_column($this->items, 'total'));
    }

    // ----------------------------------Manajemen Item Detail (Array)----------------------------------------------- //

    // Menambahkan baris detail baru
    public function addItemDetail()
    {
        $this->items[] = [
            'obat_id' => null,
            'batch_number' => '',
            'tanggal_kadaluarsa' => '',
            'jumlah' => 0,
            'harga' => 0,
            'total' => 0,
        ];
    }

    // Menghapus baris detail
    public function removeItemDetail($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        } else {
            session()->flash('warning', 'Minimal harus ada satu item obat yang dimasukkan.');
        }
        $this->computeGrandTotal();
    }

    // ----------------------------------CRUD Data----------------------------------------------- //
    // Tambah
    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'no',
            'tanggal_masuk',
            'id_sumber_anggaran',
            'id_supplier',
            'transaksi_masuk_id',
            'jumlah',
            'harga',
            'total',
            'obat_id'
        ]);

        $this->items = [
            [
                'obat_id' => null,
                'batch_number' => '',
                'tanggal_kadaluarsa' => '',
                'jumlah' => 0,
                'harga' => 0,
                'total' => 0,
            ]
        ];
        $this->grandTotal = 0;
    }

    public function store()
    {
        // Langkah 1: Aturan Validasi Master dan Detail
        $rules = [
            'no' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'id_sumber_anggaran' => 'required|exists:sumber_anggaran,id',
            'id_supplier' => 'required|exists:supplier,id',
            'items' => 'required|array|min:1',
            'items.*.obat_id' => 'required|exists:obat,id',
            'items.*.batch_number' => 'required|string|max:255',
            'items.*.tanggal_kadaluarsa' => 'required|date|after:tanggal_masuk',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|integer|min:0',
            'items.*.total' => 'required|integer|min:0',
        ];

        $messages = [
            'no.required' => 'Nomor Faktur tidak boleh kosong',
            'tanggal_masuk.required' => 'Tanggal Masuk tidak boleh kosong',
            'id_sumber_anggaran.required' => 'Sumber Anggaran tidak boleh kosong',
            'id_supplier.required' => 'Supplier tidak boleh kosong',

            'items.min' => 'Minimal harus ada satu item obat yang dimasukkan.',
            'items.*.obat_id.required' => 'Nama Obat pada baris :attribute tidak boleh kosong',
            'items.*.batch_number.required' => 'Batch Number pada baris :attribute tidak boleh kosong',
            'items.*.tanggal_kadaluarsa.required' => 'Tgl Kadaluarsa pada baris :attribute tidak boleh kosong',
            'items.*.tanggal_kadaluarsa.after' => 'Tgl Kadaluarsa pada baris :attribute harus setelah Tgl Masuk',
            'items.*.jumlah.min' => 'Jumlah pada baris :attribute harus minimal 1',
            'items.*.jumlah.required' => 'Jumlah pada baris :attribute tidak boleh kosong',
            'items.*.harga.required' => 'Harga pada baris :attribute tidak boleh kosong',
            'items.*.total.required' => 'Total pada baris :attribute tidak boleh kosong',
        ];

        $this->validate($rules, $messages);


        DB::beginTransaction();

        try {
            // Looping dan simpan setiap item detail sebagai TransaksiMasuk
            foreach ($this->items as $index => $item) {

                // --- Validasi Unik Tanggal Kadaluarsa per Item ---
                $existingStok = Stok::where('obat_id', $item['obat_id'])
                    ->where('batch_number', $item['batch_number'])
                    ->where('harga', $item['harga'])
                    ->where('id_sumber_anggaran', $this->id_sumber_anggaran)
                    ->where('id_supplier', $this->id_supplier)
                    ->first();

                if ($existingStok && $existingStok->tanggal_kadaluarsa->format('Y-m-d') !== $item['tanggal_kadaluarsa']) {
                    DB::rollBack();
                    throw ValidationException::withMessages([
                        'items.' . $index . '.tanggal_kadaluarsa' => 'Tanggal Kadaluarsa tidak cocok dengan stok Batch ' . $item['batch_number'] . ' yang sudah ada.'
                    ]);
                }
                // --- Akhir Validasi Unik Tanggal Kadaluarsa ---


                // Langkah 1: Simpan data ke tabel TransaksiMasuk
                $transaksi_masuk = new TransaksiMasuk;
                $transaksi_masuk->no = $this->no;
                $transaksi_masuk->obat_id = $item['obat_id'];
                $transaksi_masuk->tanggal_masuk = $this->tanggal_masuk;
                $transaksi_masuk->batch_number = $item['batch_number'];
                $transaksi_masuk->tanggal_kadaluarsa = $item['tanggal_kadaluarsa'];
                $transaksi_masuk->jumlah = $item['jumlah'];
                $transaksi_masuk->harga = $item['harga'];
                $transaksi_masuk->total = $item['total'];
                $transaksi_masuk->id_sumber_anggaran = $this->id_sumber_anggaran;
                $transaksi_masuk->id_supplier = $this->id_supplier;
                $transaksi_masuk->save();

                // Langkah 2: Sinkronisasi data ke tabel Stok
                $stok = Stok::firstOrNew([
                    'obat_id' => $item['obat_id'],
                    'batch_number' => $item['batch_number'],
                    'harga' => $item['harga'],
                    'id_sumber_anggaran' => $this->id_sumber_anggaran,
                    'id_supplier' => $this->id_supplier,
                ]);

                // Jika baru dibuat, inisialisasi kolom
                if (!$stok->exists) {
                    $stok->jumlah_masuk = 0;
                    $stok->total_masuk = 0;
                    $stok->jumlah = 0;
                    $stok->total = 0;
                    $stok->jumlah_keluar = 0; // Tambahkan inisialisasi kolom keluar
                    $stok->total_keluar = 0; // Tambahkan inisialisasi kolom keluar
                }

                // Update Stok
                $stok->tanggal_kadaluarsa = $item['tanggal_kadaluarsa'];
                $stok->jumlah_masuk += $item['jumlah'];
                $stok->harga_masuk = $item['harga'];
                $stok->total_masuk += $item['total'];
                $stok->jumlah += $item['jumlah'];
                $stok->total += $item['total'];
                $stok->save();
            }

            DB::commit();
            $this->dispatch('closeCreateModal');
            session()->flash('message', 'Transaksi masuk berhasil ditambahkan.');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menambahkan transaksi masuk. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ----------------------------------Edit----------------------------------------------- //
    public function edit($id)
    {
        $this->resetValidation();
        $transaksi_masuk = TransaksiMasuk::findOrFail($id);

        // Reset array items untuk memastikan hanya mode Edit yang aktif
        $this->reset('items', 'grandTotal');

        $this->no = $transaksi_masuk->no;
        $this->obat_id = $transaksi_masuk->obat_id;
        $this->tanggal_masuk = \Carbon\Carbon::parse($transaksi_masuk->tanggal_masuk)->format('Y-m-d');
        $this->batch_number = $transaksi_masuk->batch_number;
        $this->tanggal_kadaluarsa = \Carbon\Carbon::parse($transaksi_masuk->tanggal_kadaluarsa)->format('Y-m-d');
        $this->jumlah = $transaksi_masuk->jumlah;
        $this->harga = $transaksi_masuk->harga;
        $this->total = $transaksi_masuk->total;
        $this->id_sumber_anggaran = $transaksi_masuk->id_sumber_anggaran;
        $this->id_supplier = $transaksi_masuk->id_supplier;
        $this->transaksi_masuk_id = $transaksi_masuk->id;
    }

    public function update($id)
    {
        $transaksi_masuk = TransaksiMasuk::findOrFail($id);
        $old_jumlah = $transaksi_masuk->jumlah;
        $old_harga = $transaksi_masuk->harga;
        $old_batch_number = $transaksi_masuk->batch_number;
        $old_obat_id = $transaksi_masuk->obat_id;
        $old_id_sumber_anggaran = $transaksi_masuk->id_sumber_anggaran;
        $old_id_supplier = $transaksi_masuk->id_supplier;

        $rules = [
            'no' => 'required|string|max:255',
            'obat_id' => 'required|exists:obat,id',
            'tanggal_masuk' => 'required|date',
            'batch_number' => 'required|string|max:255',
            'tanggal_kadaluarsa' => 'required|date|after:tanggal_masuk',
            'jumlah' => 'required|integer',
            'harga' => 'required|integer',
            'total' => 'required|integer',
            'id_sumber_anggaran' => 'required|exists:sumber_anggaran,id',
            'id_supplier' => 'required|exists:supplier,id',
        ];

        $messages = [
            // ... (Pesan Validasi tetap sama)
            'no.required' => 'Nomor tidak boleh kosong',
            'obat_id.required' => 'Obat tidak boleh kosong',
            'obat_id.exists' => 'Obat tidak ditemukan',
            'tanggal_masuk.required' => 'Tanggal Masuk tidak boleh kosong',
            'tanggal_masuk.date' => 'Tanggal Masuk harus berupa tanggal',
            'batch_number.required' => 'Nomor Batch tidak boleh kosong',
            'tanggal_kadaluarsa.required' => 'Tanggal Kadaluarsa tidak boleh kosong',
            'tanggal_kadaluarsa.date' => 'Tanggal Kadaluarsa harus berupa tanggal',
            'tanggal_kadaluarsa.after' => 'Tanggal Kadaluarsa harus setelah Tanggal Masuk',
            'jumlah.required' => 'Jumlah tidak boleh kosong',
            'jumlah.integer' => 'Jumlah harus berupa angka',
            'harga.required' => 'Harga tidak boleh kosong',
            'harga.integer' => 'Harga harus berupa angka',
            'total.required' => 'Total tidak boleh kosong',
            'total.integer' => 'Total harus berupa angka',
            'id_sumber_anggaran.required' => 'Sumber Anggaran tidak boleh kosong',
            'id_sumber_anggaran.exists' => 'Sumber Anggaran tidak ditemukan',
            'id_supplier.required' => 'Supplier tidak boleh kosong',
            'id_supplier.exists' => 'Supplier tidak ditemukan',
        ];

        $this->validate($rules, $messages);

        // --- VALIDASI TGL KADALUARSA ---
        $existingStok = Stok::where('obat_id', $this->obat_id)
            ->where('batch_number', $this->batch_number)
            ->where('harga', $this->harga)
            ->where('id_sumber_anggaran', $this->id_sumber_anggaran)
            ->where('id_supplier', $this->id_supplier)
            ->first();

        if ($existingStok && $existingStok->tanggal_kadaluarsa->format('Y-m-d') !== $this->tanggal_kadaluarsa) {
            throw ValidationException::withMessages([
                'tanggal_kadaluarsa' => 'Tanggal Kadaluarsa tidak cocok dengan data stok yang sudah ada. Mohon periksa kembali.'
            ]);
        }
        // --- AKHIR VALIDASI TGL KADALUARSA ---

        DB::beginTransaction();

        try {
            // Langkah 1: Perbarui data TransaksiMasuk
            $transaksi_masuk->no = $this->no;
            $transaksi_masuk->obat_id = $this->obat_id;
            $transaksi_masuk->tanggal_masuk = $this->tanggal_masuk;
            $transaksi_masuk->batch_number = $this->batch_number;
            $transaksi_masuk->tanggal_kadaluarsa = $this->tanggal_kadaluarsa;
            $transaksi_masuk->jumlah = $this->jumlah;
            $transaksi_masuk->harga = $this->harga;
            $transaksi_masuk->total = $this->total;
            $transaksi_masuk->id_sumber_anggaran = $this->id_sumber_anggaran;
            $transaksi_masuk->id_supplier = $this->id_supplier;
            $transaksi_masuk->save();

            // Langkah 2: Sinkronisasi ke tabel Stok

            // 2a. Kurangi stok lama (Hapus jejak transaksi lama)
            $stok_lama = Stok::where('obat_id', $old_obat_id)
                ->where('batch_number', $old_batch_number)
                ->where('harga', $old_harga)
                ->where('id_sumber_anggaran', $old_id_sumber_anggaran)
                ->where('id_supplier', $old_id_supplier)
                ->first();

            if ($stok_lama) {
                $stok_lama->jumlah_masuk -= $old_jumlah;
                $stok_lama->total_masuk -= ($old_jumlah * $old_harga);
                $stok_lama->jumlah -= $old_jumlah;
                $stok_lama->total -= ($old_jumlah * $old_harga);

                // ⭐ PERBAIKAN SINKRONISASI: Hapus record stok jika jumlahnya menjadi 0 dan tidak ada stok keluar.
                if ($stok_lama->jumlah <= 0 && $stok_lama->jumlah_keluar <= 0) {
                    $stok_lama->delete();
                } else {
                    $stok_lama->save();
                }
            }

            // 2b. Tambahkan atau perbarui stok baru (Tambahkan nilai transaksi baru)
            $stok_baru = Stok::firstOrNew([
                'obat_id' => $this->obat_id,
                'batch_number' => $this->batch_number,
                'harga' => $this->harga,
                'id_sumber_anggaran' => $this->id_sumber_anggaran,
                'id_supplier' => $this->id_supplier
            ]);

            // Jika record baru, inisialisasi properti yang mungkin belum ada
            if (!$stok_baru->exists) {
                $stok_baru->jumlah_keluar = 0;
                $stok_baru->total_keluar = 0;
                $stok_baru->jumlah_masuk = 0;
                $stok_baru->total_masuk = 0;
                $stok_baru->jumlah = 0;
                $stok_baru->total = 0;
            }

            $stok_baru->tanggal_kadaluarsa = $this->tanggal_kadaluarsa;
            $stok_baru->jumlah_masuk += $this->jumlah;
            $stok_baru->harga_masuk = $this->harga;
            $stok_baru->total_masuk += $this->total;
            $stok_baru->jumlah += $this->jumlah;
            $stok_baru->total += $this->total;
            $stok_baru->save();

            DB::commit();
            $this->dispatch('closeEditModal');
            session()->flash('message', 'Transaksi masuk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal memperbarui transaksi masuk. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ----------------------------------Delete (TIDAK BERUBAH)----------------------------------------------- //
    public function confirm($id)
    {
        $transaksi_masuk = TransaksiMasuk::findOrFail($id);
        $this->transaksi_masuk_id = $transaksi_masuk->id;
        $this->no = $transaksi_masuk->no;
        $this->tanggal_masuk = $transaksi_masuk->tanggal_masuk;
        $this->batch_number = $transaksi_masuk->batch_number;
        $this->tanggal_kadaluarsa = $transaksi_masuk->tanggal_kadaluarsa;
        $this->jumlah = $transaksi_masuk->jumlah;
        $this->harga = $transaksi_masuk->harga;
        $this->total = $transaksi_masuk->total;
        $this->obat_id = $transaksi_masuk->obat;
        $this->id_sumber_anggaran = $transaksi_masuk->sumberAnggaran;
        $this->id_supplier = $transaksi_masuk->supplier;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaksi_masuk = TransaksiMasuk::findOrFail($id);
            // Cari stok
            $stok = Stok::where('obat_id', $transaksi_masuk->obat_id)
                ->where('batch_number', $transaksi_masuk->batch_number)
                ->where('harga', $transaksi_masuk->harga)
                ->where('id_sumber_anggaran', $transaksi_masuk->id_sumber_anggaran)
                ->where('id_supplier', $transaksi_masuk->id_supplier)
                ->first();
            if ($stok) {
                $stok->jumlah_masuk -= $transaksi_masuk->jumlah;
                $stok->harga_masuk = $stok->jumlah_masuk > 0 ? $stok->harga_masuk : 0;
                $stok->total_masuk -= $transaksi_masuk->total;
                $stok->jumlah -= $transaksi_masuk->jumlah;
                $stok->total -= $transaksi_masuk->total;

                // ⭐ SINKRONISASI DELETE: Hapus record stok jika jumlahnya menjadi 0 dan tidak ada stok keluar.
                if ($stok->jumlah <= 0 && $stok->jumlah_keluar <= 0) {
                    $stok->delete();
                } else {
                    $stok->save();
                }
            }
            $transaksi_masuk->delete();
            DB::commit();
            $this->dispatch('closeDeleteModal');
            session()->flash('message', 'Transaksi masuk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menghapus transaksi masuk. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ----------------------------------Eksport/Import (TIDAK BERUBAH)----------------------------------------------- //
    public function exportExcel()
    {
        $query = TransaksiMasuk::with(['obat', 'sumberAnggaran', 'supplier'])
            ->when($this->search, function ($queryBuilder) {
                $queryBuilder->where('tanggal_kadaluarsa', 'like', '%' . $this->search . '%');
            });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $transaksi_masuksToExport = $query->get();
        $fileName = 'Daftar-TransaksiMasuk_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.xlsx';
        return Excel::download(new TransaksiMasuksExport($transaksi_masuksToExport), $fileName);
    }

    public function exportPdf()
    {
        $query = TransaksiMasuk::query()->when($this->search, function ($queryBuilder) {
            $queryBuilder->where(function ($q) {
                $q->where('batch_number', 'like', '%' . $this->search . '%')
                    ->orWhere('tanggal_masuk', 'like', '%' . $this->search . '%')
                    ->orWhere('no', 'like', '%' . $this->search . '%');
            })
                ->orWhereHas('obat', function ($q) {
                    // Mengakses nama_obat dari tabel obat
                    $q->where('nama_obat', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('supplier', function ($q) {
                    // Mengakses nama_supplier dari tabel supplier
                    $q->where('nama_supplier', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('sumberAnggaran', function ($q) {
                    // Mengakses nama_sumber_anggaran dari tabel sumber_anggaran
                    $q->where('nama_anggaran', 'like', '%' . $this->search . '%');
                });
        });

        if ($this->sort && $this->direction) {
            $query->orderBy($this->sort, $this->direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $transaksi_masuksToExport = $query->get();
        $fileName = 'Daftar-TransaksiMasuk_' . now()->timezone('Asia/Makassar')->format('d-m-Y_H.i') . '.pdf';
        $pdf = Pdf::loadView('pdfs.transaksi-masuks', ['transaksi_masuks' => $transaksi_masuksToExport]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new TransaksiMasuksImport, $this->file);
            session()->flash('message', 'Data transaksi masuk berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('error', 'Gagal mengimpor data. Periksa kembali format file.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $this->reset('file');
    }

    public function importExcel()
    {
        $this->reset(['file', 'message']);
    }
}
