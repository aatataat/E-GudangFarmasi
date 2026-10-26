@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data User')
@section('menuSuperadminUser','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('superadmin.user.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

