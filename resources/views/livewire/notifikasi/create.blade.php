<div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white">Tambah Notifikasi</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="pesan">Pesan <span class="text-danger">*</span></label>
                    <textarea wire:model="pesan" class="form-control @error('pesan') is-invalid @enderror"
                        placeholder="Masukkan pesan notifikasi"></textarea>
                    @error('pesan') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="tipe">Tipe <span class="text-danger">*</span></label>
                    <select wire:model="tipe" class="form-control @error('tipe') is-invalid @enderror">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="info">Kedaluwarsa</option>
                        <option value="warning">Stok Minimum</option>
                        <option value="">Lainnya</option>
                    </select>
                    @error('tipe') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select wire:model="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="Belum Dibaca">Belum Dibaca</option>
                        <option value="Dibaca">Dibaca</option>
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                        class="fas fa-times mr-1"></i> Tutup</button>
                <button wire:click="store" type="button" class="btn btn-primary btn-sm"><i
                        class="fas fa-save mr-1"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>
