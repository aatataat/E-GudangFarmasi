@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Transaksi Return')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapTransaksiReturn','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.Laptransaksi_return.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

