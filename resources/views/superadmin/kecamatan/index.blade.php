@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Kecamatan')
@section('menuOpen_Superadmin_MasterData','menu-open')
@section('menuOpen_Superadmin_dataPuskesmas','menu-open')
@section('Superadmin_MasterData','active')
@section('Superadmin_dataPuskesmas','active')
@section('menuSuperadminKecamatan','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.kecamatan.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

