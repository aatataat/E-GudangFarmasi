<!-- Spinner Start -->
<div id="spinner"
    class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner"></div>
</div>
<!-- Spinner End -->


<!-- Topbar Start -->
<div class="container-fluid bg-secondary px-5 d-none d-lg-block">
    <div class="row gx-0">
        <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>MH2Q+6Q, Kelayan Tim., Kec.
                    Banjarmasin Sel., Kota Banjarmasin, Kalimantan Selatan 70235</small>
                <small class="text-light"><i class="fa fa-envelope-open me-2"></i>uptdifkbjm@gmail.com</small>
            </div>
        </div>
        <div class="col-lg-4 text-center text-lg-end">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                {{-- <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i
                        class="fab fa-twitter fw-normal"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i
                        class="fab fa-facebook-f fw-normal"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href=""><i
                        class="fab fa-linkedin-in fw-normal"></i></a> --}}
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2"
                    target="_blank" href="https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&cad=rja&uact=8&ved=2ahUKEwjnkKv08vOPAxXWV2wGHRY0C7wQFnoECG8QAQ&url=https%3A%2F%2Fwww.instagram.com%2Fuptdifkbjm%2F&usg=AOvVaw2EKHOH-dY0iVKirPOcXmKH&opi=89978449"><i
                        class="fab fa-instagram fw-normal"></i></a>
                {{-- <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href=""><i
                        class="fab fa-youtube fw-normal"></i></a> --}}
            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->


<!-- Navbar & Carousel Start -->
<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
        <a href="" class="navbar-brand p-0">
            <img src="{{ asset('landing/img/GUDANG FARMASI.png') }}" alt="Logo Gudang Farmasi"
                style="width: 132px; height: 100%;">

        </a>
        <h2 class="m-0">Banjarmasin Maju Sejahtera</h2>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a wire:navigate href="{{ route('landing.home.index') }}"
                    class="nav-item nav-link @yield('home')">Beranda</a>
                <a wire:navigate href="{{ route('landing.about.index') }}"
                    class="nav-item nav-link @yield('tentang')">Tentang</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle @yield('profil')"
                        data-bs-toggle="dropdown">Profil</a>
                    <div class="dropdown-menu m-0">
                        <a wire:navigate href="{{ route('landing.visi-misi.index') }}"
                            class="dropdown-item @yield('visi_misi')">Visi, Misi & Motto</a>
                        <a wire:navigate href="{{ route('landing.struktur-organisasi.index') }}"
                            class="dropdown-item @yield('struktur_organisasi')">Struktur Organisasi</a>
                        <a wire:navigate href="{{ route('landing.maklumat.index') }}"
                            class="dropdown-item @yield('maklumat')">Maklumat Pelayanan</a>
                    </div>
                </div>
                <a wire:navigate href="{{ route('landing.informasi.index') }}"
                    class="nav-item nav-link @yield('informasi')">Informasi</a>

                <a wire:navigate href="{{ route('landing.pengaduan.index') }}"
                    class="nav-item nav-link @yield('pengaduan')">Pengaduan</a>

                <a wire:navigate href="{{ route('landing.kontak.index') }}"
                    class="nav-item nav-link @yield('kontak')">Kontak</a>


                <a href="{{ route('login') }}" class="nav-item nav-link">Login
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M13 21q-.425 0-.712-.288T12 20t.288-.712T13 19h6V5h-6q-.425 0-.712-.288T12 4t.288-.712T13 3h6q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm-1.825-8H4q-.425 0-.712-.288T3 12t.288-.712T4 11h7.175L9.3 9.125q-.275-.275-.275-.675t.275-.7t.7-.313t.725.288L14.3 11.3q.3.3.3.7t-.3.7l-3.575 3.575q-.3.3-.712.288T9.3 16.25q-.275-.3-.262-.712t.287-.688z" />
                    </svg>
                </a>
            </div>
        </div>
    </nav>


    <!-- Navbar & Carousel End -->
