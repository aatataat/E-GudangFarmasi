@extends('layouts.layout_landing') {{-- memanggil file --}}

@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('landing.home.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection