<div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h3 class="modal-title" id="exampleModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M13.641 21.162c0 1.443 1.563 2.345 2.813 1.624c.58-.335.938-.955.938-1.625c0-1.443-1.563-2.345-2.813-1.624c-.58.336-.938.955-.938 1.625m-10.314 0c0 1.443 1.563 2.345 2.813 1.624c.58-.335.938-.955.938-1.625c0-1.443-1.563-2.345-2.813-1.624c-.58.336-.938.955-.938 1.625m16.966-4.005a1.865 1.865 0 0 1-1.856 1.66H1.022a.977.977 0 0 1 0-1.914h16.604a.98.98 0 0 0 .977-.83l1.514-13.41a1.866 1.866 0 0 1 1.856-1.66h.537a.977.977 0 1 1 0 1.875h-.362a.234.234 0 0 0-.234.205Z" />
                                    <path fill="currentColor"
                                        d="M1.92 11.785h6.564s.938 0 .938.938v1.875s0 .938-.938.938H1.921s-.938 0-.938-.938v-1.875s0-.938.938-.938m2.813-4.219h3.75s.938 0 .938.937v.938s0 .938-.938.938h-3.75s-.938 0-.938-.938v-.938s0-.937.938-.937m7.032-2.344h4.688s.938 0 .938.937v8.44s0 .937-.938.937h-4.688s-.938 0-.938-.938V6.16s0-.937.938-.937" />
                                </svg>
                    {{ $tittle }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        Nama Penyedia
                    </div>
                    <div class="col-8">
                        : {{ $nama_supplier }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Alamat
                    </div>
                    <div class="col-8">
                        : {{ $alamat }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        No Handphone
                    </div>
                    <div class="col-8">
                        : {{ $no_hp }}
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex flex-column align-items-center">
                <span class="mb-3">Apakah Anda Yakin Menghapus?</span>
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button wire:click="destroy({{ $supplier_id }})" type="button" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash mr-1 "></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
