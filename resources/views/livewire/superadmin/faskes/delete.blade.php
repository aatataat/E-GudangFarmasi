<div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h3 class="modal-title text-white" id="deleteModalLabel">
                    <i class="fas fa-trash-alt mr-1"></i> Konfirmasi Hapus
                </h3>
                {{-- Menggunakan data-dismiss Bootstrap 4 atau data-bs-dismiss Bootstrap 5 --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <p class="text-center text-danger font-weight-bold">
                    Apakah Anda Yakin Ingin Menghapus Data Faskes Ini Secara Permanen?
                </p>

                <hr>

                {{-- Tampilan Detail Faskes (Diisi oleh Properti Livewire) --}}
                <h5 class="mb-3 text-primary">Detail Faskes:</h5>

                <div class="row mb-1">
                    <div class="col-5 font-weight-bold">Nama Faskes</div>
                    <div class="col-7">: {{ $nama_faskes }}</div>
                </div>

                <div class="row mb-1">
                    <div class="col-5 font-weight-bold">Alamat</div>
                    <div class="col-7">: {{ $alamat }}</div>
                </div>

                <div class="row mb-1">
                    <div class="col-5 font-weight-bold">Telepon / Email</div>
                    <div class="col-7">: {{ $telp }} / {{ $email }}</div>
                </div>
                
                <hr>

                <div class="row mb-1">
                    <div class="col-5 font-weight-bold">Nama Kepala</div>
                    <div class="col-7">: {{ $nama_kepala }}</div>
                </div>
                <div class="row mb-1">
                    <div class="col-5 font-weight-bold">NIP Kepala</div>
                    <div class="col-7">: {{ $nip_kepala }}</div>
                </div>
                
                <div class="row mb-1 mt-3">
                    <div class="col-5 font-weight-bold">Nama Apoteker</div>
                    <div class="col-7">: {{ $nama_apoteker }}</div>
                </div>
                <div class="row mb-1">
                    <div class="col-5 font-weight-bold">NIP Apoteker</div>
                    <div class="col-7">: {{ $nip_apoteker }}</div>
                </div>
                
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                
                {{-- Tombol Hapus: Memanggil method destroy($id) --}}
                <button wire:click="destroy({{ $faskes_id }})" type="button" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash mr-1 "></i> Ya, Hapus Data
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listener untuk menutup modal setelah berhasil dihapus
    window.addEventListener('closeDeleteModal', event => {
        $('#deleteModal').modal('hide');
    });
</script>
@endpush