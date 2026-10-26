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
                <div class="card-header">
                    <div class="d-flex justify-content-between">



                        <div class="btn-group dropright">
                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle shadow"
                                data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fas fa-print"></i>
                                Cetak
                            </button>
                            <div class="dropdown-menu shadow">
                                {{-- <a wire:click="exportExcel" class="dropdown-item" href="#">
                                    <i class="fas fa-file-excel bg-success"></i>
                                    Excel
                                </a> --}}
                                <a wire:click="exportPdf" class="dropdown-item" href="#">
                                    <i class="fas fa-file-pdf bg-danger"></i>
                                    Cetak PDF
                                </a>
                            </div>
                        </div>
                    </div>
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
                            <div class="col-5 d-flex ">
                                <span class="mr-2 mt-2 text-dark">Cari: </span>
                                <input wire:model.live.debounce.500ms="search" etype="text"
                                    class="form-control shadow-lg" placeholder="🔍Pencarian...">
                            </div>
                        </div>

                        <div class="table-responsive shadow-lg">
                            <table class="table table-hover table-striped table-bordered no-wrap">
                                <thead class="  text-dark" style="background: #8bb993">
                                    <tr>
                                        <th>No</th>
                                        <th scope="col" wire:click="sortBy('nama_obat')">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Nama Barang
                                                <span>
                                                    {{-- Ikon panah atas (ascending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'nama_obat' && $direction == 'asc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M10.22 15.97L9 17.19V5c0-.41-.34-.75-.75-.75s-.75.34-.75.75v12.19l-1.22-1.22c-.29-.29-.77-.29-1.06 0s-.29.77 0 1.06l2.5 2.5a.78.78 0 0 0 .53.22a.78.78 0 0 0 .53-.22l2.5-2.5c.29-.29.29-.77 0-1.06s-.77-.29-1.06 0M14 11.21c.39.14.82-.06.96-.45l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77c-.14.39.06.82.45.96Zm2.73-2.73h-.96l.48-1.34zm1.94 4.98c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35Z" />
                                                    </svg>

                                                    {{-- Ikon panah bawah (descending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'nama_obat' && $direction == 'desc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>

                                        <th scope="col" wire:click="sortBy('nama_kategori')">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Kategori Barang
                                                <span>
                                                    {{-- Ikon panah atas (ascending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'nama_kategori' && $direction == 'asc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M10.22 15.97L9 17.19V5c0-.41-.34-.75-.75-.75s-.75.34-.75.75v12.19l-1.22-1.22c-.29-.29-.77-.29-1.06 0s-.29.77 0 1.06l2.5 2.5a.78.78 0 0 0 .53.22a.78.78 0 0 0 .53-.22l2.5-2.5c.29-.29.29-.77 0-1.06s-.77-.29-1.06 0M14 11.21c.39.14.82-.06.96-.45l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77c-.14.39.06.82.45.96Zm2.73-2.73h-.96l.48-1.34zm1.94 4.98c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35Z" />
                                                    </svg>

                                                    {{-- Ikon panah bawah (descending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'nama_kategori' && $direction == 'desc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col" wire:click="sortBy('nama_satuan')">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Satuan Barang
                                                <span>
                                                    {{-- Ikon panah atas (ascending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'nama_satuan' && $direction == 'asc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M10.22 15.97L9 17.19V5c0-.41-.34-.75-.75-.75s-.75.34-.75.75v12.19l-1.22-1.22c-.29-.29-.77-.29-1.06 0s-.29.77 0 1.06l2.5 2.5a.78.78 0 0 0 .53.22a.78.78 0 0 0 .53-.22l2.5-2.5c.29-.29.29-.77 0-1.06s-.77-.29-1.06 0M14 11.21c.39.14.82-.06.96-.45l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77c-.14.39.06.82.45.96Zm2.73-2.73h-.96l.48-1.34zm1.94 4.98c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35Z" />
                                                    </svg>

                                                    {{-- Ikon panah bawah (descending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'nama_satuan' && $direction == 'desc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th>
                                            Jumlah Diminta
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="fw-bold text-secondary">
                                                {{ $item->nama_obat ?? '-' }}
                                            </td>
                                            <td>
                                                <span>
                                                    {{ $item->nama_kategori ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span>
                                                    {{ $item->nama_satuan ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($item->total_diminta, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Data tidak
                                                ditemukan atau data permintaan kosong.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                            </table>
                        </div>
                        {{-- <div class="card-footer card-pagination-bootstrap">
                            {{ $items->links() }}
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                                    <div class="card card-outline card-success shadow h-100 mb-0">
                                        <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                            <h3 class="card-title mb-0">
                                                <i class="fas fa-chart-bar mr-1"></i>
                                                Grafik 10 Barang Paling Banyak Diminta
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" onclick="location.reload()">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @livewire('chart10minta')
                                        </div>
                                    </div>
                                </div>

        </section>

    </div>
    
    <!-- /.content-wrapper -->
</div>
