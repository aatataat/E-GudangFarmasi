@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Barang Akan Kadaluarsa')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapAkanKadaluarsa','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('pimpinan.lapAkanKadaluarsa.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

