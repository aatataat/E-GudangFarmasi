@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Penyedia Barang')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapSupplier','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('pimpinan.LapSupplier.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

