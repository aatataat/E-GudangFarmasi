@extends('layouts.layout_landing') {{-- memanggil file --}}

@section('profil', 'active')
@section('visi_misi', 'active')

@section('contentData')
    {{-- ini memanggil di layouts/app --}}
    @livewire('landing.visi-misi.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection
