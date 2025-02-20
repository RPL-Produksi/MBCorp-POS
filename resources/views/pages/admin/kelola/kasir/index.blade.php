@extends('layouts.app')
@section('title', 'Kelola Kasir')

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="card p-3">
        <div class="d-flex justify-content-between">
            <h4 class="text-primary">Kelola Kasir</h4>
            <a href="{{ route('admin.store.kasir.show') }}" class="btn btn-success">Tambah</a>
        </div>
        <hr>
        <table class="table table-bordered" id="example">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th class="text-left">Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kasir as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->user->nama_lengkap }}</td>
                        <td>{{ $item->user->username }}</td>
                        <td class="text-left">{{ $item->user->nomor_telp }}</td>
                        <td>
                            <a href="{{ route('admin.edit.kasir.show', $item->id) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ route('admin.password.kasir.show', $item->id) }}" class="btn btn-warning">Password</a>
                            <form action="{{ route('admin.delete.kasir', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                pageLength: 10
            });
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Berhasil!",
                    text: "{{ session('error') }}",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btn-delete").forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault();

                    let url = this.getAttribute("href");

                    Swal.fire({
                        title: "Yakin ingin menghapus?",
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btn-delete").forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault();
                    let form = this.closest("form");

                    Swal.fire({
                        title: "Yakin ingin menghapus?",
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
