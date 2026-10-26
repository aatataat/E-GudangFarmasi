@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Supplier')
@section('menuOpen_Superadmin_MasterData','menu-open')
@section('Superadmin_MasterData','active')
@section('menuSuperadminSupplier','active')
    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.supplier.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

