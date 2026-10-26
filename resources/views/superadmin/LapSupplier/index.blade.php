@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Penyedia Barang')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapSupplier','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.LapSupplier.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

