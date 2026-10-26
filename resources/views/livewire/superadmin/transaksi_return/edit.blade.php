<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form wire:submit.prevent="update">
                <div class="modal-header bg-warning">
                    <h3 class="modal-title" id="editModalLabel">
                        <i class="fas fa-edit mr-1"></i> Edit Data Transaksi Return
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="resetValidationState">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h5 class="mb-4">Informasi Pengembalian Barang</h5>

                    {{-- 1. REFERENSI TRANSAKSI KELUAR --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="id_transaksi_keluar" class="form-label">Nama Barang (Batch)<span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <select wire:model.live="id_transaksi_keluar" id="id_transaksi_keluar"
                                class="form-control @error('id_transaksi_keluar') is-invalid @enderror">
                                <option value="">-- Pilih Transaksi Keluar --</option>
                                @foreach ($listTransaksiKeluars as $itemGabungan)
                                    <option value="{{ $itemGabungan['id'] }}">
                                        {{ $itemGabungan['nama_obat'] }} (Batch: {{ $itemGabungan['batch_number'] }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_transaksi_keluar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- 2. TANGGAL RETURN --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="tanggal_return" class="form-label">Tanggal Return <span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="tanggal_return" type="date"
                                class="form-control @error('tanggal_return') is-invalid @enderror">
                            @error('tanggal_return')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- 3. JUMLAH RETURN & MAX LIMIT --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="jumlah_return" class="form-label">Jumlah Return <span
                                    class="text-danger">*</span></label>
                            @if ($max_jumlah_return > 0)
                                <div class="alert alert-info mt-1 p-1" style="font-size: 0.8rem;">
                                    Batas Maks: <strong>{{ $max_jumlah_return }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="col-8">
                            <input wire:model.live="jumlah_return" type="number" min="1"
                                max="{{ $max_jumlah_return }}" {{-- Ini akan berubah otomatis --}}
                                class="form-control @error('jumlah_return') is-invalid @enderror">

                            @if ($max_jumlah_return > 0)
                                <small class="text-muted">Sisa yang bisa dikembalikan:
                                    <strong>{{ $max_jumlah_return }}</strong></small>
                            @endif
                        </div>
                    </div>



                    {{-- 5. STATUS STOK --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="status_stok" class="form-label">Status Stok <span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <select wire:model.live="status_stok" id="status_stok"
                                class="form-control @error('status_stok') is-invalid @enderror">
                                <option value="Expired">Expired (Hapus dari Stok)</option>
                                <option value="Kembali Stok">Kembali ke Stok (Gudang)</option>
                            </select>
                            @error('status_stok')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"
                        wire:click="resetValidationState">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
