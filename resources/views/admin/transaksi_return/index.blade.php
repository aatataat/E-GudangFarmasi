@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Transaksi Return')
@section('menuAdminTransaksiReturn','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('admin.transaksi_return.index') {{-- ini memanggil di folder livewire/admin/ --}}
@endsection

