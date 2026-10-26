@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Stok Minimum Barang')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapStokMinimum','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('Pimpinan.lapStokMinimum.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

