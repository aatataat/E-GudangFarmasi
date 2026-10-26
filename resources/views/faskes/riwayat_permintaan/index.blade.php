@extends('layouts.layout') {{-- memanggil file --}}

@section('tittle','Data Riwayat Permintaan')
@section('menuFaskesRiwayatPermintaan','active')

    
@section('contentData') {{-- ini memanggil di layouts/app --}}
    @livewire('faskes.riwayat_permintaan.index') {{-- ini memanggil di folder livewire/superadmin/ --}}
@endsection

