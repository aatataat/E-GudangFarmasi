@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Transaksi Masuk')
@section('menuAdminTransaksiMasuk','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('admin.transaksi_masuk.index') {{-- ini memanggil di folder livewire/admin/ --}}
@endsection

