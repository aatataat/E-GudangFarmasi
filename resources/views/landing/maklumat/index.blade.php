@extends('layouts.layout_landing') {{-- memanggil file --}}

@section('profil', 'active')
@section('maklumat', 'active')

@section('contentData')
    {{-- ini memanggil di layouts/app --}}
    @livewire('landing.maklumat.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection
