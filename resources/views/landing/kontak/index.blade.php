@extends('layouts.layout_landing') {{-- memanggil file --}}

{{-- @section('menuOpen_Superadmin_MasterData','menu-open') --}}
@section('kontak','active')

@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('landing.kontak.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

