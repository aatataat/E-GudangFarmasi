@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Barang Kadaluarsa')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapKadaluarsa','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.lapKadaluarsa.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

