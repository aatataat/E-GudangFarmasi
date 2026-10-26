<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form wire:submit.prevent="update({{ $transaksi_keluar_id }})">

                <div class="modal-header bg-warning">
                    <h3 class="modal-title" id="editModalLabel">
                        Edit Data Transaksi Keluar
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h5 class="mb-4">
                        <i class="fas fa-edit mr-1"></i>
                        Edit Data Transaksi Keluar
                    </h5>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label class="form-label">Barang & Batch</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control bg-light"
                                value="{{ $batch_number_display }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label class="form-label">Tujuan Faskes</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control bg-light"
                                value="{{ $allFaskes->find($faskes_id)->nama_faskes ?? 'Tidak Ditemukan' }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label class="form-label">Tanggal Keluar</label>
                        </div>
                        <div class="col-8">
                            <input wire:model="tanggal_keluar" type="date" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="jumlah" class="form-label font-weight-bold">Jumlah Keluar <span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="jumlah" type="number"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                placeholder="Masukkan Jumlah">
                            @error('jumlah')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-info d-block mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Maksimal stok yang dapat digunakan: <strong>{{ $jumlah_stok_tersedia }}</strong>
                            </small>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label class="form-label">Total Nilai (Rp)</label>
                        </div>
                        <div class="col-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">Rp</span>
                                </div>
                                <input type="text" class="form-control bg-light font-weight-bold"
                                    value="{{ number_format($total, 0, ',', '.') }}" readonly>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-save mr-1"></i> Update Jumlah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
