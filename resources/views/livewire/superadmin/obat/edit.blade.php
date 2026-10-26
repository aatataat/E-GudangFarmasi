<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h3 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit mr-1"></i> Edit Data Barang
                </h3>
                {{-- Di sini, wire:click="resetValidation" sekarang akan menemukan method-nya --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetValidation">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form wire:submit.prevent="update({{ $obat_id }})">
                <div class="modal-body">
                    
                    <h5 class="mb-4">
                        <i class="fas fa-prescription-bottle-alt mr-1"></i>
                        Edit {{ $tittle ?? 'Obat' }}
                    </h5>

                    {{-- Field 1: Nama Obat --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="nama_obat" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <input wire:model.live="nama_obat" type="text"
                                class="form-control @error('nama_obat') is-invalid @enderror"
                                placeholder="Masukkan Nama Obat">
                            @error('nama_obat')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Field 2: Kategori Obat --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="id_kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <select wire:model.live="id_kategori" name="id_kategori" id="id_kategori"
                                class="form-control @error('id_kategori') is-invalid @enderror">
                                <option value="">-- Pilih Kategori Barang --</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('id_kategori')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Field 3: Satuan Obat --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="id_satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-8">
                            <select wire:model.live="id_satuan" name="id_satuan" id="id_satuan"
                                class="form-control @error('id_satuan') is-invalid @enderror">
                                <option value="">-- Pilih Satuan --</option>
                                @foreach ($satuans as $satuan)
                                    <option value="{{ $satuan->id }}">{{ $satuan->nama_satuan }}</option>
                                @endforeach
                            </select>
                            @error('id_satuan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                        class="fas fa-times mr-1"></i> Tutup</button>
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
    window.addEventListener('closeEditModal', event => {
        $('#editModal').modal('hide');
    });
</script>
@endpush