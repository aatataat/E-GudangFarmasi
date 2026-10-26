@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Stok Minimum Barang')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapStokMinimum','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.lapStokMinimum.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

