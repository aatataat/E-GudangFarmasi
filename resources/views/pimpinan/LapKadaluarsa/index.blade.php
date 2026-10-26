@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Barang Kadaluarsa')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapKadaluarsa','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('Pimpinan.lapKadaluarsa.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

