@extends('layouts.app')
@section('title', 'Tambah Owner')

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="card p-3">
        <div class="d-flex justify-content-between">
            <h4 class="text-primary">Tambah Owner</h4>
        </div>
        <hr>
        <div>
            <form action="{{ route('admin.store.owner') }}" class="form-group" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" required placeholder="Masukan nama lengkap">
                </div>
                <div class="mt-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="username" name="username" required placeholder="Masukan username">
                        <button type="button" class="btn btn-primary ml-2" onclick="generateUsername()">Random</button>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="password" name="password" required placeholder="Masukan password">
                        <button type="button" class="btn btn-primary ml-2" onclick="generatePassword()">Random</button>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="nomor_telp" class="form-label">Nomor Telepon</label>
                    <input type="number" class="form-control" name="nomor_telp" required placeholder="Masukan nomor telepon">
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
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

        const randomText = (length) => {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }

        function generateUsername() {
            document.getElementById("username").value = randomText(10);
        }

        function generatePassword() {
            document.getElementById("password").value = randomText(12);
        }
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
@endpush
