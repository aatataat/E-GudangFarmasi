@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Transaksi Keluar')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapTransaksiKeluar','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('Pimpinan.Laptransaksi_keluar.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

