@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Permintaan Barang')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapPermintaan','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('Pimpinan.LapPermintaan.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

