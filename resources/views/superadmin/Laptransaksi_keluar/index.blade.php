@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Transaksi Keluar')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapTransaksiKeluar','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.Laptransaksi_keluar.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

