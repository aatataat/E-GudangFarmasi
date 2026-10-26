@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Kategori Obat')
@section('menuOpen_Superadmin_MasterData','menu-open')
@section('menuOpen_Superadmin_DataObat','menu-open')
@section('Superadmin_MasterData','active')
@section('Superadmin_dataObat','active')
@section('menuSuperadminKategoriObat','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.kategori-obat.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

