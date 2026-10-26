@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Transaksi Masuk')
@section('menuSuperadminTransaksiMasuk','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.transaksi_masuk.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

