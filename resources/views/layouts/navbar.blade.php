<!-- Navbar -->
<nav class="main-header navbar navbar-expand text-white" style="background-color: #4f7856">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                    class="fas fa-bars text-white"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        {{-- Render Livewire NavbarNotifications component --}}
        @if (Auth::user()->role === 'Admin' || Auth::user()->role === 'Pegawai' || Auth::user()->role === 'Pimpinan')
        @livewire('navbar-notifications')
        @endif

        {{-- Profil Pengguna (bagian ini tetap sama) --}}
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset('img/user2-160x160.jpg') }}"
                    class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline text-white">{{ Auth::user()->nama }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-success">
                    <img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
                    <p>
                        {{ Auth::user()->name }}
                        <small>
                            <i class="fas fa-user-shield"></i>
                            @if (Auth::user()->role == 'Superadmin')
                                <span class="badge badge-pill badge-info">
                                    {{ Auth::user()->role }}
                                </span>
                            @elseif(Auth::user()->role == 'Pegawai')
                                <span class="badge badge-pill badge-secondary">
                                    {{ Auth::user()->role }}
                                </span>
                            @elseif(Auth::user()->role == 'Faskes')
                                <span class="badge badge-pill badge-success">
                                    {{ Auth::user()->role }}
                                </span>
                            @elseif(Auth::user()->role == 'Pimpinan')
                                <span class="badge badge-pill badge-primary">
                                    {{ Auth::user()->role }}
                                </span>
                            @endif
                        </small>
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="btn btn-sm btn-danger float-right">
                        <i class="fas fa-sign-out-alt"></i>
                        Log Out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
