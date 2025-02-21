@extends('layouts.app')
@section('title', 'Kelola Kasir - ' . $perusahaan->nama)

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="row">
        @include('templates.feedbacks')
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-title text-primary">Kelola Kasir | {{ $perusahaan->nama }}</h4>
                        </div>
                        <button class="btn btn-success" data-target="#tambahKasirModal" data-toggle="modal">
                            <i class="fa-regular fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered nowrap w-100" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahKasirModal" tabindex="-1" role="dialog" aria-labelledby="tambahKasirModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKasirModalLabel">Tambah Kasir | {{ $perusahaan->nama }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('admin.kelola.kasir.store') }}" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="nama_lengkap">Nama</label>
                            <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap"
                                placeholder="Masukkan Nama Kasir" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <input id="addUsername" type="text" class="form-control" name="username"
                                        placeholder="Masukkan username" required="">
                                </div>
                                <div class="col-2">
                                    <button onclick="$('#addUsername').val(randomText(8))" type="button"
                                        class="btn btn-primary"><i class="fa-regular fa-shuffle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <input id="addPassword" type="text" class="form-control" name="password"
                                        placeholder="Masukkan password" required="">
                                </div>
                                <div class="col-2">
                                    <button onclick="$('#addPassword').val(randomText(8))" type="button"
                                        class="btn btn-primary"><i class="fa-regular fa-shuffle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nomor_telp">Telepon</label>
                            <input type="number" class="form-control" name="nomor_telp" id="nomor_telp"
                                placeholder="Masukkan Nomor Telepon" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-loading">
                            <span class="btn-text">Tambah</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editKasirModal" tabindex="-1" role="dialog" aria-labelledby="editKasirModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKasirModalLabel">Edit Kasir | {{ $perusahaan->nama }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="formEditKasir" action="" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="nama_lengkap">Nama</label>
                            <input type="text" class="form-control" name="nama_lengkap" id="editNamaLengkap"
                                placeholder="Masukkan Nama Kasir" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <input id="editUsername" type="text" class="form-control" name="username"
                                        placeholder="Masukkan username" required="">
                                </div>
                                <div class="col-2">
                                    <button onclick="$('#editUsername').val(randomText(8))" type="button"
                                        class="btn btn-primary"><i class="fa-regular fa-shuffle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nomor_telp">Telepon</label>
                            <input type="number" class="form-control" name="nomor_telp" id="editNomorTelp"
                                placeholder="Masukkan Nomor Telepon" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-loading">
                            <span class="btn-text">Ubah</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog"
        aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password Kasir | {{ $perusahaan->nama }}
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="formChangePassword" action="" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="changePassword">Password Baru</label>
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <input id="changePassword" type="text" class="form-control" name="password"
                                        placeholder="Masukkan Password Baru" required="">
                                </div>
                                <div class="col-2">
                                    <button onclick="$('#changePassword').val(randomText(8))" type="button"
                                        class="btn btn-primary"><i class="fa-regular fa-shuffle"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-loading">
                            <span class="btn-text">Simpan</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
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
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.kelola.kasir.data') }}",
                    data: function(e) {
                        return e;
                    }
                },
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: null,
                        className: 'text-center',
                        orderable: true,
                        render: function(data, type, row, meta) {
                            let pageInfo = $('#table-1').DataTable().page.info();
                            return meta.row + 1 + pageInfo.start;
                        }
                    },
                    {
                        data: 'user.nama_lengkap',
                        orderable: true,
                    },
                    {
                        data: 'user.username',
                        orderable: false,
                    },
                    {
                        data: 'user.nomor_telp',
                        orderable: false,
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            const deleteUrl =
                                "{{ route('admin.kelola.kasir.delete', ':id') }}"

                            let editBtn =
                                `<a onclick="edit('${row.id}')" class="btn btn-primary mr-1"><i class="fa-regular fa-edit"></i></a>`;
                            let changePasswordBtn =
                                `<a onclick="changeJustPassword('${data.user.id}')" class="btn btn-warning mr-1"><i class="fa-regular fa-key"></i></a>`;
                            let deleteBtn =
                                `<a href='${deleteUrl.replace(":id", row.id)}' class="btn btn-danger" data-confirm-delete="true"><i class="fa-regular fa-trash"></i></a>`;
                            return `${editBtn}${changePasswordBtn}${deleteBtn}`;
                        }
                    }
                ],
            });
        })
    </script>
    <script>
        const edit = (id) => {
            $.getJSON(`${window.location.origin}/admin/kelola/kasir/data/${id}`, (data) => {
                const editUrl = `{{ route('admin.kelola.kasir.store', ':id') }}`

                $('#formEditKasir').attr('action', editUrl.replace(':id', id))
                $('#editNamaLengkap').val(data.user.nama_lengkap)
                $('#editUsername').val(data.user.username)
                $('#editNomorTelp').val(data.user.nomor_telp)

                const myModal = new bootstrap.Modal(document.getElementById('editKasirModal'))
                myModal.show()
            })
        }

        const changeJustPassword = (userId) => {
            const changeUrl = "{{ route('change.just.password', ':id') }}"

            $('#formChangePassword').attr('action', changeUrl.replace(':id', userId))

            const myModal = new bootstrap.Modal(document.getElementById('changePasswordModal'))
            myModal.show()
        }
    </script>
    <script>
        const randomText = (length) => {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
    </script>
@endpush
