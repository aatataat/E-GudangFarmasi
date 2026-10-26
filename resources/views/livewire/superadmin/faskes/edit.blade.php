<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg"> {{-- Menggunakan modal-lg untuk menampung lebih banyak field --}}
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h3 class="modal-title" id="editModalLabel">
                    <img src="{{ asset('img\puskes.png') }}" alt="Logo Dinas Kesehatan"
                                        style="height: 50px; width: auto; object-fit: contain;">
                                        {{ $tittle }}
                    
                </h3>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetFormValidation">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            {{-- Form dimulai di sini. Menggunakan wire:submit.prevent untuk memanggil method update --}}
            <form wire:submit.prevent="update({{ $faskes_id }})">
                <div class="modal-body">
                    <i class="fas fa-edit mr-1"></i> Edit Data Faskes <br><br>
                    
                    
                    {{-- Row 1: Nama Faskes & Alamat --}}
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="nama_faskes" class="form-label">Nama Faskes <span class="text-danger">*</span></label>
                            <input wire:model.live="nama_faskes" type="text"
                                class="form-control @error('nama_faskes') is-invalid @enderror"
                                placeholder="Masukkan Nama Faskes">
                            @error('nama_faskes') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <input wire:model.live="alamat" type="text"
                                class="form-control @error('alamat') is-invalid @enderror"
                                placeholder="Masukkan Alamat Faskes">
                            @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    {{-- Row 2: Telp & Email --}}
                    <div class="row mt-3">
                        <div class="col-md-6 form-group">
                            <label for="telp" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input wire:model.live="telp" type="text"
                                class="form-control @error('telp') is-invalid @enderror"
                                placeholder="Contoh: 081234567890">
                            @error('telp') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input wire:model.live="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Contoh: faskes@email.com">
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">
                    
                    {{-- Judul Pembatas --}}
                    <h5>Data Kepala Faskes</h5>

                    {{-- Row 3: Nama Kepala & NIP Kepala --}}
                    <div class="row mt-3">
                        <div class="col-md-6 form-group">
                            <label for="nama_kepala" class="form-label">Nama Kepala <span class="text-danger">*</span></label>
                            <input wire:model.live="nama_kepala" type="text"
                                class="form-control @error('nama_kepala') is-invalid @enderror"
                                placeholder="Nama Lengkap Kepala">
                            @error('nama_kepala') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="nip_kepala" class="form-label">NIP Kepala <span class="text-danger">*</span></label>
                            <input wire:model.live="nip_kepala" type="text"
                                class="form-control @error('nip_kepala') is-invalid @enderror"
                                placeholder="Masukkan NIP Kepala (Min 15 Angka)">
                            @error('nip_kepala') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">

                    {{-- Judul Pembatas --}}
                    <h5>Data Apoteker</h5>
                    
                    {{-- Row 4: Nama Apoteker & NIP Apoteker --}}
                    <div class="row mt-3">
                        <div class="col-md-6 form-group">
                            <label for="nama_apoteker" class="form-label">Nama Apoteker <span class="text-danger">*</span></label>
                            <input wire:model.live="nama_apoteker" type="text"
                                class="form-control @error('nama_apoteker') is-invalid @enderror"
                                placeholder="Nama Lengkap Apoteker">
                            @error('nama_apoteker') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="nip_apoteker" class="form-label">NIP Apoteker <span class="text-danger">*</span></label>
                            <input wire:model.live="nip_apoteker" type="text"
                                class="form-control @error('nip_apoteker') is-invalid @enderror"
                                placeholder="Masukkan NIP Apoteker (Min 15 Angka)">
                            @error('nip_apoteker') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" wire:click="resetFormValidation">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    {{-- Mengubah button menjadi type="submit" untuk memicu wire:submit.prevent --}}
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listener untuk menutup modal setelah berhasil di-update
    window.addEventListener('closeEditModal', event => {
        $('#editModal').modal('hide');
    });
</script>
@endpush