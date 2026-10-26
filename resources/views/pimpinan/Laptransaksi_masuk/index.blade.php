@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Transaksi Masuk')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLapTransaksiMasuk','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('Pimpinan.Laptransaksi_masuk.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

