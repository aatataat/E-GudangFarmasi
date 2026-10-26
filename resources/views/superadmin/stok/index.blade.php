@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Stok Barang')
@section('menuSuperadminStok','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.stok.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

