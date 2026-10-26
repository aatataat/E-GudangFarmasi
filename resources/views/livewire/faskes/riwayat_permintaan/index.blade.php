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

        <div>
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
        </div>

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
                            <div class="col-md-3 d-flex align-items-center">
                                <span class="d-block text-dark small mb-1">Filter Status</span>
                                <select wire:model.live="filterStatus" class="form-control form-control-sm">
                                    <option value="">-- Semua Status --</option>
                                    @foreach ($availableStatuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-5 d-flex ">
                                <span class="mr-2 mt-2 text-dark">Cari: </span>
                                <input wire:model.live.debounce.500ms="search" etype="text"
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
                                    {{-- ini mengambil datanya --}}
                                    @forelse ($permintaans as $index => $permintaan)
                                        <tr>
                                            <td>{{ $index + $permintaans->firstItem() }}</td>
                                            <td>{{ $permintaan->nomor_surat }}</td>
                                            <td>{{ $permintaan->faskes->nama_faskes ?? 'Faskes Tidak Ditemukan' }}</td>
                                            <td>{{ $permintaan->tanggal_surat->format('d-m-Y') }}</td>
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
                                                @if ($status)
                                                @endif
                                                <button wire:click="showDetail({{ $permintaan->id }})"
                                                    class="btn btn-sm btn-info" data-toggle="modal"
                                                    data-target="#detailModal">
                                                    Detail
                                                </button>
                                                @if ($permintaan->status === 'DIAJUKAN')
                                                    <button wire:click="editPermintaan({{ $permintaan->id }})"
                                                        class="btn btn-sm btn-warning" data-toggle="modal"
                                                        data-target="#editModal">
                                                        Edit
                                                    </button>

                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirm('Apakah Anda yakin ingin menghapus data permintaan ini?') || event.stopImmediatePropagation()"
                                                        wire:click="hapusPermintaan({{ $permintaan->id }})">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                @else
                                                    <span class="badge badge-light text-muted"><i
                                                            class="fas fa-lock"></i> Selesai</span>
                                                @endif


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


        </section>

    </div>


    @include('livewire.faskes.riwayat_permintaan.detail')

    @include('livewire.faskes.riwayat_permintaan.edit')
    <script>
        window.addEventListener('showDetailModal', event => {
            $('#detailModal').modal('show');
        });
        window.addEventListener('showEditModal', event => {
            $('#editModal').modal('show');
        });
        window.addEventListener('hideEditModal', event => {
            $('#editModal').modal('hide');
        });
    </script>

    {{-- Close delete Modal --}}

</div>
