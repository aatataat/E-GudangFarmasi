<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- Menggunakan form untuk menangkap event submit --}}
            <form wire:submit.prevent="update({{ $transaksi_masuk_id }})">

                <div class="modal-header bg-warning">
                    <h3 class="modal-title" id="editModalLabel">
                        Edit Data Transaksi Masuk
                    </h3>
                    {{-- Tombol Close/X: Panggil metode resetValidation() saat modal ditutup --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h5 class="mb-4">
                        <i class="fas fa-edit mr-1"></i>
                        Edit Data Transaksi Masuk
                    </h5>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="no" class="form-label">No <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="no" type="text"
                                class="form-control @error('no') is-invalid @enderror" placeholder="Masukkan Nomor">
                            @error('no')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="obat_id" class="form-label">Nama Barang <span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            {{-- PENTING: Perbaiki properti binding menjadi obat_id --}}
                            <select wire:model.live="obat_id" name="obat_id" id="obat_id"
                                class="form-control @error('obat_id') is-invalid @enderror">
                                <option value="">-- Pilih Obat --</option>
                                {{-- Pastikan $obats berisi data Obat, bukan TransaksiMasuk --}}
                                @foreach ($obats as $obatItem)
                                    <option value="{{ $obatItem->id }}">{{ $obatItem->nama_obat }}</option>
                                @endforeach
                            </select>
                            @error('obat_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="batch_number" class="form-label">Batch Number <span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="batch_number" type="text"
                                class="form-control @error('batch_number') is-invalid @enderror"
                                placeholder="Masukkan Batch Number">
                            @error('batch_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="tanggal_masuk" type="date"
                                class="form-control @error('tanggal_masuk') is-invalid @enderror">
                            @error('tanggal_masuk')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kedaluwarsa <span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="tanggal_kadaluarsa" type="date"
                                class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror">
                            @error('tanggal_kadaluarsa')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="jumlah" type="number"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                placeholder="Masukkan Jumlah">
                            @error('jumlah')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="harga" type="number"
                                class="form-control @error('harga') is-invalid @enderror" placeholder="Masukkan Harga">
                            @error('harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="total" class="form-label">Total <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="total" type="text"
                                class="form-control @error('total') is-invalid @enderror" placeholder="Total"
                                readonly>
                            @error('total')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="id_sumber_anggaran" class="form-label">Sumber Anggaran <span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <select wire:model.live="id_sumber_anggaran" name="id_sumber_anggaran"
                                id="id_sumber_anggaran"
                                class="form-control @error('id_sumber_anggaran') is-invalid @enderror">
                                <option value="">-- Pilih Sumber Anggaran --</option>
                                @foreach ($sumberAnggarans as $sumberAnggaranItem)
                                    <option value="{{ $sumberAnggaranItem->id }}">
                                        {{ $sumberAnggaranItem->nama_anggaran }}</option>
                                @endforeach
                            </select>
                            @error('id_sumber_anggaran')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="id_supplier" class="form-label">Supplier <span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <select wire:model.live="id_supplier" name="id_supplier" id="id_supplier"
                                class="form-control @error('id_supplier') is-invalid @enderror">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $supplierItem)
                                    <option value="{{ $supplierItem->id }}">{{ $supplierItem->nama_supplier }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_supplier')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    {{-- Tombol Tutup: Panggil metode resetValidation() saat modal ditutup --}}
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                            class="fas fa-times mr-1"></i> Tutup</button>
                    {{-- Tombol Update: Menggunakan type="submit" untuk memicu wire:submit.prevent pada form --}}
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
