<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h3 class="modal-title" id="exampleModalLabel">
                    
                    {{ $tittle }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>
                    <i class="fas fa-edit mr-0.5 mt-2 "></i>
                    Edit {{ $tittle }}
                </h5>

                <div class="div row mt-4">
                    <div class="col-5 mt-1 ">
                        <label for="kode_rak" class="form-label">Kode Penyimpanan</label>
                        <span class="text-danger">*</span>
                    </div>
                    <div class="col-7">
                        <input wire:model.live="kode_rak" type="text"
                            class="form-control
                            @error('kode_rak')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Nama">
                        @error('kode_rak')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-3">
                    <div class="col-5 mt-1.5">
                        <label for="lokasi_rak" class="form-label">Lokasi Penyimpanan</label>
                        <span class="text-danger">*</span>
                    </div>
                    <div class="col-7">
                        <input wire:model.live="lokasi_rak" type="lokasi_rak"
                            class="form-control
                            @error('lokasi_rak')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Alamat">
                        @error('lokasi_rak')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                        class="fas fa-times mr-1"></i> Tutup</button>
                <button wire:click="update({{ $rak_penyimpanan_id }})" type="button" class="btn btn-warning btn-sm"><i
                        {{-- {{ $user_id }} untuk mengambil id --}} class="fas fa-edit mr-1 "></i> Update</button>
            </div>
        </div>
    </div>
</div>
