<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Gudang | @yield('tittle')</title> {{-- pakai agar sesuai dengan section di views/superadmin/index --}}
    <link rel="shortcut icon" href="{{ asset('img\logo_icon.png') }}" />
    
    @include('layouts.style')

    @livewireStyles

</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        @include('layouts.navbar')

        <!-- Main Sidebar Container -->
        @include('layouts.sidebar')

        @yield('contentData') <!-- ini untuk menaruh di folder livewire -->

        @include('layouts.footer')


    </div>
    <!-- ./wrapper -->
    @include('layouts.script')

    @livewireScripts
</body>

</html>
