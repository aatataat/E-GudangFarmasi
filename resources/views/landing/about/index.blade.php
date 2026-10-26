@extends('layouts.layout_landing') {{-- memanggil file --}}

{{-- @section('menuOpen_Superadmin_MasterData','menu-open') --}}
@section('tentang','active')

@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('landing.about.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

