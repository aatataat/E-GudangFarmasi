<div>
    <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="{{ asset('landing/img/bangunan.jpg') }}" alt="Image">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h1 class="display-1 text-white mb-md-4 animated zoomIn">TENTANG KAMI</h1>
                        <h5 class="text-white text-uppercase mb-3 animated slideInDown">UPTD Instalasi Farmasi Kota Banjarmasin</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="section-title position-relative pb-3 mb-5">
                        <h5 class="fw-bold text-primary text-uppercase">Tentang Kami</h5>
                        <h1 class="mb-0">UPTD Instalasi Farmasi Kota Banjarmasin</h1>
                    </div>
                    <p class="mb-4">UPTD Instalasi Farmasi adalah Unit Pelaksana Teknis Dinas Kesehatan Kota
                        Banjarmasin yang bertanggung jawab dalam mengelola perbekalan farmasi untuk seluruh fasilitas
                        pelayanan kesehatan di wilayah Kota Banjarmasin. Kami berdedikasi untuk memastikan ketersediaan,
                        pemerataan, dan kualitas obat serta alat kesehatan demi mendukung pelayanan kesehatan yang
                        optimal bagi masyarakat.</p>
                    <div class="row g-0 mb-3">
                        <div class="col-sm-6 wow zoomIn" data-wow-delay="0.2s">
                            <h5 class="mb-3"><i class="fa fa-check text-primary me-3"></i>Pelayanan Prima</h5>
                            <h5 class="mb-3"><i class="fa fa-check text-primary me-3"></i>Pengelolaan Efisien</h5>
                        </div>
                        <div class="col-sm-6 wow zoomIn" data-wow-delay="0.4s">
                            <h5 class="mb-3"><i class="fa fa-check text-primary me-3"></i>Staf Profesional</h5>
                            <h5 class="mb-3"><i class="fa fa-check text-primary me-3"></i>Jaminan Kualitas</h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4 wow fadeIn" data-wow-delay="0.6s">
                        <div class="bg-primary d-flex align-items-center justify-content-center rounded"
                            style="width: 60px; height: 60px;">
                            <i class="fa fa-phone-alt text-white"></i>
                        </div>
                        <div class="ps-4">
                            <h5 class="mb-2">Hubungi Kami</h5>
                            <h4 class="text-primary mb-0">+62 511 3365 444</h4>
                        </div>
                    </div>
                    <a href="{{ route('landing.maklumat.index') }}" class="btn btn-primary py-3 px-5 mt-3 wow zoomIn"
                        data-wow-delay="0.9s">Lihat Maklumat Pelayanan</a>
                </div>
                <div class="col-lg-5" style="min-height: 500px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100 rounded wow zoomIn" data-wow-delay="0.9s"
                            src="{{ asset('landing/img/depan.jpg') }}" style="object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->
</div>
