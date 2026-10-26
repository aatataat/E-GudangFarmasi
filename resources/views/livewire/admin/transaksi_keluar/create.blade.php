<div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="exampleModalLabel">
                    {{ $tittle }}
                </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>
                    <i class="fas fa-plus mr-0.5 mt-2 "></i>
                    Tambah Data Transaksi Keluar
                </h5>

                <div class="div row mt-3 mb-4 border p-3 rounded">
                    <div class="col-md-6">
                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar<span
                                style="color: rgba(255, 0, 0, 0.6)"> *</span></label>
                        <input wire:model.live="tanggal_keluar" type="date"
                            class="form-control @error('tanggal_keluar') is-invalid @enderror">
                        @error('tanggal_keluar')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="faskes_id" class="form-label">Tujuan Faskes<span
                                style="color: rgba(255, 0, 0, 0.6)">
                                *</span></label>
                        <select wire:model.live="faskes_id" name="faskes_id"
                            class="form-control @error('faskes_id') is-invalid @enderror">
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach ($faskesList as $faskesItem)
                                <option value="{{ $faskesItem->id }}">
                                    {{ $faskesItem->nama_faskes }}
                                </option>
                            @endforeach
                        </select>
                        @error('faskes_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <h5 class="mt-4 mb-3">Daftar Barang yang Keluar
                    <button wire:click="addItem" type="button" class="btn btn-sm btn-success float-right">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Baris
                    </button>
                </h5>

                @foreach ($items as $index => $item)
                    <div class="div row mt-3 p-3 border rounded shadow-sm">
                        <div class="col-md-4">
                            <label for="id_stok_{{ $index }}" class="form-label">Nama Barang <span
                                    style="color: rgba(255, 0, 0, 0.6)"> *</span></label>
                            <select wire:model.live="items.{{ $index }}.id_stok" id="id_stok_{{ $index }}"
                                class="form-control @error('items.' . $index . '.id_stok') is-invalid @enderror">
                                <option value="">-- Pilih Stok Barang --</option>
                                @foreach ($availableStoks as $stokItem)
                                    <option value="{{ $stokItem->id }}">
                                        {{ $stokItem->obat->nama_obat }} - {{ $stokItem->batch_number }} (ED:
                                        {{ \Carbon\Carbon::parse($stokItem->tanggal_kadaluarsa)->format('d-m-Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('items.' . $index . '.id_stok')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="jumlah_{{ $index }}" class="form-label">Jumlah<span
                                    style="color: rgba(255, 0, 0, 0.6)"> *</span></label>
                            <input wire:model.live="items.{{ $index }}.jumlah" type="number" min="1"
                                class="form-control @error('items.' . $index . '.jumlah') is-invalid @enderror"
                                placeholder="Masukkan Jumlah">
                            @error('items.' . $index . '.jumlah')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            @if ($item['stok_tersedia_for_item'] > 0)
                                <small class="form-text text-muted">Stok Tersedia:
                                    **{{ $item['stok_tersedia_for_item'] }}**</small>
                            @endif
                        </div>

                        <div class="col-md-2">
                            <label for="harga_{{ $index }}" class="form-label">Harga</label>
                            <input wire:model.live="items.{{ $index }}.harga" type="text" readonly
                                class="form-control" placeholder="Harga">
                        </div>

                        <div class="col-md-2">
                            <label for="total_{{ $index }}" class="form-label">Total</label>
                            <input wire:model.live="items.{{ $index }}.total" type="text" readonly
                                class="form-control" placeholder="Total">
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button wire:click="removeItem({{ $index }})" type="button"
                                class="btn btn-sm btn-danger w-100" title="Hapus Item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
                <div class="row mt-4">
                    <div class="col-md-11 text-right">
                        <h4 class="text-dark"> Total Keseluruhan: Rp {{ number_format($grand_total, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>

            </div>


            <div class="modal-footer d-flex">
                <button wire:click="store" type="button" class="btn btn-primary btn-sm"><i
                        class="fas fa-save mr-1 "></i>
                    Simpan Transaksi</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                        class="fas fa-times mr-1"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>
