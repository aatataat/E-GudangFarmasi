@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Stok Obat')
@section('menuAdminStok','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('admin.stok.index') {{-- ini memanggil di folder livewire/Admin/ --}}
@endsection

