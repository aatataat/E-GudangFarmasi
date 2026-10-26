@extends('layouts.layout')
@section('dashboard', 'active')


@section('contentData')
    <div>
        <div class="content-wrapper bg-white">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="text-dark">
                                <i class="fas fa-home mr-2"></i>
                                DASHBOARD
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <i class="fas fa-home mr-1 test" style="color: #6C757D"></i>
                                    <span style="color: #6C757D">Dashboard</span>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="card card shadow-lg" style="background-color: #e9ffd9">
                    <div class="card-header">
                        {{-- Contoh: Mengaktifkan Pesan Selamat Datang --}}
                        {{--
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">Dashboard Umum</div>
                                        <div class="card-body">
                                            Selamat datang di Dashboard, {{ Auth::user()->nama }}!
                                            Ini adalah halaman ringkasan data gudang.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        --}}

                        @if (Auth::user()->role === 'Admin')
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $totalTransaksiMasuk ?? 0 }}</h3>

                                            <p>Total Transaksi Masuk</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </div>
                                        <a href="{{ route('superadmin.transaksi_masuk.index') }}"
                                            class="small-box-footer">Lihat
                                            Detail <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $totalStok ?? 0 }}</h3>

                                            <p>Total Stok Saat Ini</p>
                                        </div>
                                        <div class="icon">
                                            <i class="nav-icon fas fa-warehouse"></i>
                                        </div>
                                        <a href="{{ route('superadmin.stok.index') }}" class="small-box-footer">Lihat
                                            Inventaris <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>


                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $totalPengguna ?? 0 }}</h3>

                                            <p>Total Pengguna</p>
                                        </div>
                                        <div class="icon">
                                            <i class="nav-icon fas fa-user"></i>
                                        </div>
                                        <a href="{{ route('superadmin.user.index') }}" class="small-box-footer">Kelola
                                            Pengguna <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $totalTransaksiKeluar ?? 0 }}</h3>

                                            <p>Total Transaksi Keluar</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <a href="{{ route('superadmin.transaksi_keluar.index') }}"
                                            class="small-box-footer">Lihat Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-light">
                                        <div class="inner">
                                            <h3>{{ $totalTransaksiReturn ?? 0 }}</h3>

                                            <p>Total Transaksi Return</p>
                                        </div>
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="65" height="65"
                                                viewBox="0 0 14 14">
                                                <g fill="none" stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="1">
                                                    <path d="M9 .5L11.5 3L9 5.5" />
                                                    <path d="M.5 6V4a1 1 0 0 1 1-1h10M5 13.5L2.5 11L5 8.5" />
                                                    <path d="M13.5 8v2a1 1 0 0 1-1 1h-10" />
                                                </g>
                                            </svg>
                                        </div>
                                        <a href="{{ route('superadmin.transaksi_return.index') }}"
                                            class="small-box-footer">Lihat Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row mt-4 g-4">
                                <div class="col-lg-6 col-md-12">
                                    <div class="card card-outline card-primary shadow h-100 mb-0">
                                        <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                            <h3 class="card-title mb-0">
                                                <i class="fas fa-shopping-cart mr-1"></i>
                                                Statistik Barang Paling Banyak Keluar
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
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                @livewire('obat-chart')
                                            </div>
                                            <div class="mt-3">
                                                <small class="text-muted italic">* Data diupdate secara real-time
                                                    berdasarkan transaksi harian</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
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
                                            @livewire('chart-10mintaDashboard')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (Auth::user()->role === 'Pegawai')
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $totalTransaksiMasuk ?? 0 }}</h3>

                                            <p>Total Transaksi Masuk</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </div>
                                        <a href="{{ route('admin.transaksi_masuk.index') }}"
                                            class="small-box-footer">Lihat
                                            Detail <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $totalStok ?? 0 }}</h3>

                                            <p>Total Stok Saat Ini</p>
                                        </div>
                                        <div class="icon">
                                            <i class="nav-icon fas fa-warehouse"></i>
                                        </div>
                                        <a href="{{ route('admin.stok.index') }}" class="small-box-footer">Lihat
                                            Inventaris
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $totalTransaksiKeluar ?? 0 }}</h3>

                                            <p>Total Transaksi Keluar</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <a href="{{ route('admin.transaksi_keluar.index') }}"
                                            class="small-box-footer">Lihat Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-light">
                                        <div class="inner">
                                            <h3>{{ $totalTransaksiReturn ?? 0 }}</h3>

                                            <p>Total Transaksi Return</p>
                                        </div>
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="65" height="65"
                                                viewBox="0 0 14 14">
                                                <g fill="none" stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="1">
                                                    <path d="M9 .5L11.5 3L9 5.5" />
                                                    <path d="M.5 6V4a1 1 0 0 1 1-1h10M5 13.5L2.5 11L5 8.5" />
                                                    <path d="M13.5 8v2a1 1 0 0 1-1 1h-10" />
                                                </g>
                                            </svg>
                                        </div>
                                        <a href="{{ route('admin.transaksi_return.index') }}"
                                            class="small-box-footer">Lihat Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-4 g-4">
                                <div class="col-lg-6 col-md-12">
                                    <div class="card card-outline card-primary shadow h-100 mb-0">
                                        <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                            <h3 class="card-title mb-0">
                                                <i class="fas fa-shopping-cart mr-1"></i>
                                                Statistik Barang Paling Banyak Keluar
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
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                @livewire('obat-chart')
                                            </div>
                                            <div class="mt-3">
                                                <small class="text-muted italic">* Data diupdate secara real-time
                                                    berdasarkan transaksi harian</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
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
                                            @livewire('chart-10mintaDashboard')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (Auth::user()->role === 'Pimpinan')
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $totalTransaksiMasuk ?? 0 }}</h3>

                                            <p>Total Transaksi Masuk</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </div>
                                        <a href="{{ route('pimpinan.Laptransaksi_masuk.index') }}"
                                            class="small-box-footer">Lihat
                                            Detail <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $totalStok ?? 0 }}</h3>

                                            <p>Total Stok Saat Ini</p>
                                        </div>
                                        <div class="icon">
                                            <i class="nav-icon fas fa-warehouse"></i>
                                        </div>
                                        <a href="{{ route('pimpinan.LapStok.index') }}" class="small-box-footer">Lihat
                                            Inventaris <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $totalTransaksiKeluar ?? 0 }}</h3>

                                            <p>Total Transaksi Keluar</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <a href="{{ route('pimpinan.Laptransaksi_keluar.index') }}"
                                            class="small-box-footer">Lihat Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-light">
                                        <div class="inner">
                                            <h3>{{ $totalTransaksiReturn ?? 0 }}</h3>

                                            <p>Total Transaksi Return</p>
                                        </div>
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="65" height="65"
                                                viewBox="0 0 14 14">
                                                <g fill="none" stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="1">
                                                    <path d="M9 .5L11.5 3L9 5.5" />
                                                    <path d="M.5 6V4a1 1 0 0 1 1-1h10M5 13.5L2.5 11L5 8.5" />
                                                    <path d="M13.5 8v2a1 1 0 0 1-1 1h-10" />
                                                </g>
                                            </svg>
                                        </div>
                                        <a href="{{ route('pimpinan.Laptransaksi_return.index') }}"
                                            class="small-box-footer">Lihat Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-4 g-4">
                                <div class="col-lg-6 col-md-12">
                                    <div class="card card-outline card-primary shadow h-100 mb-0">
                                        <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                            <h3 class="card-title mb-0">
                                                <i class="fas fa-shopping-cart mr-1"></i>
                                                Statistik Barang Paling Banyak Keluar
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
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                @livewire('obat-chart')
                                            </div>
                                            <div class="mt-3">
                                                <small class="text-muted italic">* Data diupdate secara real-time
                                                    berdasarkan transaksi harian</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
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
                                            @livewire('chart-10mintaDashboard')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (Auth::user()->role === 'Faskes')
                            <div class="row">

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $permintaanDiajukan ?? 0 }}</h3>

                                            <p>Total Permintaan yang Diajukan</p>
                                        </div>
                                        <div class="icon">
                                            <i class="nav-icon fas fa-hourglass-half"></i>
                                        </div>
                                        <a href="{{ route('admin.stok.index') }}" class="small-box-footer">Lihat
                                            Inventaris <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>


                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $permintaanDitolak ?? 0 }}</h3>

                                            <p>Total Permintaan Ditolak</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <a href="{{ route('admin.transaksi_keluar.index') }}"
                                            class="small-box-footer">Lihat Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $permintaanDiterima ?? 0 }}</h3>

                                            <p>Total Permintaan Diterima</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <a href="{{ route('faskes.riwayat_permintaan.index') }}"
                                            class="small-box-footer">Lihat
                                            Detail <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                            </div>
                        @endif
                    </div>
            </section>
        </div>
    </div>
@endsection
