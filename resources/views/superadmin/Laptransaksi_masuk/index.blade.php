@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Transaksi Masuk')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapTransaksiMasuk','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.Laptransaksi_masuk.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

