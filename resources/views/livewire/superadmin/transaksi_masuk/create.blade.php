<div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl"> {{-- Menggunakan modal-xl karena formulir akan lebih lebar --}}
        <div class="modal-content">

            {{-- HEADER MODAL --}}
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="exampleModalLabel">
                    {{ $tittle }}
                </h3>
                {{-- Mematuhi instruksi kustom, menggunakan tombol close standar --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- BODY MODAL - FORM MASTER-DETAIL --}}
            <div class="modal-body">

                {{-- BAGIAN 1: HEADER TRANSAKSI (MASTER) --}}
                <h5>
                    <i class="fas fa-plus mr-0.5 mt-2 "></i>
                    Tambah Transaksi Masuk
                </h5>
                <hr>
                <div class="row">
                    {{-- Input No. Faktur --}}
                    <div class="col-md-4 mb-3">
                        <label for="no" class="form-label">No. Faktur <span class="text-danger">*</span></label>
                        <input wire:model.live="no" type="text"
                            class="form-control @error('no') is-invalid @enderror" placeholder="Masukkan Nomor Faktur">
                        @error('no')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Input Tanggal Masuk --}}
                    <div class="col-md-4 mb-3">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span
                                class="text-danger">*</span></label>
                        <input wire:model.live="tanggal_masuk" type="date"
                            class="form-control @error('tanggal_masuk') is-invalid @enderror">
                        @error('tanggal_masuk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Dropdown Supplier --}}
                    <div class="col-md-4 mb-3">
                        <label for="id_supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select wire:model.live="id_supplier"
                            class="form-control @error('id_supplier') is-invalid @enderror">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplierItem)
                                <option value="{{ $supplierItem->id }}">{{ $supplierItem->nama_supplier }}</option>
                            @endforeach
                        </select>
                        @error('id_supplier')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Dropdown Sumber Anggaran --}}
                    <div class="col-md-4 mb-3">
                        <label for="id_sumber_anggaran" class="form-label">Sumber Anggaran <span
                                class="text-danger">*</span></label>
                        <select wire:model.live="id_sumber_anggaran"
                            class="form-control @error('id_sumber_anggaran') is-invalid @enderror">
                            <option value="">-- Pilih Sumber Anggaran --</option>
                            @foreach ($sumberAnggarans as $anggaranItem)
                                <option value="{{ $anggaranItem->id }}">{{ $anggaranItem->nama_anggaran }}</option>
                            @endforeach
                        </select>
                        @error('id_sumber_anggaran')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div> {{-- /div.row Master --}}

                <br>

                {{-- BAGIAN 2: DETAIL BARANG (ARRAY) --}}
                <h5 class="mb-3">
                    <i class="fas fa-boxes mr-1"></i> Item Barang Masuk
                    <button wire:click="addItemDetail" type="button" class="btn btn-success btn-sm float-right"><i
                            class="fas fa-plus"></i> Tambah Baris</button>
                </h5>
                <hr>

                {{-- TABLE DETAIL ITEMS --}}
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr class="table-info">
                            <th style="width: 25%">Nama Barang</th>
                            <th style="width: 15%">Batch Number</th>
                            <th style="width: 15%">Tgl. Kedaluwarsa</th>
                            <th style="width: 10%">Jumlah</th>
                            <th style="width: 15%">Harga Satuan</th>
                            <th style="width: 15%">Total</th>
                            <th style="width: 5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- LOOPING UNTUK SETIAP BARIS DETAIL ITEM --}}
                        @forelse ($items as $index => $item)
                            <tr>
                                <td>
                                    <select wire:model.live="items.{{ $index }}.obat_id"
                                        class="form-control form-control-sm @error('items.' . $index . '.obat_id') is-invalid @enderror">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach ($obats as $obatItem)
                                            <option value="{{ $obatItem->id }}">{{ $obatItem->nama_obat }}</option>
                                        @endforeach
                                    </select>
                                    @error('items.' . $index . '.obat_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </td>
                                <td>
                                    <input wire:model.live="items.{{ $index }}.batch_number" type="text" placeholder="Nomor Batch"
                                        class="form-control form-control-sm @error('items.' . $index . '.batch_number') is-invalid @enderror">
                                    @error('items.' . $index . '.batch_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </td>
                                <td>
                                    <input wire:model.live="items.{{ $index }}.tanggal_kadaluarsa"
                                        type="date"
                                        class="form-control form-control-sm @error('items.' . $index . '.tanggal_kadaluarsa') is-invalid @enderror">
                                    @error('items.' . $index . '.tanggal_kadaluarsa')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </td>
                                <td>
                                    <input wire:model.live="items.{{ $index }}.jumlah" type="number"
                                        min="1"
                                        class="form-control form-control-sm @error('items.' . $index . '.jumlah') is-invalid @enderror">
                                    @error('items.' . $index . '.jumlah')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </td>
                                <td>
                                    <input wire:model.live="items.{{ $index }}.harga" type="number"
                                        min="0"
                                        class="form-control form-control-sm @error('items.' . $index . '.harga') is-invalid @enderror">
                                    @error('items.' . $index . '.harga')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </td>
                                <td>
                                    {{-- Kolom total ini hanya menampilkan nilai yang dihitung di backend Livewire --}}
                                    <input value="{{ number_format($item['total'] ?? 0, 0, ',', '.') }}" type="text"
                                        readonly class="form-control form-control-sm text-right">
                                </td>
                                <td class="text-center">
                                    <button wire:click="removeItemDetail({{ $index }})" type="button"
                                        class="btn btn-danger btn-sm p-1" title="Hapus Item"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Klik "Tambah Baris" untuk
                                    menambahkan item obat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>TOTAL KESELURUHAN</strong></td>
                            <td>
                                {{-- Properti $grandTotal harus dihitung di komponen Livewire Anda --}}
                                <input value="{{ number_format($grandTotal ?? 0, 0, ',', '.') }}" type="text"
                                    readonly
                                    class="form-control form-control-sm text-right bg-warning font-weight-bold">
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

            </div> {{-- /modal-body --}}

            {{-- FOOTER MODAL --}}
            <div class="modal-footer d-flex">
                <button wire:click="store" type="button" class="btn btn-primary btn-sm"><i
                        class="fas fa-save mr-1"></i> Simpan Transaksi</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                        class="fas fa-times mr-1"></i> Tutup</button>
                {{-- Tombol Simpan akan mengirimkan data Master dan Detail --}}
            </div>

        </div>
    </div>
</div>
