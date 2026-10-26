@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Sumber Anggaran')
@section('menuOpen_Superadmin_MasterData','menu-open')
@section('Superadmin_MasterData','active')
@section('menuSuperadminSumberAnggaran','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.sumber_anggaran.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

