@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Transaksi LKeluar')
@section('menuSuperadminTransaksiKeluar','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.transaksi_keluar.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

