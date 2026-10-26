<aside class="main-sidebar sidebar-dark-olive elevation-4 text-white" style="background-color: #3E5F44">
    <!-- Brand Logo -->

    <a href="{{ route('homepage') }}" class="brand-link">
        <div class="d-flex align-items-center">
            {{-- Logo --}}
            <img src="{{ asset('img\logo.png') }}" alt="Logo Dinas Kesehatan"
                style="height: 80px; width: auto; object-fit: contain; margin-right: 5px">

            <div class="brand-text font-weight-white" style="margin-top: 5px">
                <span class="d-block" style="line-height:1.1;">E-Gudang Farmasi</span>
                <span class="d-block" style="line-height:1.1;">Dinas Kesehatan</span>
                <span class="d-block" style="line-height:1.2;">Kota Banjarmasin</span>
            </div>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- SidebarSearch Form -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a wire:navigate href="{{ route('dashboard') }}" class="nav-link @yield('dashboard')">
                        <i class="nav-icon fas fa-home "></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if (Auth::user()->role === 'Admin' || Auth::user()->role === 'Pegawai' || Auth::user()->role === 'Pimpinan')
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('notifikasi.index') }}" class="nav-link @yield('menuSuperadminNotifikasi')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M374.09 101c-.11.23-.21.46-.31.7c-.34.75-.67 1.5-1 2.26l-.36.9c-.27.71-.54 1.42-.79 2.14c-.12.35-.24.7-.35 1c-.23.68-.44 1.37-.64 2.07l-.33 1.15q-.27 1-.51 2c-.1.41-.2.82-.28 1.23c-.15.67-.28 1.36-.4 2c-.08.42-.16.84-.23 1.27c-.11.69-.2 1.4-.29 2.1c-.05.42-.11.83-.16 1.25c-.08.77-.13 1.54-.19 2.31c0 .36-.06.72-.08 1.09c-.06 1.13-.09 2.27-.09 3.41c0 1 0 2 .07 2.94v.62c.05.95.12 1.89.21 2.83l.06.46c.09.87.2 1.72.32 2.57c0 .15 0 .31.07.46c.14.92.31 1.84.49 2.75l.12.59c.2.92.4 1.84.64 2.75c.23.92.5 1.82.77 2.71c.06.19.12.38.17.57c.28.88.57 1.74.88 2.59c.05.15.11.29.16.43c.29.78.6 1.55.92 2.32c.05.14.11.28.17.42c.35.83.73 1.65 1.11 2.47l.27.53c.4.82.81 1.64 1.24 2.44a64.2 64.2 0 0 0 29.56 27.63l.37.17c1.78.8 3.59 1.53 5.45 2.17l.95.32l1.5.47c.45.14.9.26 1.36.39l1.92.5c.57.14 1.14.27 1.72.39l1.15.24l1.83.32l.93.16c.9.13 1.81.24 2.72.34l.77.07c.73.07 1.47.13 2.22.17l.85.05c1 0 1.94.07 2.93.07c1.15 0 2.29 0 3.43-.09l1.09-.09c.77 0 1.54-.11 2.3-.19c.42 0 .83-.1 1.25-.16c.7-.08 1.41-.17 2.1-.28l1.27-.23c.68-.12 1.36-.25 2-.39l1.24-.29c.67-.16 1.35-.32 2-.51c.39-.1.78-.21 1.16-.33c.69-.2 1.37-.41 2.05-.63l1.07-.36c.7-.24 1.4-.5 2.1-.77l.94-.37c.74-.3 1.47-.62 2.19-.95l.77-.34c.8-.37 1.58-.77 2.36-1.17c.17-.09.35-.17.52-.27c.91-.48 1.8-1 2.68-1.5l.12-.07a63.95 63.95 0 1 0-89.21-84.88l-.21.39c-.3 1.03-.72 1.86-1.11 2.69" />
                                <path fill="currentColor"
                                    d="m371.51 202.43l-105.69 82.2a16 16 0 0 1-19.64 0L89.55 162.81l19.64-25.26L256 251.73l94.36-73.39A95.81 95.81 0 0 1 349 80H48a16 16 0 0 0-16 16v320a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V211.13a95.75 95.75 0 0 1-108.49-8.7" />
                            </svg>
                            <p class="ml-1">
                                Notifikasi
                                @livewire('sidebar-notifications-badge')
                            </p>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->role === 'Admin')
                    <li class="nav-header">Admin</li>
                    <li class="nav-item @yield('menuOpen_Superadmin_MasterData')">
                        <a href="#" class="nav-link @yield('Superadmin_MasterData')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16">
                                <g fill="currentColor">
                                    <path
                                        d="M3.904 1.777C4.978 1.289 6.427 1 8 1s3.022.289 4.096.777C13.125 2.245 14 2.993 14 4s-.875 1.755-1.904 2.223C11.022 6.711 9.573 7 8 7s-3.022-.289-4.096-.777C2.875 5.755 2 5.007 2 4s.875-1.755 1.904-2.223" />
                                    <path
                                        d="M2 6.161V7c0 1.007.875 1.755 1.904 2.223C4.978 9.71 6.427 10 8 10s3.022-.289 4.096-.777C13.125 8.755 14 8.007 14 7v-.839c-.457.432-1.004.751-1.49.972C11.278 7.693 9.682 8 8 8s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972" />
                                    <path
                                        d="M2 9.161V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13s3.022-.289 4.096-.777C13.125 11.755 14 11.007 14 10v-.839c-.457.432-1.004.751-1.49.972c-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972" />
                                    <path
                                        d="M2 12.161V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16s3.022-.289 4.096-.777C13.125 14.755 14 14.007 14 13v-.839c-.457.432-1.004.751-1.49.972c-1.232.56-2.828.867-4.51.867s-3.278-.307-4.51-.867c-.486-.22-1.033-.54-1.49-.972" />
                                </g>
                            </svg>
                            <p class="ml-1">
                                Master Data
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.supplier.index') }}"
                                    class="nav-link @yield('menuSuperadminSupplier')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M13.641 21.162c0 1.443 1.563 2.345 2.813 1.624c.58-.335.938-.955.938-1.625c0-1.443-1.563-2.345-2.813-1.624c-.58.336-.938.955-.938 1.625m-10.314 0c0 1.443 1.563 2.345 2.813 1.624c.58-.335.938-.955.938-1.625c0-1.443-1.563-2.345-2.813-1.624c-.58.336-.938.955-.938 1.625m16.966-4.005a1.865 1.865 0 0 1-1.856 1.66H1.022a.977.977 0 0 1 0-1.914h16.604a.98.98 0 0 0 .977-.83l1.514-13.41a1.866 1.866 0 0 1 1.856-1.66h.537a.977.977 0 1 1 0 1.875h-.362a.234.234 0 0 0-.234.205Z" />
                                        <path fill="currentColor"
                                            d="M1.92 11.785h6.564s.938 0 .938.938v1.875s0 .938-.938.938H1.921s-.938 0-.938-.938v-1.875s0-.938.938-.938m2.813-4.219h3.75s.938 0 .938.937v.938s0 .938-.938.938h-3.75s-.938 0-.938-.938v-.938s0-.937.938-.937m7.032-2.344h4.688s.938 0 .938.937v8.44s0 .937-.938.937h-4.688s-.938 0-.938-.938V6.16s0-.937.938-.937" />
                                    </svg>
                                    <p class="ml-1">
                                        Penyedia
                                    </p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.faskes.index') }}"
                                    class="nav-link @yield('menuSuperadminFaskes')">
                                    <img src="{{ asset('img\puskes.png') }}" alt="Logo Dinas Kesehatan"
                                        style="height: 33px; width: auto; object-fit: contain;">
                                    <p>Fasilitas Kesehatan</p>
                                </a>
                            </li>
                            
                            <li class="nav-item @yield('menuOpen_Superadmin_DataObat')">
                                <a href="#" class="nav-link @yield('Superadmin_dataObat')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M12.35 16.5q-1 1-2.425 1t-2.425-1t-1-2.412t1-2.413L11.65 7.5q1-1 2.425-1t2.425 1t1 2.413t-1 2.412zM8.9 15.075q.575.575 1.188.413t.837-.388l1.375-1.4l-2-2l-1.4 1.375q-.425.425-.425 1t.425 1m6.2-6.15q-.575-.575-1.187-.413t-.838.388L11.7 10.3l2 2l1.4-1.375q.425-.425.425-1t-.425-1M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h4.2q.325-.9 1.088-1.45T12 1t1.713.55T14.8 3H19q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V5H5zm7-14.75q.325 0 .538-.213t.212-.537t-.213-.537T12 2.75t-.537.213t-.213.537t.213.538t.537.212M5 19V5z" />
                                    </svg>
                                    <p>
                                        Data Barang
                                        <i class="right fas fa-angle-left mr-3"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview ml-4">
                                    <li class="nav-item">
                                        <a wire:navigate href="{{ route('superadmin.kategori.index') }}"
                                            class="nav-link @yield('menuSuperadminKategoriObat')">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Kategori Barang</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:navigate href="{{ route('superadmin.satuan.index') }}"
                                            class="nav-link @yield('menuSuperadminSatuanObat')">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Satuan Barang</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a wire:navigate href="{{ route('superadmin.obat.index') }}"
                                            class="nav-link @yield('menuSuperadminObat')">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Barang</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.sumber_anggaran.index') }}"
                                    class="nav-link @yield('menuSuperadminSumberAnggaran')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path
                                                d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                                            <path
                                                d="M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2m5 6h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3H10m2 0v1m0-8v1" />
                                        </g>
                                    </svg>
                                    <p>Sumber Anggaran</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.rak_penyimpanan.index') }}"
                                    class="nav-link @yield('menuSuperadminRakPenyimpanan')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M4 23q-.425 0-.712-.288T3 22V2q0-.425.288-.712T4 1t.713.288T5 2v1h14V2q0-.425.288-.712T20 1t.713.288T21 2v20q0 .425-.288.713T20 23t-.712-.288T19 22v-1H5v1q0 .425-.288.713T4 23m1-12h2V8q0-.425.288-.712T8 7h4q.425 0 .713.288T13 8v3h6V5H5zm0 8h6v-3q0-.425.288-.712T12 15h4q.425 0 .713.288T17 16v3h2v-6H5zm4-8h2V9H9zm4 8h2v-2h-2zm-4-8h2zm4 8h2z" />
                                    </svg>
                                    <p>Rak Penyimpanan</p>
                                </a>
                            </li> --}}
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('superadmin.transaksi_masuk.index') }}"
                            class="nav-link @yield('menuSuperadminTransaksiMasuk')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="6" height="6" x="9" y="15" rx="2"
                                        transform="rotate(90 9 15)" />
                                    <path d="m18 3l3 4l-3 4" />
                                    <path d="M21 7h-7c-3.771 0-5.657 0-6.828 1.172S6 11.229 6 15" />
                                </g>
                            </svg>

                            <p>
                                Barang Masuk
                            </p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a wire:navigate href="{{ route('superadmin.permintaan.index') }}"
                            class="nav-link @yield('menuSuperadminPermintaan')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 1200 1200">
                                <path fill="currentColor"
                                    d="M600 0c-65.168 0-115.356 54.372-115.356 119.385c0 62.619-.439 117.407-.439 117.407h-115.87c-2.181 0-4.291.241-6.372.586h-32.227v112.573h540.527V237.378h-32.227c-2.081-.345-4.191-.586-6.372-.586H715.796s1.318-49.596 1.318-117.041C717.114 57.131 665.168 0 600 0M175.195 114.185V1200h849.609V114.185H755.64v78.662h191.382v928.345h-693.97V192.847H444.36v-78.662zM600 115.649c21.35 0 38.599 17.18 38.599 38.452c0 21.311-17.249 38.525-38.599 38.525s-38.599-17.215-38.599-38.525c0-21.271 17.249-38.452 38.599-38.452M329.736 426.27v38.525h38.599V426.27zm115.869.732v38.525h424.658v-38.525zm-115.869 144.58v38.525h38.599v-38.525zm115.869.732v38.599h424.658v-38.599zM329.736 716.895v38.525h38.599v-38.525zm115.869.805v38.525h424.658V717.7zM329.736 862.28v38.525h38.599V862.28zm115.869.806v38.525h424.658v-38.525zm-115.869 144.507v38.525h38.599v-38.525zm115.869.805v38.525h424.658v-38.525z" />
                            </svg>
                            <p>
                                Permintaan Barang
                                @if (isset($countPermintaanBaru) && $countPermintaanBaru > 0)
                                    <span class="right badge badge-danger"
                                        style="position: absolute; top: 0; right: 0; transform: translate(-70%, 15%);">
                                        {{ $countPermintaanBaru }}
                                    </span>
                                @endif
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('superadmin.transaksi_keluar.index') }}"
                            class="nav-link @yield('menuSuperadminTransaksiKeluar')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="6" height="6" x="15" y="9" rx="2"
                                        transform="rotate(-90 15 9)" />
                                    <path d="m6 21l-3-4l3-4" />
                                    <path d="M3 17h7c3.771 0 5.657 0 6.828-1.172S18 12.771 18 9" />
                                </g>
                            </svg>
                            <p>
                                Barang Keluar
                            </p>
                        </a>
                    </li>

                    <li class="nav-item ">
                        <a wire:navigate href="{{ route('superadmin.stok.index') }}"
                            class="nav-link @yield('menuSuperadminStok')">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>
                                Stok Barang
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('superadmin.transaksi_return.index') }}"
                            class="nav-link @yield('menuSuperadminTransaksiReturn')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 14 14">
                                <g fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1">
                                    <path d="M9 .5L11.5 3L9 5.5" />
                                    <path d="M.5 6V4a1 1 0 0 1 1-1h10M5 13.5L2.5 11L5 8.5" />
                                    <path d="M13.5 8v2a1 1 0 0 1-1 1h-10" />
                                </g>
                            </svg>
                            <p>
                                Barang Return
                            </p>
                        </a>
                    </li>



                    <li class="nav-item @yield('menuOpen_Superadmin_Laporan')">
                        <a href="#" class="nav-link @yield('Superadmin_Laporan')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                viewBox="0 0 16 16">
                                <path fill="currentColor" fill-rule="evenodd"
                                    d="M2.5 1.045a.5.5 0 0 0-.5.5v10.91a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V5.364a.5.5 0 0 0-.152-.36L7.911 1.188a.5.5 0 0 0-.348-.142zm7.766 3.819L8.063 2.727v2.137zM6 5.5H4v-1h2zM10 8H4V7h6zm-6 2.5h6v-1H4z"
                                    clip-rule="evenodd" />
                                <path fill="currentColor" d="M13 7.5V14H4.5v1h9a.5.5 0 0 0 .5-.5v-7z" />
                            </svg>
                            <p class="ml-1">
                                Laporan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.LapSupplier.index') }}"
                                    class="nav-link @yield('menuSuperadminLapSupplier')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Penyedia
                                    </p>
                                    <p style="margin-left: 37px">
                                        Barang
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.Laptransaksi_masuk.index') }}"
                                    class="nav-link @yield('menuSuperadminLapTransaksiMasuk')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Masuk
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.LapPermintaan.index') }}"
                                    class="nav-link @yield('menuSuperadminLapPermintaan')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Unit
                                    </p>
                                    <p style="margin-left: 37px">
                                        Peminta
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.Laptransaksi_keluar.index') }}"
                                    class="nav-link @yield('menuSuperadminLapTransaksiKeluar')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Distribusi
                                    </p>
                                    <p style="margin-left: 37px">
                                        Barang
                                    </p>
                                </a>
                            </li>
                        </ul>
                        
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.LapStok.index') }}"
                                    class="nav-link @yield('menuSuperadminLapStok')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Stok
                                    </p>
                                    
                                </a>
                            </li>
                        </ul>

                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.Laptransaksi_return.index') }}"
                                    class="nav-link @yield('menuSuperadminLapTransaksiReturn')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Return 
                                    </p>
                                </a>
                            </li>
                        </ul>
                        
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.LapAkanKadaluarsa.index') }}"
                                    class="nav-link @yield('menuSuperadminLapAkanKadaluarsa')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Akan Kadaluarsa
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.LapKadaluarsa.index') }}"
                                    class="nav-link @yield('menuSuperadminLapKadaluarsa')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Kedaluwarsa
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.LapStokObat.index') }}"
                                    class="nav-link @yield('menuSuperadminLapStokObat')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Dinamika
                                    </p>
                                    <p style="margin-left: 37px">
                                        Barang 
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.LapStokMinimum.index') }}"
                                    class="nav-link @yield('menuSuperadminLapStokMinimum')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Stok Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Minimum
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('superadmin.Lap10Minta.index') }}"
                                    class="nav-link @yield('menuSuperadminLap10Minta')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Banyak Diminta
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item ">
                        <a wire:navigate href="{{ route('superadmin.user.index') }}"
                            class="nav-link @yield('menuSuperadminUser')">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                User
                            </p>
                        </a>
                    </li>

                    {{-- <li class="nav-item">
                        <a wire:navigate href="{{ route('superadmin.supplier.index') }}"
                            class="nav-link @yield('menuSuperadminKategori')">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Supplier
                            </p>
                        </a>
                    </li> --}}
                @endif

                @if (Auth::user()->role === 'Pegawai')
                    <li class="nav-header">Pegawai</li>


                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.transaksi_masuk.index') }}"
                            class="nav-link @yield('menuAdminTransaksiMasuk')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="6" height="6" x="9" y="15" rx="2"
                                        transform="rotate(90 9 15)" />
                                    <path d="m18 3l3 4l-3 4" />
                                    <path d="M21 7h-7c-3.771 0-5.657 0-6.828 1.172S6 11.229 6 15" />
                                </g>
                            </svg>

                            <p>
                                Barang Masuk
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.permintaan.index') }}"
                            class="nav-link @yield('menuAdminPermintaan')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 1200 1200">
                                <path fill="currentColor"
                                    d="M600 0c-65.168 0-115.356 54.372-115.356 119.385c0 62.619-.439 117.407-.439 117.407h-115.87c-2.181 0-4.291.241-6.372.586h-32.227v112.573h540.527V237.378h-32.227c-2.081-.345-4.191-.586-6.372-.586H715.796s1.318-49.596 1.318-117.041C717.114 57.131 665.168 0 600 0M175.195 114.185V1200h849.609V114.185H755.64v78.662h191.382v928.345h-693.97V192.847H444.36v-78.662zM600 115.649c21.35 0 38.599 17.18 38.599 38.452c0 21.311-17.249 38.525-38.599 38.525s-38.599-17.215-38.599-38.525c0-21.271 17.249-38.452 38.599-38.452M329.736 426.27v38.525h38.599V426.27zm115.869.732v38.525h424.658v-38.525zm-115.869 144.58v38.525h38.599v-38.525zm115.869.732v38.599h424.658v-38.599zM329.736 716.895v38.525h38.599v-38.525zm115.869.805v38.525h424.658V717.7zM329.736 862.28v38.525h38.599V862.28zm115.869.806v38.525h424.658v-38.525zm-115.869 144.507v38.525h38.599v-38.525zm115.869.805v38.525h424.658v-38.525z" />
                            </svg>
                            <p>
                                Permintaan Faskes
                                @if (isset($countPermintaanBaru) && $countPermintaanBaru > 0)
                                    <span class="right badge badge-danger"
                                        style="position: absolute; top: 0; right: 0; transform: translate(-70%, 15%);">
                                        {{ $countPermintaanBaru }}
                                    </span>
                                @endif
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.transaksi_keluar.index') }}"
                            class="nav-link @yield('menuAdminTransaksiKeluar')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="6" height="6" x="15" y="9" rx="2"
                                        transform="rotate(-90 15 9)" />
                                    <path d="m6 21l-3-4l3-4" />
                                    <path d="M3 17h7c3.771 0 5.657 0 6.828-1.172S18 12.771 18 9" />
                                </g>
                            </svg>
                            <p>
                                Barang Keluar
                            </p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a wire:navigate href="{{ route('admin.stok.index') }}" class="nav-link @yield('menuAdminStok')">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>
                                Stok Barang
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('admin.transaksi_return.index') }}"
                            class="nav-link @yield('menuAdminTransaksiReturn')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 14 14">
                                <g fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1">
                                    <path d="M9 .5L11.5 3L9 5.5" />
                                    <path d="M.5 6V4a1 1 0 0 1 1-1h10M5 13.5L2.5 11L5 8.5" />
                                    <path d="M13.5 8v2a1 1 0 0 1-1 1h-10" />
                                </g>
                            </svg>
                            <p>
                                Barang Return
                            </p>
                        </a>
                    </li>

                @endif

                @if (Auth::user()->role === 'Faskes')
                    <li class="nav-header">Fasilitas Kesehatan</li>

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('faskes.permintaan.index') }}"
                            class="nav-link @yield('menuFaskesPermintaan')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 1200 1200">
                                <path fill="currentColor"
                                    d="M600 0c-65.168 0-115.356 54.372-115.356 119.385c0 62.619-.439 117.407-.439 117.407h-115.87c-2.181 0-4.291.241-6.372.586h-32.227v112.573h540.527V237.378h-32.227c-2.081-.345-4.191-.586-6.372-.586H715.796s1.318-49.596 1.318-117.041C717.114 57.131 665.168 0 600 0M175.195 114.185V1200h849.609V114.185H755.64v78.662h191.382v928.345h-693.97V192.847H444.36v-78.662zM600 115.649c21.35 0 38.599 17.18 38.599 38.452c0 21.311-17.249 38.525-38.599 38.525s-38.599-17.215-38.599-38.525c0-21.271 17.249-38.452 38.599-38.452M329.736 426.27v38.525h38.599V426.27zm115.869.732v38.525h424.658v-38.525zm-115.869 144.58v38.525h38.599v-38.525zm115.869.732v38.599h424.658v-38.599zM329.736 716.895v38.525h38.599v-38.525zm115.869.805v38.525h424.658V717.7zM329.736 862.28v38.525h38.599V862.28zm115.869.806v38.525h424.658v-38.525zm-115.869 144.507v38.525h38.599v-38.525zm115.869.805v38.525h424.658v-38.525z" />
                            </svg>
                            <p>
                                Permintaan Barang
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('faskes.riwayat_permintaan.index') }}"
                            class="nav-link @yield('menuFaskesRiwayatPermintaan')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5">
                                    <path
                                        d="M19 10.5V10c0-3.771 0-5.657-1.172-6.828S14.771 2 11 2S5.343 2 4.172 3.172S3 6.229 3 10v4.5c0 3.287 0 4.931.908 6.038q.25.304.554.554C5.57 22 7.212 22 10.5 22M7 7h8m-8 4h4" />
                                    <path d="m18 18.5l-1.5-.55V15.5m-4.5 2a4.5 4.5 0 1 0 9 0a4.5 4.5 0 0 0-9 0" />
                                </g>
                            </svg>

                            <p class="ml-1">
                                Riwayat Permintaan
                            </p>
                            <p style="margin-left: 32px">
                                Barang
                            </p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role === 'Pimpinan')
                    <li class="nav-header">Pimpinan</li>

                    <li class="nav-item @yield('menuOpen_Pimpinan_Laporan')">
                        <a href="#" class="nav-link @yield('Pimpinan_Laporan')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                viewBox="0 0 16 16">
                                <path fill="currentColor" fill-rule="evenodd"
                                    d="M2.5 1.045a.5.5 0 0 0-.5.5v10.91a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V5.364a.5.5 0 0 0-.152-.36L7.911 1.188a.5.5 0 0 0-.348-.142zm7.766 3.819L8.063 2.727v2.137zM6 5.5H4v-1h2zM10 8H4V7h6zm-6 2.5h6v-1H4z"
                                    clip-rule="evenodd" />
                                <path fill="currentColor" d="M13 7.5V14H4.5v1h9a.5.5 0 0 0 .5-.5v-7z" />
                            </svg>
                            <p class="ml-1">
                                Laporan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.LapSupplier.index') }}"
                                    class="nav-link @yield('menuPimpinanLapSupplier')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Penyedia
                                    </p>
                                    <p style="margin-left: 37px">
                                        Barang
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.Laptransaksi_masuk.index') }}"
                                    class="nav-link @yield('menuPimpinanLapTransaksiMasuk')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Masuk
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.LapPermintaan.index') }}"
                                    class="nav-link @yield('menuPimpinanLapPermintaan')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Unit
                                    </p>
                                    <p style="margin-left: 37px">
                                        Peminta
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.Laptransaksi_keluar.index') }}"
                                    class="nav-link @yield('menuPimpinanLapTransaksiKeluar')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Distribusi
                                    </p>
                                    <p style="margin-left: 37px">
                                        Barang
                                    </p>
                                </a>
                            </li>
                        </ul>
                        
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.LapStok.index') }}"
                                    class="nav-link @yield('menuPimpinanLapStok')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Stok
                                    </p>
                                    
                                </a>
                            </li>
                        </ul>

                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.Laptransaksi_return.index') }}"
                                    class="nav-link @yield('menuPimpinanLapTransaksiReturn')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Return 
                                    </p>
                                </a>
                            </li>
                        </ul>
                        
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.LapAkanKadaluarsa.index') }}"
                                    class="nav-link @yield('menuPimpinanLapAkanKadaluarsa')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Akan Kadaluarsa
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.LapKadaluarsa.index') }}"
                                    class="nav-link @yield('menuPimpinanLapKadaluarsa')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Kedaluwarsa
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.LapStokObat.index') }}"
                                    class="nav-link @yield('menuPimpinanLapStokObat')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Dinamika
                                    </p>
                                    <p style="margin-left: 37px">
                                        Barang 
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.LapStokMinimum.index') }}"
                                    class="nav-link @yield('menuPimpinanLapStokMinimum')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Stok Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Minimum
                                    </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview ml-4">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('pimpinan.Lap10Minta.index') }}"
                                    class="nav-link @yield('menuPimpinanLap10Minta')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p class="ml-1">
                                        Laporan Barang
                                    </p>
                                    <p style="margin-left: 37px">
                                        Banyak Diminta
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

            </ul>
        </nav>

    </div>
</aside>
