@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Transaksi Return')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapTransaksiReturn','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('Pimpinan.Laptransaksi_return.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

