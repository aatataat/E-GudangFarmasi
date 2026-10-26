@extends('layouts.layout_landing') {{-- memanggil file --}}

{{-- @section('menuOpen_Superadmin_MasterData','menu-open') --}}
@section('pengaduan','active')

@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('landing.pengaduan.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

