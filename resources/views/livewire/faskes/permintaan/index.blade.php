<div>
    <div class="content-wrapper bg-white">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-dark">
                            {{ $tittle }}
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="fas fa-home mr-1"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                {{ $tittle }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="card card shadow-lg" style="background-color: #e9ffd9">
                <div>
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal!</strong> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-header">

                    <div class="card-body">
                        @if ($currentStep == 1)
                            <form wire:submit="firstStepSubmit">
                                <div class="form-group">
                                    <label for="nomor_surat">Nomor Surat (Otomatis):</label>
                                    <input type="text" wire:model.defer="nomor_surat" id="nomor_surat"
                                        class="form-control @error('nomor_surat') is-invalid @enderror" readonly disabled>
                                    @error('nomor_surat')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="perihal">Perihal:</label>
                                    <input type="text" wire:model.defer="perihal" id="perihal"
                                        class="form-control @error('perihal') is-invalid @enderror">
                                    @error('perihal')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_surat">Tanggal Surat:</label>
                                    <input type="date" wire:model.defer="tanggal_surat" id="tanggal_surat"
                                        class="form-control @error('tanggal_surat') is-invalid @enderror">
                                    @error('tanggal_surat')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Lanjut (Entri Barang)
                                    &rarr;</button>
                            </form>
                        @endif

                        @if ($currentStep == 2)
                            <form wire:submit="secondStepSubmit">
                                <h5 class="text-primary mb-3">Detail Barang yang Diminta</h5>

                                <div class="alert alert-info p-2">
                                    Surat No: <strong>{{ $nomor_surat }}</strong> | Perihal:
                                    <strong>{{ $perihal }}</strong>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">Barang / Obat (Stok Tersedia)</th>
                                                <th style="width: 20%;">Satuan</th>
                                                <th style="width: 30%;">Jumlah Diminta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($items as $index => $item)
                                                <tr>
                                                    <td>
                                                        <select wire:model.live="items.{{ $index }}.id_stok"
                                                            class="form-control form-control-sm @error('items.' . $index . '.id_stok') is-invalid @enderror">
                                                            <option value="">-- Pilih Barang (Stok Tersedia) --
                                                            </option>
                                                            @foreach ($availableStoks as $stok)
                                                                <option value="{{ $stok['id'] }}">
                                                                    {{ $stok['display'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('items.' . $index . '.id_stok')
                                                            <span
                                                                class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            value="{{ $item['nama_satuan'] ?? 'Pilih Item' }}" readonly
                                                            disabled>
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            wire:model.live="items.{{ $index }}.jumlah_diminta"
                                                            class="form-control form-control-sm @error('items.' . $index . '.jumlah_diminta') is-invalid @enderror"
                                                            min="1" max="{{ $item['max_stok'] ?? '' }}">
                                                        @error('items.' . $index . '.jumlah_diminta')
                                                            <span
                                                                class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        @if ($index > 0)
                                                            <button type="button"
                                                                wire:click="removeItem({{ $index }})"
                                                                class="btn btn-sm btn-danger">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">Klik 'Tambah Item' untuk
                                                        memulai permintaan.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" wire:click="addItem" class="btn btn-sm btn-primary mb-3">
                                    <i class="fas fa-plus"></i> Tambah Item
                                </button>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <button type="button" wire:click="back" class="btn btn-secondary">&larr; Kembali
                                        (Edit Surat)</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane"></i> Simpan & Ajukan Permintaan
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->


        {{-- Close delete Modal --}}
    </div>
    <!-- /.content-wrapper -->
</div>
