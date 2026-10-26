@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Permintaan')
@section('menuSuperadminPermintaan','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.permintaan.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

