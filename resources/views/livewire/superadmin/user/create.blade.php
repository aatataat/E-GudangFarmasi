<div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="exampleModalLabel">
                    <i class="fas fa-user mr-1"></i>
                    {{ $tittle }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>
                    <i class="fas fa-plus mr-0.5 mt-2 "></i>
                    Tambah Data User
                </h5>

                <div class="div row mt-4 ">
                    <div class="col-4 mt-1 ">
                        <label for="nama" class="form-label">Nama<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="nama" type="text"
                            class="form-control
                            @error('nama')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Nama">
                        @error('nama')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-3">
                    <div class="col-4 mt-1.5">
                        <label for="email" class="form-label">Email<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="email" type="email"
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

                <div class="div row mt-3">
                    <div class="col-4 mt-2">
                        <label for="role" class="form-label">Role<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <select wire:model.live="role" name="role" id="role"
                            class="form-control
                            @error('role')
                            is-invalid
                            @enderror">
                            <option selected>-- Pilih Role --</option>
                            <option value="Pimpinan">Pimpinan</option>
                            <option value="Admin">Admin</option>
                            <option value="Pegawai">Pegawai</option>
                            <option value="Faskes">Fasilitas Kesehatan</option>
                        </select>
                        @error('role')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>
                {{-- START: BLOK KONDISIONAL FASKES ID --}}
                {{-- Tampil jika role adalah Faskes ATAU Pegawai --}}
                @if (in_array($role, ['Faskes'])) 
                <div class="div row mt-3">
                    <div class="col-4 mt-2">
                        <label for="faskes_id" class="form-label">Nama Faskes<span
                                style="color: rgba(255, 0, 0, 0.6)">*</span></label>
                    </div>
                    <div class="col-8">
                        <select wire:model="faskes_id" name="faskes_id" id="faskes_id"
                            class="form-control @error('faskes_id') is-invalid @enderror">
                            <option selected value="">-- Pilih Faskes --</option>
                            {{-- Loop untuk menampilkan daftar Faskes dari Livewire Component --}}
                            @foreach ($faskesList as $faskes)
                                <option value="{{ $faskes->id }}">{{ $faskes->nama_faskes }}</option>
                            @endforeach
                        </select>
                        @error('faskes_id')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>
                @endif
                {{-- END: BLOK KONDISIONAL FASKES ID --}}


                <div class="div row mt-3">
                    <div class="col-4 mt-2">
                        <label for="password" class="form-label">Password<span style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="password" type="password"
                            class="form-control
                            @error('password')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Password">
                        @error('password')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </div>

                <div class="div row mt-3">
                    <div class="col-4 mt-2">
                        <label for="password_confirmation" class="form-label ">Password Konfirmasi<span
                                class="text-danger">*</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="password_confirmation" type="password"
                            class="form-control
                            @error('password_confirmation')
                            is-invalid
                        @enderror"
                            placeholder="Masukkan Password Confirmation">
                        @error('password_confirmation')
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