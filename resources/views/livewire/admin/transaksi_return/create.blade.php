<div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="exampleModalLabel">
                    {{ $tittle }}
                </h3>
                {{-- Tombol Tutup Sesuai Instruksi --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    wire:click="resetValidationState">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>
                    <i class="fas fa-plus-circle mr-1 mt-2"></i>
                    Tambah Data Transaksi Return
                </h5>

                {{-- Row 1: Pilih Faskes --}}
                <div class="row mt-3">
                    <div class="col-4 mt-1">
                        <label class="form-label">Pilih Faskes Asal<span class="text-danger"> *</span></label>
                    </div>
                    <div class="col-8">
                        <select wire:model.live="faskes_id"
                            class="form-control @error('faskes_id') is-invalid @enderror">
                            <option value="">-- Pilih Puskesmas / Faskes --</option>
                            @foreach ($listFaskes as $f)
                                <option value="{{ $f->id }}">{{ $f->nama_faskes }}</option>
                            @endforeach
                        </select>
                        @error('faskes_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Row 2: Tanggal Return --}}
                <div class="row mt-3">
                    <div class="col-4 mt-1">
                        <label for="tanggal_return" class="form-label">Tanggal Return<span class="text-danger">
                                *</span></label>
                    </div>
                    <div class="col-8">
                        <input wire:model.live="tanggal_return" type="date"
                            class="form-control @error('tanggal_return') is-invalid @enderror">
                        @error('tanggal_return')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

         

                <hr>

                {{-- Loop untuk Detail Item (Multi-Entry) --}}
                <h5>
                    <i class="fas fa-list-alt mr-1"></i>
                    Detail Item Return
                </h5>

                @if ($faskes_id)
                    @foreach ($detailItems as $index => $item)
                        <div class="card p-3 mb-3 border border-primary shadow-sm"
                            wire:key="return-item-{{ $index }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary font-weight-bold">Item #{{ $index + 1 }}</h6>
                                @if (count($detailItems) > 1)
                                    <button type="button" class="btn btn-danger btn-sm"
                                        wire:click="removeItem({{ $index }})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                @endif
                            </div>

                            <div class="row">
                                {{-- Pilih Barang --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Barang (Batch)<span class="text-danger"> *</span></label>
                                    <select wire:model.live="detailItems.{{ $index }}.id_transaksi_keluar"
                                        class="form-control @error('detailItems.' . $index . '.id_transaksi_keluar') is-invalid @enderror">
                                        <option value="">-- Pilih Transaksi Keluar yang Diretur --</option>
                                        @foreach ($listTransaksiKeluars as $itemGabungan)
                                            <option value="{{ $itemGabungan['id'] }}">
                                                {{ $itemGabungan['nama_obat'] }} (Batch:
                                                {{ $itemGabungan['batch_number'] }}) - Sisa: {{ $itemGabungan['sisa'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('detailItems.' . $index . '.id_transaksi_keluar')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    @error('duplicate_item')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Gagal Simpan!</strong> {{ $message }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @enderror

                                    @if (session()->has('error'))
                                        <div class="alert alert-warning">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    @if ($item['max_jumlah_return'] > 0)
                                        <div class="alert alert-info mt-2 p-2 mb-0" style="font-size: 0.85rem;">
                                            <i class="fas fa-info-circle mr-1"></i> Max Retur Tersedia:
                                            <strong>{{ $item['max_jumlah_return'] }}</strong>
                                        </div>
                                    @endif
                                </div>

                                {{-- Jumlah Return --}}
                                <div class="col-6">
                                    <label class="form-label">Jumlah Return<span class="text-danger"> *</span></label>
                                    <input wire:model.live="detailItems.{{ $index }}.jumlah_return"
                                        type="number" min="1" max="{{ $item['max_jumlah_return'] }}"
                                        class="form-control @error('detailItems.' . $index . '.jumlah_return') is-invalid @enderror"
                                        placeholder="0">
                                    @error('detailItems.' . $index . '.jumlah_return')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Status Barang --}}
                                <div class="col-6">
                                    <label class="form-label">Status Barang<span class="text-danger"> *</span></label>
                                    <select wire:model.live="detailItems.{{ $index }}.status_stok"
                                        class="form-control @error('detailItems.' . $index . '.status_stok') is-invalid @enderror">
                                        <option value="Expired">Sudah Kedaluwarsa</option>
                                        <option value="Kembali Stok">Kembali ke Stok</option>
                                    </select>
                                    @error('detailItems.' . $index . '.status_stok')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-right mt-2">
                        <button type="button" class="btn btn-outline-success btn-sm" wire:click="addItem">
                            <i class="fas fa-plus mr-1"></i> Tambah Item Retur
                        </button>
                    </div>
                @else
                    <div class="alert alert-warning text-center mt-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Silakan pilih <strong>Faskes Asal</strong>
                        terlebih dahulu untuk memuat daftar barang.
                    </div>
                @endif

            </div>

            <div class="modal-footer">
                <button wire:click="store" type="button" class="btn btn-primary btn-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Transaksi
                </button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"
                    wire:click="resetValidationState">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
