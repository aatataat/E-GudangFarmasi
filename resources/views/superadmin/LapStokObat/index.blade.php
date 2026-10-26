@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Kartu Stok Barang')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapStokObat','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.lapStokObat.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

