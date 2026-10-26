@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan 10 Barang Banyak Diminta')
@section('menuOpen_Superadmin_Laporan','menu-open')
@section('Superadmin_Laporan','active')
@section('menuSuperadminLap10Minta','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.Lap10Minta.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

