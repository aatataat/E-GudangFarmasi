<div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="exampleModalLabel">
                    <img src="{{ asset('img\puskes.png') }}" alt="Logo Dinas Kesehatan"
                                        style="height: 50px; width: auto; object-fit: contain;">
                    {{ $tittle }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <h5>
                    <i class="fas fa-plus mr-0.5 mt-2 "></i>
                    Tambah Data Faskes
                </h5>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="nama_faskes" class="form-label">Nama Faskes<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="nama_faskes" type="text"
                            class="form-control 
                            @error('nama_faskes')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Nama Faskes">
                        @error('nama_faskes')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="alamat" class="form-label">Alamat<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="alamat" type="text"
                            class="form-control 
                            @error('alamat')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Alamat">
                        @error('alamat')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="telp" class="form-label">Telpon<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="telp" type="text"
                            class="form-control 
                            @error('telp')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Telpon">
                        @error('telp')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="email" class="form-label">Email<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="email" type="text"
                            class="form-control 
                            @error('email')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Email">
                        @error('email')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="nama_kepala" class="form-label">Nama Kepala<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="nama_kepala" type="text"
                            class="form-control 
                            @error('nama_kepala')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Nama Kepala">
                        @error('nama_kepala')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="nip_kepala" class="form-label">NIP Kepala<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="nip_kepala" type="text"
                            class="form-control 
                            @error('nip_kepala')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan NIP Kepala">
                        @error('nip_kepala')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="nama_apoteker" class="form-label">Nama Apoteker<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="nama_apoteker" type="text"
                            class="form-control 
                            @error('nama_apoteker')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Nama Apoteker">
                        @error('nama_apoteker')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="nip_apoteker" class="form-label">NIP Apoteker<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="nip_apoteker" type="text"
                            class="form-control 
                            @error('nip_apoteker')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan NIP Apoteker">
                        @error('nip_apoteker')
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
                <button wire:click="store" type="button" class="btn btn-primary btn-sm"><i
                        class="fas fa-save mr-1 "></i> Simpan</button>
            </div>
        </div>
    </div>
</div>
