@extends('layouts.app')
@section('title', 'Kelola Perusahaan')

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-title text-primary">Form Perusahaan</h4>
                        </div>
                    </div>
                </div>
                <form action="{{ route('superadmin.kelola.perusahaan.store', @$perusahaan->id) }}" class="form-group" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mt-2">
                                <div class="form-input">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" id="nama" class="form-control"
                                        value="{{ old('nama', @$perusahaan->nama) }}" placeholder="Masukkan Nama Perusahaan"
                                        required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mt-2">
                                <div class="form-input">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ old('email', @$perusahaan->email) }}"
                                        placeholder="Masukkan Email Perusahaan" required>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-input">
                                    <label for="nomor_telp">Nomor Telepon</label>
                                    <input type="number" name="nomor_telp" id="nomor_telp" class="form-control"
                                        value="{{ old('nomor_telp', @$perusahaan->nomor_telp) }}"
                                        placeholder="Masukkan Nomor Telepon Perusahaan" required>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-input">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat Perusahaan" required>{{ old('alamat', @$perusahaan->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-right">
                            <a href="{{ route('superadmin.kelola.perusahaan') }}" class="btn btn-link">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(() => {
            $('#table-1').DataTable()
        })
    </script>
@endpush
