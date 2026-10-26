@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Stok')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapStok','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.lapStok.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

