@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Laporan 10 Barang Banyak Diminta')
@section('menuOpen_Pimpinan_Laporan','menu-open')
@section('Pimpinan_Laporan','active')
@section('menuPimpinanLap10Minta','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('pimpinan.Lap10Minta.index') {{-- ini memanggil di folder livewire/Pimpinan/ --}}
@endsection

