@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Permintaan')
@section('menuAdminPermintaan','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('admin.permintaan.index') {{-- ini memanggil di folder livewire/admin/ --}}
@endsection

