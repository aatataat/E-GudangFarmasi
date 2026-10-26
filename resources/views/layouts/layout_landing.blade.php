<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Gudang Farmasi Kota Banjarmasin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="{{ asset('landing/img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    @include('layouts.styleHome')
    @livewireStyles
</head>

<body>
    

    @include('layouts.navbar_homepage')

    @yield('contentData')

    @include('layouts.footer_homepage')


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i
            class="bi bi-arrow-up"></i></a>

    @include('layouts.scriptHome')

    {{-- agar no refresh --}}
    @stack('scripts') 

    @livewireScripts

</body>

</html>
