<div>

    <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="{{ asset('landing/img/bangunan.jpg') }}" alt="Image">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h1 class="display-1 text-white mb-md-4 animated zoomIn">INFORMASI</h1>
                        <h5 class="text-white text-uppercase mb-3 animated slideInDown">UPTD Instalasi Farmasi Kota
                            Banjarmasin</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Testimonial Start -->
    <div id="iso2025" class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-4 mx-auto" style="max-width: 600px;">
                <h5 class="fw-bold text-primary text-uppercase">Informasi</h5>
                <h1 class="mb-0">Kegiatan Peningkatan Mutu ISO Kota Banjarmasin</h1>
            </div>

            <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="container py-5">
                    <div class="row g-5">
                        <div class="col-lg-8">
                            <div class="mb-5">
                                <img class="img-fluid rounded" src="{{ asset('landing/img/informasi/iso.jpg') }}"
                                    alt="Gambar Berita">
                                <h1 class="mb-4 mt-4">Judul Lengkap Berita Anda</h1>
                                <div class="d-flex mb-3">
                                    <small class="me-3 text-dark"><i class="far fa-calendar-alt me-2"></i>24 September
                                        2025</small>
                                    <small class="text-dark"><i class="far fa-user me-2"></i>Admin</small>
                                </div>
                                <hr>
                                <p class="mb-4">
                                    Paragraf pertama dari konten berita Anda. Ini bisa menjadi pengantar singkat.
                                </p>
                                <p class="mb-4">
                                    Isi detail berita Anda di sini. Anda dapat menambahkan beberapa paragraf, daftar,
                                    atau kutipan. Ini adalah tempat di mana Anda akan menjelaskan informasi secara lebih
                                    mendalam.
                                    <br><br>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum enim error
                                    quasi ut. Corporis maiores aspernatur repellendus facilis, dolores sit sint totam
                                    placeat optio non rerum ipsa necessitatibus aperiam quia?
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
                                <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                    <h3 class="mb-0">Berita Terbaru</h3>
                                </div>
                                <div class="d-flex rounded overflow-hidden mb-3">
                                    <img class="img-fluid" src="{{ asset('landing/img/no-image.jpg') }}"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                    <a href="#"
                                        class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0 text-decoration-none text-dark">
                                        Judul Berita Terkait 1
                                    </a>
                                </div>
                                <div class="d-flex rounded overflow-hidden mb-3">
                                    <img class="img-fluid" src="{{ asset('landing/img/no-image.jpg') }}"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                    <a href="#"
                                        class="h5 fw-semi-bold d-flex align-items-center bg-light px-3 mb-0 text-decoration-none text-dark">
                                        Judul Berita Terkait 2
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        {{-- agar no refresh --}}
        @push('scripts')
            <script>
                document.addEventListener('livewire:navigated', () => {
                    if (window.location.hash) {
                        // Tambahkan penundaan 300ms untuk memberi waktu animasi selesai
                        setTimeout(() => {
                            const targetElement = document.querySelector(window.location.hash);
                            if (targetElement) {
                                window.scrollTo({
                                    top: targetElement.offsetTop,
                                    behavior: 'smooth'
                                });
                                console.log('Halaman berhasil digulir ke ID:', window.location.hash);
                            } else {
                                console.log('Elemen dengan ID tersebut tidak ditemukan.');
                            }
                        }, 300); // Penundaan dalam milidetik
                    }
                });
            </script>
        @endpush
    </div>
    <!-- Testimonial End -->

</div>
