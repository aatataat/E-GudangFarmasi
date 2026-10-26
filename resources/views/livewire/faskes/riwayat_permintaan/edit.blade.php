<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning ">
                <h5 class="modal-title text-bold" id="editModalLabel">Edit Permintaan Logistik</h5>
                <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-dark">
                @if (session()->has('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger p-2">{{ session('error') }}</div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Nomor Permintaan:</label>
                            <input type="text" wire:model="nomor_surat" class="form-control form-control-sm @error('nomor_surat') is-invalid @enderror">
                            @error('nomor_surat') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <p><strong>Faskes Pengaju:</strong> {{ $permintaanDetail->faskes->nama_faskes ?? 'N/A' }}</p>
                        <p><strong>Status:</strong> <span class="badge badge-warning text-dark">DIAJUKAN</span></p>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Perihal:</label>
                            <input type="text" wire:model="perihal" class="form-control form-control-sm @error('perihal') is-invalid @enderror">
                            @error('perihal') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tanggal Permintaan:</label>
                            <input type="date" wire:model="tanggal_surat" class="form-control form-control-sm @error('tanggal_surat') is-invalid @enderror">
                            @error('tanggal_surat') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="font-weight-bold m-0">Item yang Diminta:</h5>
                    <button type="button" class="btn btn-sm btn-success" wire:click="$set('showSearchStok', true)">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </button>
                </div>

                @if (isset($showSearchStok) && $showSearchStok)
                    <div class="card card-body bg-light mb-3">
                        <h6>Pilih Barang Baru:</h6>
                        <div class="input-group">
                            <select class="form-control" wire:model="selectedStokId">
                                <option value="">-- Pilih Barang (Stok Tersedia) --
                                </option>
                                @foreach ($availableStoks as $stok)
                                    <option value="{{ $stok['id'] }}">
                                        {{ $stok['display'] }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-sm" wire:click="tambahItemKeDaftar">Tambah</button>
                                <button class="btn btn-secondary btn-sm"
                                    wire:click="$set('showSearchStok', false)">Batal</button>
                            </div>
                        </div>
                        @error('selectedStokId')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-sm table-bordered text-dark">
                        <thead class="bg-light">
                            <tr>
                                <th width="50px">#</th>
                                <th>Nama Barang/Obat</th>
                                <th class="text-center" width="200px">Jumlah Diminta</th>
                                <th class="text-center" width="100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($itemsDetail as $i => $item)
                                <tr>
                                    <td class="align-middle text-center">{{ $i + 1 }}</td>
                                    <td class="align-middle font-weight-bold">{{ $item['nama_obat'] }}</td>
                                    <td class="text-center">
                                        <input type="number"
                                            wire:model="itemsDetail.{{ $i }}.jumlah_diminta"
                                            class="form-control form-control-sm text-center mx-auto @error('itemsDetail.'.$i.'.jumlah_diminta') is-invalid @enderror"
                                            style="width: 100px; background-color: #ffeeba; border: 1px solid #ffc107;">
                                        @error('itemsDetail.'.$i.'.jumlah_diminta')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-center">
                                        <button title="Hapus Barang" wire:click="removeItem({{ $i }})"
                                            class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada item yang dipilih.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" wire:click="updatePermintaan">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
