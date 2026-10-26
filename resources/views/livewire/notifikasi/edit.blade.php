<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h3 class="modal-title text-dark">Edit Notifikasi</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="pesan">Pesan <span class="text-danger">*</span></label>
                    <textarea wire:model="pesan" class="form-control @error('pesan') is-invalid @enderror"></textarea>
                    @error('pesan') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="tipe">Tipe <span class="text-danger">*</span></label>
                    <select wire:model="tipe" class="form-control @error('tipe') is-invalid @enderror">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="info">Info</option>
                        <option value="peringatan">Peringatan</option>
                        <option value="sistem">Sistem</option>
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
                <button wire:click="update({{ $notifikasi_id }})" type="button" class="btn btn-warning btn-sm"><i
                        class="fas fa-save mr-1"></i> Update</button>
            </div>
        </div>
    </div>
</div>
