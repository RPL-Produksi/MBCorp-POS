@extends('layouts.app')
@section('title', 'Kelola Owner - ' . $perusahaan->nama)

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
                            <h4 class="card-title text-primary">Kelola Owner | {{ $perusahaan->nama }}</h4>
                        </div>
                        <button class="btn btn-success" data-target="#tambahOwnerModal" data-toggle="modal">
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

    <div class="modal fade" id="tambahOwnerModal" tabindex="-1" role="dialog" aria-labelledby="tambahOwnerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahOwnerModalLabel">Tambah Owner | {{ $perusahaan->nama }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('superadmin.kelola.perusahaan.owner.store', ['perusahaanId' => $perusahaan->id]) }}"
                    method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="nama_lengkap">Nama</label>
                            <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap"
                                placeholder="Masukkan Nama Owner" required>
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

    <div class="modal fade" id="editOwnerModal" tabindex="-1" role="dialog" aria-labelledby="editOwnerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOwnerModalLabel">Edit Owner | {{ $perusahaan->nama }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="formEditOwner"
                    action="{{ route('superadmin.kelola.perusahaan.owner.store', ['perusahaanId' => $perusahaan->id]) }}"
                    method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="nama_lengkap">Nama</label>
                            <input type="text" class="form-control" name="nama_lengkap" id="editNamaLengkap"
                                placeholder="Masukkan Nama Owner" required>
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
                    url: "{{ route('superadmin.kelola.perusahaan.owner.data', ['perusahaanId' => $perusahaan->id]) }}",
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
                        data: 'user.nomor_telp',
                        orderable: false,
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            const deleteUrl = ''
                            const formEdit = ''

                            let editBtn =
                                `<a onclick=edit('${row.id}') class="btn btn-primary mr-1"><i class="fa-regular fa-edit"></i></a>`;
                            let deleteBtn =
                                `<a href='' class="btn btn-danger" data-confirm-delete="true"><i class="fa-regular fa-trash"></i></a>`;
                            return `${editBtn}${deleteBtn}`;
                        }
                    }
                ],
            });
        })
    </script>
    <script>
        const edit = (ownerId) => {
            const perusahaanId = '{{ $perusahaan->id }}'
            $.getJSON(`${window.location.origin}/superadmin/kelola/perusahaan/${perusahaanId}/owner/data/${ownerId}`, (
                data) => {
                const editUrl =
                    `{{ route('superadmin.kelola.perusahaan.owner.store', ['perusahaanId' => ':perusahaanId', 'ownerId' => ':ownerId']) }}`

                $('#formEditOwner').attr('action', editUrl.replace(':perusahaanId', perusahaanId).replace(
                    ':ownerId', ownerId))
                $('#editNamaLengkap').val(data.user.nama_lengkap)
                $('#editNomorTelp').val(data.user.nomor_telp)

                const myModal = new bootstrap.Modal(document.getElementById('editOwnerModal'))
                myModal.show()
            })
        }
    </script>
@endpush
