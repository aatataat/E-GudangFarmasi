<div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h3 class="modal-title" id="exampleModalLabel">
                    Konfirmasi Hapus Data Transaksi Return
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                {{-- Data yang ditampilkan disesuaikan dengan Transaksi Return --}}
                
                <p>Anda akan menghapus data Retur berikut:</p>
                
                <div class="row">
                    <div class="col-4">
                        Nama Obat
                    </div>
                    <div class="col-8">
                        : <strong>{{ $nama_obat_display ?? 'N/A' }}</strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Batch Number
                    </div>
                    <div class="col-8">
                        : {{ $batch_number}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Tanggal Return
                    </div>
                    <div class="col-8">
                        : {{ \Carbon\Carbon::parse($tanggal_return)->format('d F Y') }} {{-- Sudah diformat di confirm() --}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Jumlah Return
                    </div>
                    <div class="col-8">
                        : <strong>{{ $jumlah_return }}</strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        Status Stok
                    </div>
                    <div class="col-8">
                        : {{ $status_stok == 'Kembali_Stok' ? 'Kembali ke Stok' : 'Expired (Stok Rusak/Kedaluwarsa)' }}
                    </div>
                </div>

                
                
            </div>
            <div class="modal-footer d-flex flex-column align-items-center">
                <span class="mb-3 font-weight-bold text-danger">Apakah Anda Yakin Ingin Menghapus Data Ini? Tindakan ini tidak dapat dibatalkan dan akan mengembalikan stok yang terpengaruh.</span>
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary btn-sm mr-2" data-dismiss="modal" 
                        wire:click="resetValidationState">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    {{-- Pastikan wire:click memanggil destroy() dengan ID yang telah disimpan di properti --}}
                    <button wire:click="destroy({{ $return_id }})" type="button"
                        class="btn btn-danger btn-sm">
                        <i class="fas fa-trash mr-1 "></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>