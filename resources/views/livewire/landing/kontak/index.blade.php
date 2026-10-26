<div>
    <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="{{ asset('landing/img/bangunan.jpg') }}" alt="Image">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h1 class="display-1 text-white mb-md-4 animated zoomIn">KONTAK</h1>
                        <h5 class="text-white text-uppercase mb-3 animated slideInDown">UPTD Instalasi Farmasi Kota
                            Banjarmasin</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
                <h5 class="fw-bold text-primary text-uppercase">Hubungi Kami</h5>
                <h1 class="mb-0">Jika Anda Memiliki Pertanyaan, Jangan Ragu untuk Menghubungi Kami</h1>
            </div>
            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.4s">
                        <div class="bg-primary d-flex align-items-center justify-content-center rounded"
                            style="width: 60px; height: 60px;">
                            <i class="fa fa-envelope-open text-white"></i>
                        </div>
                        <div class="ps-4">
                            <h5 class="mb-2">Email untuk informasi</h5>
                            <h4 class="text-primary mb-0">uptdifkbjm@gmail.com</h4>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex align-items-start wow fadeIn" data-wow-delay="0.8s">
                        <div class="bg-primary d-flex align-items-center justify-content-center rounded"
                            style="width: 60px; height: 60px;">
                            <i class="fa fa-map-marker-alt text-white"></i>
                        </div>
                        <div class="ps-4">
                            <h5 class="mb-2">Kunjungi kantor kami</h5>
                            <div class="text-primary mb-0" style="font-size: 1.1rem; line-height: 1.2;">
                                <strong class="d-block">Jl. Lingkar Dalam Selatan,</strong>
                                <strong class="d-block">Kelayan Tim., Kec. Banjarmasin Sel.,</strong>
                                <strong class="d-block">Kota Banjarmasin,</strong>
                                <strong class="d-block">Kalimantan Selatan 70235</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-6 wow slideInUp" data-wow-delay="0.3s">

                    @if (session()->has('success_message'))
                        <div class="alert alert-success text-center">
                            {{ session('success_message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submitForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control border-0 bg-light px-4"
                                    placeholder="Nama Anda" style="height: 55px;" wire:model="name">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <input type="email" class="form-control border-0 bg-light px-4"
                                    placeholder="Email Anda" style="height: 55px;" wire:model="email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-light px-4" placeholder="Subjek"
                                    style="height: 55px;" wire:model="subject">
                                @error('subject')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12">
                                <textarea class="form-control border-0 bg-light px-4 py-3" rows="4" placeholder="Pesan" wire:model="message"></textarea>
                                @error('message')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit" wire:loading.attr="disabled">
                                    <span wire:loading wire:target="submitForm">Mengirim...</span>
                                    <span wire:loading.remove wire:target="submitForm">Kirim Pesan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 wow slideInUp" data-wow-delay="0.6s">
                    <iframe class="position-relative rounded w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.0020269237543!2d114.5894641!3d-3.3496316999999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4212d874ac5b3%3A0x9c24e5eae9f9f885!2sGudang%20Farmasi%20Dinas%20Kesehatan%20Kota%20Banjarmasin!5e0!3m2!1sid!2sid!4v1758803882848!5m2!1sid!2sid"
                        frameborder="0" style="min-height: 350px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                </div>
            </div>
        </div>
    </div>

</div>
