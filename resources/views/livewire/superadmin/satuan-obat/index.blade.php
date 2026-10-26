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
                        <div>
                            <button wire:click="create" class="btn btn-sm btn-primary shadow" data-toggle="modal"
                                data-target="#createModal">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Data
                            </button>
                            <button wire:click="importExcel" type="button" class="btn btn-success btn-sm ml-4 shadow"
                                data-toggle="modal" data-target="#excelModal">
                                <i class="fas fa-file-import mr-1"></i>
                                Import Data
                            </button>
                        </div>


                        {{-- <div class="btn-group dropleft">
                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle shadow"
                                data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fas fa-print"></i>
                                Cetak
                            </button>
                            <div class="dropdown-menu shadow">
                                <a wire:click="exportExcel" class="dropdown-item" href="#">
                                    <i class="fas fa-file-excel bg-success"></i>
                                    Excel
                                </a>
                                <a wire:click="exportPdf" class="dropdown-item" href="#">
                                    <i class="fas fa-file-pdf bg-danger"></i>
                                    PDF
                                </a>
                            </div>
                        </div> --}}
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
                                        <th scope="col" wire:click="sortBy('nama_satuan')">
                                            <div class="d-flex justify-content-between align-items-center">
                                                Nama Satuan Barang
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
                                            <i class="fas fa-cog "></i>
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-dark border border-dark">
                                    {{-- ini mengambil datanya --}}
                                    @forelse ($satuan as $item)
                                        <tr>
                                            <td>{{ $loop->iteration + ($satuan->currentPage() - 1) * $satuan->perPage() }}
                                            </td>
                                            <td>{{ $item->nama_satuan }}</td>

                                            <td>
                                                <button wire:click="edit({{ $item->id }})"
                                                    class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#editModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button wire:click="confirm({{ $item->id }})"
                                                    class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#deleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Data Tidak Ada</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            {{ $satuan->links() }}
                        </div>
                    </div>
                </div>


        </section>
        <!-- /.content -->

        {{-- Create Modal --}}
        @include('livewire.superadmin.satuan-obat.create')
        {{-- Create Modal --}}
        {{-- Close Create Modal --}}
        @script
            <script>
                $wire.on('closeCreateModal', () => { //"closeCreateModal" ini dapatany di file create.blade.php paling atas idnya
                    $('#createModal').modal('hide');
                    Swal.fire({
                        title: "SUKSES !",
                        text: "Data Berhasil Disimpan ➕",
                        icon: "success" //swal.fire ini notif diambil dari sweetalert2 kyk adminlte3
                    }); // id pakai pagar manggilnya kalao class pakai titik 
                });
            </script>
        @endscript
        {{-- Close Create Modal --}}


        {{-- Edit Modal --}}
        @include('livewire.superadmin.satuan-obat.edit')
        {{-- Edit Modal --}}
        {{-- Close edit Modal --}}
        @script
            <script>
                $wire.on('closeEditModal', () => { //"closeeditModal" ini dapatany di file edit.blade.php paling atas idnya
                    $('#editModal').modal('hide');
                    Swal.fire({
                        title: "SUKSES !",
                        text: "Data Berhasil Diupdate 📝",
                        icon: "success" //swal.fire ini notif diambil dari sweetalert2 kyk adminlte3
                    }); // id pakai pagar manggilnya kalao class pakai titik 
                });
            </script>
        @endscript
        {{-- Close Edit Modal --}}

        {{-- Delete Modal --}}
        @include('livewire.superadmin.satuan-obat.delete')
        {{-- Delete Modal --}}
        {{-- Close delete Modal --}}
        @script
            <script>
                $wire.on('closeDeleteModal', () => { //"closeeditModal" ini dapatany di file edit.blade.php paling atas idnya
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        title: "SUKSES !",
                        text: "Data Berhasil Dihapus 🗑️",
                        icon: "success" //swal.fire ini notif diambil dari sweetalert2 kyk adminlte3
                    }); // id pakai pagar manggilnya kalao class pakai titik 
                });
            </script>
        @endscript

        {{-- excel modals --}}
        @include('livewire.superadmin.satuan-obat.excel')

        {{-- Close delete Modal --}}
    </div>
    <!-- /.content-wrapper -->
</div>
