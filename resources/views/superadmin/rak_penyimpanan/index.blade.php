@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Rak Penyimpanan')
@section('menuOpen_Superadmin_MasterData','menu-open')
@section('Superadmin_MasterData','active')
@section('menuSuperadminRakPenyimpanan','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.rak-penyimpanan.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

