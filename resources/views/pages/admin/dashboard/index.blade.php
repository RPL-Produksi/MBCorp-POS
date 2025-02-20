@extends('layouts.app')
@section('title', 'Dashboard')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content')
    @if (session('welcome'))
        <div class="alert alert-success border-left-success">
            {{ session('welcome') }}
        </div>
    @endif

    <div class="card p-3 border-left-primary">
        <h4 class="text-primary">Dasboard Admin</h4>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
@endpush
