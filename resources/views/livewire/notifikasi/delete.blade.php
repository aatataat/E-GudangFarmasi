<div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h3 class="modal-title text-white">Hapus Notifikasi</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <p>Apakah Anda yakin ingin menghapus notifikasi ini?</p>
                <strong>"{{ $pesan }}"</strong>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                        class="fas fa-times mr-1"></i> Batal</button>
                <button wire:click="destroy({{ $notifikasi_id }})" type="button" class="btn btn-danger btn-sm"><i
                        class="fas fa-trash mr-1"></i> Hapus</button>
            </div>
        </div>
    </div>
</div>
