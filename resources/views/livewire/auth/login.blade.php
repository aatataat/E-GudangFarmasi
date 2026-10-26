<div>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0 shadow-lg">
                            <div class="card-body">
                                {{-- <a href={{ route('homepage') }}
                                    class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('img\logo.png') }}" alt="Logo Dinas Kesehatan"
                                        style="height: 150px; width: auto; object-fit: contain;">
                                </a> --}}
                                <a href={{ route('homepage') }} class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('landing/img/GUDANG FARMASI.png') }}" alt="Logo Gudang Farmasi"
                                        style="width: 300px; height: 100%;">

                                </a>

                                <h4 class="text-center">LOGIN</h4>
                                <br><br>

                                <form wire:submit.prevent="loginUser">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Alamat Email</label>
                                        <input id="email" type="email" placeholder="Masukkan Alamat Email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            wire:model.defer="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label">Kata Sandi</label>
                                        <input id="password" type="password" placeholder="Masukkan Kata Sandi"
                                            class="form-control @error('password') is-invalid @enderror"
                                            wire:model.defer="password">
                                        @error('password')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input primary" type="checkbox" name="remember"
                                                id="remember" wire:model.defer="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                        <a class="text-primary fw-bold" href="{{ route('password.request') }}">Lupa
                                            Kata Sandi?</a>
                                    </div> --}}

                                    <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">
                                        Masuk
                                    </button>

                                    {{-- <div class="d-flex align-items-center justify-content-center">
                                        <a class="text-primary fw-bold ms-2" href="{{ route('register') }}">Belum Punya
                                            Akun?</a>
                                    </div> --}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
