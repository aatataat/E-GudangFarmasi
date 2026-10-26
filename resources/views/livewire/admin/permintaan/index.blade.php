<div>
    <div class="content-wrapper bg-white">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-dark">
                            {{ $title }}
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
                                {{ $title }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif (session()->has('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @elseif (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <section class="content">
            <div class="card card shadow-lg" style="background-color: #e9ffd9">
                <div class="card-header">
                    <hr>

                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between">
                            <div class="col-2.5 d-flex">
                                <span class="mr-2 mt-2 d-flex text-dark">Show</span>
                                <select wire:model.change="paginate" class="form-control shadow-lg">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                &emsp;<p class="text-dark mt-2">Entries</p>
                            </div>

                            <div class="col-3">
                                <span class="d-block text-dark small mb-1">Fasilitas Kesehatan</span>
                                <select wire:model.live="filterFaskes" id="filterFaskes"
                                    class="form-control shadow-lg form-control-sm">
                                    <option value="">Semua Faskes</option>
                                    @foreach ($allFaskes as $faskes)
                                        <option value="{{ $faskes->id }}">{{ $faskes->nama_faskes }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-2.5">
                                <span class="d-block text-dark small mb-1">Status</span>
                                <select wire:model.live="filterStatus" class="form-control shadow-lg form-control-sm">
                                    <option value="">-- Semua Status --</option>
                                    @foreach ($availableStatuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-2.5">
                                <span class="d-block text-dark small mb-1">Tanggal Permintaan</span>
                                <input type="date" wire:model.live="filterTanggal"
                                    class="form-control shadow-lg form-control-sm">
                            </div>

                            <div class="col-3 d-flex ">
                                <span class="mr-2 mt-2 text-dark">Cari: </span>
                                <input wire:model.live.debounce.500ms="search" type="text"
                                    class="form-control shadow-lg" placeholder="🔍Pencarian...">
                            </div>
                        </div>

                        <div class="table-responsive shadow-lg">
                            <table class="table table-hover table-striped table-bordered no-wrap">
                                <thead class="text-dark" style="background: #8bb993">
                                    <tr>
                                        <th wire:click="sortBy('id')" class="cursor-pointer">No.
                                            @include('livewire.sort-icon', ['field' => 'id'])</th>
                                        <th wire:click="sortBy('nomor_surat')" class="cursor-pointer">Nomor Permintaan
                                            @include('livewire.sort-icon', ['field' => 'nomor_surat'])</th>
                                        <th>Faskes Pengaju</th>
                                        <th wire:click="sortBy('tanggal_surat')" class="cursor-pointer">Tgl. Permintaan
                                            @include('livewire.sort-icon', ['field' => 'tanggal_surat'])</th>
                                        <th wire:click="sortBy('perihal')" class="cursor-pointer">Perihal
                                            @include('livewire.sort-icon', ['field' => 'perihal'])</th>
                                        <th wire:click="sortBy('status')" class="cursor-pointer">Status
                                            @include('livewire.sort-icon', ['field' => 'status'])</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark border border-dark">
                                    @forelse ($permintaans as $index => $permintaan)
                                        <tr wire:key="permintaan-{{ $permintaan->id }}">
                                            <td>{{ $index + $permintaans->firstItem() }}</td>
                                            <td>{{ $permintaan->nomor_surat }}</td>
                                            <td>{{ $permintaan->faskes->nama_faskes ?? 'Faskes Tidak Ditemukan' }}</td>
                                            <td>{{ $permintaan->tanggal_surat->format('d F Y') }}</td>
                                            <td>{{ $permintaan->perihal }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = match ($permintaan->status) {
                                                        'DIAJUKAN' => 'badge-warning',
                                                        'DITERIMA' => 'badge-primary',
                                                        'DIPROSES' => 'badge-info',
                                                        'DITOLAK' => 'badge-danger',
                                                        'SELESAI' => 'badge-success',
                                                        default => 'badge-secondary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge {{ $badgeClass }}">{{ $permintaan->status }}</span>
                                            </td>
                                            <td>
                                                <button type="button" wire:click="showDetail({{ $permintaan->id }})"
                                                    class="btn btn-sm btn-info">
                                                    Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                Tidak ada permintaan logistik dengan status {{ $filterStatus }}.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            {{ $permintaans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div wire:ignore.self class="modal fade" id="detailModal" tabindex="-1" role="dialog"
            aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="detailModalLabel">Detail Permintaan Logistik</h5>
                        {{-- SESUAI INSTRUKSI: Tombol close tidak dirubah --}}
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    @if ($permintaanDetail)
                        @php
                            $badgeClassDetail = match ($permintaanDetail->status) {
                                'DIAJUKAN' => 'badge-warning',
                                'DITERIMA' => 'badge-primary',
                                'DIPROSES' => 'badge-info',
                                'DITOLAK' => 'badge-danger',
                                'SELESAI' => 'badge-success',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Nomor Permintaan:</strong> {{ $permintaanDetail->nomor_surat }}</p>
                                    <p><strong>Faskes Pengaju:</strong>
                                        {{ $permintaanDetail->faskes->nama_faskes ?? 'N/A' }}</p>
                                    <p><strong>Status:</strong> <span
                                            class="badge {{ $badgeClassDetail }}">{{ $permintaanDetail->status }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Perihal:</strong> {{ $permintaanDetail->perihal }}</p>
                                    <p><strong>Tanggal Permintaan:</strong>
                                        {{ $permintaanDetail->tanggal_surat->format('d F Y') }}</p>
                                </div>
                            </div>

                            <hr>

                            <h5>Item yang Diminta & Diberikan:</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th width="50px">No</th>
                                            <th>Nama Barang/Obat</th>
                                            <th>Stok Gudang</th>
                                            <th width="150px">Jumlah Diminta</th>
                                            @if ($permintaanDetail && $permintaanDetail->status !== 'DITOLAK')
                                                <th class="bg-warning text-dark">Jumlah Diberi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($editableItems as $index => $item)
                                            <tr wire:key="item-edit-{{ $index }}">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $item['nama_obat'] }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge {{ $item['total_stok_gudang'] < $item['jumlah_diminta'] ? 'badge-danger' : 'badge-success' }}">
                                                        {{ number_format($item['total_stok_gudang'] ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{-- TAMPILAN JUMLAH DIMINTA (Read Only) --}}
                                                    <span
                                                        class="fw-bold">{{ number_format($item['jumlah_diminta'], 0, ',', '.') }}</span>
                                                </td>

                                                {{-- Cek apakah statusnya BUKAN ditolak --}}
                                                @if ($permintaanDetail && $permintaanDetail->status !== 'DITOLAK')
                                                    <td class="text-center">
                                                        {{-- Ganti kondisi agar DIAJUKAN dan DITERIMA bisa mengedit input --}}
                                                        @if (in_array($permintaanDetail->status, ['DIAJUKAN', 'DITERIMA']))
                                                            <input type="number"
                                                                wire:model.live="editableItems.{{ $index }}.jumlah_diberi"
                                                                class="form-control form-control-sm shadow-sm text-center border-warning"
                                                                min="0">
                                                            @error('editableItems.' . $index . '.jumlah_diberi')
                                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                                            @enderror

                                                            @if ((int)$editableItems[$index]['jumlah_diberi'] > (int)$item['total_stok_gudang'] + (int)$item['jumlah_diberi'])
                                                                <small class="text-danger d-block">Melebihi
                                                                    stok!</small>
                                                            @endif
                                                        @elseif ($permintaanDetail->status === 'SELESAI')
                                                            <span class="badge bg-warning text-dark">
                                                                {{ number_format($item['jumlah_diberi'], 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                @else
                                                @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada item detail.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-between">
                            <div class="w-100 d-flex justify-content-between">
                                <div>
                                    @if (in_array($permintaanDetail->status, ['DIAJUKAN', 'DITERIMA']))
                                        <button wire:click="terimaPermintaan({{ $permintaanDetail->id }})"
                                            class="btn btn-success me-2"
                                            onclick="confirm('Simpan perubahan jumlah dan perbarui verifikasi?') || event.stopImmediatePropagation()">
                                            {{ $permintaanDetail->status === 'DITERIMA' ? 'Update Verifikasi' : 'Simpan & Terima' }}
                                        </button>

                                        @if ($permintaanDetail->status === 'DIAJUKAN')
                                            <button wire:click="tolakPermintaan({{ $permintaanDetail->id }})"
                                                class="btn btn-danger"
                                                onclick="confirm('Yakin ingin MENOLAK permintaan ini?') || event.stopImmediatePropagation()">
                                                Tolak
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('showDetailModal', event => {
            $('#detailModal').modal('show');
        });
        window.addEventListener('closeDetailModal', event => {
            $('#detailModal').modal('hide');
        });
    </script>
</div>
