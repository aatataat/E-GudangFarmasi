<div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h3 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-user mr-1"></i>
                    {{ $tittle }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-2">
                        Nama
                    </div>
                    <div class="col-10">
                        : {{ $nama }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-2">
                        Email
                    </div>
                    <div class="col-10">
                        : {{ $email }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-2">
                        Role
                    </div>
                    <div class="col-10">
                        :
                        @if ($role == 'Admin')
                            <span class="badge badge-pill badge-info">
                                {{ $role }}
                            </span>
                        @elseif($role == 'Pegawai')
                            <span class="badge badge-pill badge-secondary">
                                {{ $role }}
                            </span>
                        @elseif($role == 'Pimpinan')
                            <span class="badge badge-pill badge-primary">
                                {{ $role }}
                            </span>
                        @elseif($role == 'Faskes')
                            <span class="badge badge-pill badge-success">
                                {{ $role }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex flex-column align-items-center">
                <span class="mb-3">Apakah Anda Yakin Menghapus?</span>
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button wire:click="destroy({{ $user_id }})" type="button" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash mr-1 "></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
