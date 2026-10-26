@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Kartu Stok Barang')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapStokObat','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('Pimpinan.lapStokObat.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

