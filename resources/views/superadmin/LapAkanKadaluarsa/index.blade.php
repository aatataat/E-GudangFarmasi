@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan Barang Akan Kadaluarsa')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLapAkanKadaluarsa','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.lapAkanKadaluarsa.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

