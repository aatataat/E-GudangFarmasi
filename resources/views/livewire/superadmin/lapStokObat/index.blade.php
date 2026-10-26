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
                        <div class="btn-group dropright ">
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
                                    PDF
                                </a>
                            </div>
                        </div>
                        <button wire:click="resetToAwal"
                            wire:confirm="Apakah Anda yakin ingin mereset semua transaksi? Aksi ini akan menghapus riwayat masuk/keluar dan menjadikan stok saat ini sebagai stok awal periode baru."
                            class="btn btn-danger">
                            <i class="fas fa-sync"></i> Reset ke Stok Awal
                        </button>
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
                                <input wire:model.live.debounce.500ms="search" type="text"
                                    class="form-control shadow-lg" placeholder="🔍Pencarian...">
                            </div>
                        </div>

                        <div class="table-responsive shadow-lg border-dark">
                            <table class="table table-hover table-striped table-bordered no-wrap">
                                <thead class="  text-dark" style="background: #8bb993">
                                    <tr>
                                        <th rowspan="2" class="text-center align-middle">No</th>
                                        <th rowspan="2" scope="col" wire:click="sortBy('kategori')"
                                            class="text-center align-middle">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Kategori
                                                <span>
                                                    {{-- Ikon panah atas (ascending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'kategori' && $direction == 'asc') text-white
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
                @if ($sort == 'kategori' && $direction == 'desc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s-.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th rowspan="2" scope="col" wire:click="sortBy('nama_obat')"
                                            class="text-center align-middle">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Nama Obat
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
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s-.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th rowspan="2" scope="col" wire:click="sortBy('satuan')"
                                            class="text-center align-middle">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Satuan
                                                <span>
                                                    {{-- Ikon panah atas (ascending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'satuan' && $direction == 'asc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M10.22 15.97L9 17.19V5c0-.41-.34-.75-.75-.75s-.75.34-.75.75v12.19l-1.22-1.22c-.29-.29-.77-.29-1.06 0s-.29.77 0 1.06l2.5 2.5a.78.78 0 0 0 .53.22a.78.78 0 0 0 .53-.22l2.5-2.5c.29-.29.29-.77 0-1.06s-.77-.29-1.06 0M14 11.21c.39.14.82-.06.96-.45l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77c-.14.39.06.82.45.96Zm2.73-2.73h-.96l.48-1.34zm1.94 4.98c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35Z" />
                                                    </svg>

                                                    {{-- Ikon panah bawah (descending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'satuan' && $direction == 'desc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s-.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th rowspan="2" scope="col" wire:click="sortBy('batch_number')"
                                            class="text-center align-middle">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Batch Number
                                                <span>
                                                    {{-- Ikon panah atas (ascending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'batch_number' && $direction == 'asc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M10.22 15.97L9 17.19V5c0-.41-.34-.75-.75-.75s-.75.34-.75.75v12.19l-1.22-1.22c-.29-.29-.77-.29-1.06 0s-.29.77 0 1.06l2.5 2.5a.78.78 0 0 0 .53.22a.78.78 0 0 0 .53-.22l2.5-2.5c.29-.29.29-.77 0-1.06s-.77-.29-1.06 0M14 11.21c.39.14.82-.06.96-.45l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77c-.14.39.06.82.45.96Zm2.73-2.73h-.96l.48-1.34zm1.94 4.98c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35Z" />
                                                    </svg>

                                                    {{-- Ikon panah bawah (descending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'batch_number' && $direction == 'desc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s-.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th rowspan="2" scope="col" wire:click="sortBy('tanggal_kadaluarsa')"
                                            class="text-center align-middle">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Tanggal Kedaluwarsa
                                                <span>
                                                    {{-- Ikon panah atas (ascending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'tanggal_kadaluarsa' && $direction == 'asc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M10.22 15.97L9 17.19V5c0-.41-.34-.75-.75-.75s-.75.34-.75.75v12.19l-1.22-1.22c-.29-.29-.77-.29-1.06 0s-.29.77 0 1.06l2.5 2.5a.78.78 0 0 0 .53.22a.78.78 0 0 0 .53-.22l2.5-2.5c.29-.29.29-.77 0-1.06s-.77-.29-1.06 0M14 11.21c.39.14.82-.06.96-.45l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77c-.14.39.06.82.45.96Zm2.73-2.73h-.96l.48-1.34zm1.94 4.98c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35Z" />
                                                    </svg>

                                                    {{-- Ikon panah bawah (descending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'tanggal_kadaluarsa' && $direction == 'desc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s-.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th colspan="3" class="text-center">
                                            Persediaan Awal
                                        </th>


                                        <th colspan="3" class="text-center"
                                            style="background-color: rgb(112, 117, 250)">
                                            Penerimaan
                                        </th>
                                        <th colspan="3" class="text-center"
                                            style="background-color: rgb(253, 115, 115)">
                                            Pengeluaran
                                        </th>
                                        <th colspan="3" class="text-center">
                                            Total Persediaan
                                        </th>

                                        <th rowspan="2" scope="col" wire:click="sortBy('sumber_anggaran')"
                                            class="text-center align-middle">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Sumber Anggaran
                                                <span>
                                                    {{-- Ikon panah atas (ascending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'sumber_anggaran' && $direction == 'asc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M10.22 15.97L9 17.19V5c0-.41-.34-.75-.75-.75s-.75.34-.75.75v12.19l-1.22-1.22c-.29-.29-.77-.29-1.06 0s-.29.77 0 1.06l2.5 2.5a.78.78 0 0 0 .53.22a.78.78 0 0 0 .53-.22l2.5-2.5c.29-.29.29-.77 0-1.06s-.77-.29-1.06 0M14 11.21c.39.14.82-.06.96-.45l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77c-.14.39.06.82.45.96Zm2.73-2.73h-.96l.48-1.34zm1.94 4.98c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35Z" />
                                                    </svg>

                                                    {{-- Ikon panah bawah (descending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'sumber_anggaran' && $direction == 'desc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s-.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>

                                        <th rowspan="2" scope="col" wire:click="sortBy('supplier')"
                                            class="text-center align-middle">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Penyedia
                                                <span>
                                                    {{-- Ikon panah atas (ascending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'supplier' && $direction == 'asc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M10.22 15.97L9 17.19V5c0-.41-.34-.75-.75-.75s-.75.34-.75.75v12.19l-1.22-1.22c-.29-.29-.77-.29-1.06 0s-.29.77 0 1.06l2.5 2.5a.78.78 0 0 0 .53.22a.78.78 0 0 0 .53-.22l2.5-2.5c.29-.29.29-.77 0-1.06s-.77-.29-1.06 0M14 11.21c.39.14.82-.06.96-.45l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77c-.14.39.06.82.45.96Zm2.73-2.73h-.96l.48-1.34zm1.94 4.98c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35Z" />
                                                    </svg>

                                                    {{-- Ikon panah bawah (descending) --}}
                                                    <svg style="cursor: pointer;"
                                                        class="w-4 h-4
                @if ($sort == 'supplier' && $direction == 'desc') text-white
                @else
                    text-secondary @endif"
                                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 24 24">
                                                        <path fill="currentColor"
                                                            d="M8.78 4.47a.8.8 0 0 0-.24-.16a.7.7 0 0 0-.57 0c-.09.04-.17.09-.24.16l-2.5 2.5c-.29.29-.29.77 0 1.06s.77.29 1.06 0l1.22-1.22V19c0 .41.34.75.75.75s.75-.34.75-.75V6.81l1.22 1.22c.15.15.34.22.53.22s.38-.07.53-.22c.29-.29.29-.77 0-1.06l-2.5-2.5Zm10.18 13.78l-1.71-4.79c-.17-.43-.56-.71-1-.71s-.83.28-1 .73l-1.7 4.77a.75.75 0 1 0 1.41.51l.28-.78h2.03l.28.78c.11.31.4.5.71.5c.08 0 .17-.01.25-.04a.75.75 0 0 0 .45-.96Zm-3.19-1.77l.48-1.34l.48 1.34zm-1.94-5.94c.19.44.59.71 1.05.71h3.13c.41 0 .75-.34.75-.75s-.34-.75-.75-.75h-2.39l2.82-2.93c.34-.36.44-.89.24-1.35c-.19-.44-.59-.71-1.05-.71h-3.11c-.41 0-.75.34-.75.75s-.34.75.75.75h2.39l-2.83 2.95c-.34.36-.43.88-.24 1.34Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Jumlah Satuan</th>
                                        <th class="text-center">Nilai Satuan </th>
                                        <th class="text-center" style="padding-left: 70px; padding-right: 70px;">Total
                                        </th>
                                        <th class="text-center">Jumlah Satuan</th>
                                        <th class="text-center">Nilai Satuan </th>
                                        <th class="text-center " style="padding-left: 70px; padding-right: 70px;">
                                            Total</th>
                                        <th class="text-center">Jumlah Satuan</th>
                                        <th class="text-center">Nilai Satuan </th>
                                        <th class="text-center" style="padding-left: 70px; padding-right: 70px;">Total
                                        </th>
                                        <th class="text-center">Jumlah Satuan</th>
                                        <th class="text-center">Nilai Satuan </th>
                                        <th class="text-center" style="padding-left: 70px; padding-right: 70px;">Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark border border-dark">
                                    {{-- ini mengambil datanya --}}
                                    @forelse ($stoks as $item)
                                        {{-- Mengubah $puskesmas menjadi $stoks --}}
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration + ($stoks->currentPage() - 1) * $stoks->perPage() }}
                                            </td>
                                            <td>{{ $item->obat->kategori->nama_kategori }}</td>
                                            <td>{{ $item->obat->nama_obat }}</td>
                                            <td class="text-center">{{ $item->obat->satuan->nama_satuan }}</td>
                                            <td class="text-center">{{ $item->batch_number }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->Format('d F Y') }}
                                            </td>
                                            {{-- 1. PERSEDIAAN AWAL --}}
                                            {{-- Jumlah Satuan Awal --}}
                                            <td class="text-center">
                                                {{ number_format($item->jumlah + ($item->jumlah_keluar ?? 0) - ($item->jumlah_masuk ?? 0), 0, ',', '.') }}
                                            </td>
                                            {{-- Nilai Satuan Awal (Harga Beli) --}}
                                            <td style="text-align: left;">
                                                <span style="float: left;">Rp</span>
                                                <span
                                                    style="float: right;">{{ number_format($item->harga, 0, ',', '.') }}</span>
                                            </td>
                                            {{-- Total Nilai Awal --}}
                                            <td style="text-align: left;">
                                                <span style="float: left;">Rp</span>
                                                <span style="float: right;">
                                                    {{ number_format(($item->jumlah + ($item->jumlah_keluar ?? 0) - ($item->jumlah_masuk ?? 0)) * $item->harga, 0, ',', '.') }}
                                                </span>
                                            </td>

                                            {{-- 2. PENERIMAAN --}}
                                            <td class="text-center">
                                                {{ $item->jumlah_masuk == 0 ? '-' : number_format($item->jumlah_masuk, 0, ',', '.') }}
                                            </td>
                                            <td style="text-align: left;">
                                                <span style="float: left;">Rp</span>
                                                <span
                                                    style="float: right;">{{ $item->harga_masuk == 0 ? '-' : number_format($item->harga_masuk, 0, ',', '.') }}</span>
                                            </td>
                                            <td style="text-align: left;">
                                                <span style="float: left;">Rp</span>
                                                <span
                                                    style="float: right;">{{ $item->total_masuk == 0 ? '-' : number_format($item->total_masuk, 0, ',', '.') }}</span>
                                            </td>

                                            {{-- 3. PENGELUARAN --}}
                                            <td class="text-center">
                                                {{ $item->jumlah_keluar == 0 ? '-' : number_format($item->jumlah_keluar, 0, ',', '.') }}
                                            </td>
                                            <td style="text-align: left;">
                                                <span style="float: left;">Rp</span>
                                                <span
                                                    style="float: right;">{{ $item->harga_keluar == 0 ? '-' : number_format($item->harga_keluar, 0, ',', '.') }}</span>
                                            </td>
                                            <td style="text-align: left;">
                                                <span style="float: left;">Rp</span>
                                                <span
                                                    style="float: right;">{{ $item->total_keluar == 0 ? '-' : number_format($item->total_keluar, 0, ',', '.') }}</span>
                                            </td>

                                            {{-- 4. TOTAL PERSEDIAAN (STOK AKHIR) --}}
                                            {{-- Jumlah Satuan Akhir --}}
                                            <td class="text-center">
                                                {{ number_format($item->jumlah, 0, ',', '.') }}
                                            </td>
                                            {{-- Nilai Satuan Akhir --}}
                                            <td style="text-align: left;">
                                                <span style="float: left;">Rp</span>
                                                <span
                                                    style="float: right;">{{ number_format($item->harga, 0, ',', '.') }}</span>
                                            </td>
                                            {{-- Total Nilai Akhir (Harus sama dengan kolom 'total' di database) --}}
                                            <td style="text-align: left;">
                                                <span style="float: left;">Rp</span>
                                                <span
                                                    style="float: right;">{{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}</span>
                                            </td>
                                            {{-- <td>{{ $item->rak->kode_rak ?? 'N/A' }}</td> --}}
                                            <td>
                                                {{ $item->sumberAnggaran->nama_anggaran ?? 'N/A' }}</td>
                                            <td>{{ $item->supplier->nama_supplier ?? 'N/A' }}</td>


                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="20" class="text-center">Data Tidak Ada</td>
                                            {{-- Menyesuaikan colspan --}}
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            {{ $stoks->links() }} {{-- Mengubah $puskesmas->links() menjadi $stoks->links() --}}
                        </div>
                    </div>
                </div>


        </section>
        @script
            <script>
                $wire.on('closeResetModal', () => { //"closeeditModal" ini dapatany di file edit.blade.php paling atas idnya
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        title: "SUKSES !",
                        text: "Data Berhasil Direset 🔄",
                        icon: "success" //swal.fire ini notif diambil dari sweetalert2 kyk adminlte3
                    }); // id pakai pagar manggilnya kalao class pakai titik 
                });
            </script>
        @endscript
    </div>
    <!-- /.content-wrapper -->
</div>
