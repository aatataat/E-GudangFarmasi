@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Barang')
@section('menuOpen_Superadmin_MasterData','menu-open')
@section('menuOpen_Superadmin_DataObat','menu-open')
@section('Superadmin_MasterData','active')
@section('Superadmin_dataObat','active')
@section('menuSuperadminObat','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.obat.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

