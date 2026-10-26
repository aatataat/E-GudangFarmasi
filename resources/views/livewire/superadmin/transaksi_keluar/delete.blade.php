<div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h3 class="modal-title" id="exampleModalLabel">
                    {{ $tittle }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-4">
                        Tanggal Keluar
                    </div>
                    <div class="col-8">
                        : {{ \Carbon\Carbon::parse($tanggal_keluar)->format('d F Y') }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Batch Number
                    </div>
                    <div class="col-8">
                        : {{ $batch_number_display }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Jumlah
                    </div>
                    <div class="col-8">
                        : {{ $jumlah }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Harga Satuan Keluar
                    </div>
                    <div class="col-8">
                        : {{ $harga }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Total Keluar
                    </div>
                    <div class="col-8">
                        : {{ $total }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Tanggal Kedaluwarsa
                    </div>
                    <div class="col-8">
                        : {{ \Carbon\Carbon::parse($tanggal_kadaluarsa)->format('d F Y') }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Faskes Tujuan
                    </div>
                    <div class="col-8">
                        : {{ $faskesList->firstWhere('id', $faskes_id)->nama_faskes ?? 'Faskes Tidak Ditemukan' }}
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-4">
                        Supplier
                    </div>
                    <div class="col-8">
                        : {{ $supplier_display ?? '-' }}
                    </div>
                </div>

            </div>
            <div class="modal-footer d-flex flex-column align-items-center">
                <span class="mb-3">Apakah Anda Yakin Menghapus Transaksi Keluar Ini?</span>
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button wire:click="destroy({{ $transaksi_keluar_id }})" type="button"
                        class="btn btn-danger btn-sm">
                        <i class="fas fa-trash mr-1 "></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>