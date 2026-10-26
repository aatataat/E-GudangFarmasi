@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Transaksi Return')
@section('menuSuperadminTransaksiReturn','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.transaksi_return.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

