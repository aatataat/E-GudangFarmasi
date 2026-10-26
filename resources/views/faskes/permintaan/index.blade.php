@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Permintaan')
@section('menuFaskesPermintaan','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('faskes.permintaan.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

