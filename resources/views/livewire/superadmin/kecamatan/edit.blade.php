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
                        <label for="nama_kecamatan" class="form-label">Nama Kecamatan</label>
                        <span class="text-danger">*</span>
                    </div>
                    <div class="col-7">
                        <input wire:model.live="nama_kecamatan" type="text"
                            class="form-control
                            @error('nama_kecamatan')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Nama">
                        @error('nama_kecamatan')
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
                <button wire:click="update({{ $kecamatan_id }})" type="button" class="btn btn-warning btn-sm"><i
                        {{-- {{ $user_id }} untuk mengambil id --}} class="fas fa-edit mr-1 "></i> Update</button>
            </div>
        </div>
    </div>
</div>
