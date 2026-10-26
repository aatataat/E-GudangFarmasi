@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Notifikasi')
@section('menuSuperadminNotifikasi','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('notifikasi.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

