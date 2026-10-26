@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Permintaan Barang')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapPermintaan','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.LapPermintaan.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

