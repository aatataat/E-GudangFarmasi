@extends('layouts.layout_landing') {{-- memanggil file --}}

@section('profil', 'active')
@section('struktur_organisasi', 'active')

@section('contentData')
    {{-- ini memanggil di layouts/app --}}
    @livewire('landing.struktur-organisasi.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection
