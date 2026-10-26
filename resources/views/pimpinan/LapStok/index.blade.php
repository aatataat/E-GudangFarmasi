@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Stok')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapStok','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('Pimpinan.lapStok.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

