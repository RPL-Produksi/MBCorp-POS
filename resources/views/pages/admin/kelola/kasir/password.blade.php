@extends('layouts.app')
@section('title', 'Ganti Password')

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="card p-3">
        <div class="d-flex justify-content-between">
            <h4 class="text-primary">Ganti Password</h4>
        </div>
        <hr>
        <div>
            <form action="{{ route('admin.edit.kasir', $user->id) }}" class="form-group" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $user->nama_lengkap }}" class="form-control" name="nama_lengkap" required
                    placeholder="Masukan nama lengkap">
                <input type="hidden" value="{{ $user->username }}" class="form-control" id="username" name="username"
                    required placeholder="Masukan username">
                <input type="hidden" value="{{ $user->nomor_telp }}" class="form-control" name="nomor_telp" required
                    placeholder="Masukan nomor telepon">
                <div class="mt-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" class="form-control" name="password" required placeholder="Masukan password baru">
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
