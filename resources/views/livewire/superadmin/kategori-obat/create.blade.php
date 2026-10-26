<div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="exampleModalLabel">
                    {{ $tittle }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <h5>
                    <i class="fas fa-plus mr-0.5 mt-2 "></i>
                    Tambah Data Kategori
                </h5>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="nama_kategori" class="form-label">Nama Kategori<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="nama_kategori" type="text"
                            class="form-control 
                            @error('nama_kategori')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Nama Kategori">
                        @error('nama_kategori')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button wire:click="store" type="button" class="btn btn-primary btn-sm"><i
                        class="fas fa-save mr-1 "></i> Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                        class="fas fa-times mr-1"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>
