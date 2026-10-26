@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Fasilitas Kesehatan')
@section('menuOpen_Superadmin_MasterData','menu-open')
@section('Superadmin_MasterData','active')
@section('menuSuperadminFaskes','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.faskes.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

