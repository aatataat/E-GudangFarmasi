@extends('layouts.layout_landing') {{-- memanggil file --}}

{{-- @section('menuOpen_Superadmin_MasterData','menu-open') --}}
@section('informasi','active')

@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('landing.informasi.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

