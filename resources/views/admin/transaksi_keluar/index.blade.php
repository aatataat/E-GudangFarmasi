@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Transaksi LKeluar')
@section('menuAdminTransaksiKeluar','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('admin.transaksi_keluar.index') {{-- ini memanggil di folder livewire/admin/ --}}
@endsection

