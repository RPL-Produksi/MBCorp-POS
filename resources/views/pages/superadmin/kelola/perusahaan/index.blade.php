@extends('layouts.app')
@section('title', 'Kelola Perusahaan')

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
                            <h4 class="card-title text-primary">Kelola Perusahaan</h4>
                        </div>
                        <a href="{{ route('superadmin.kelola.perusahaan.form') }}" class="btn btn-success">
                            <i class="fa-regular fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered nowrap w-100" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Perusahaan</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Kelola</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
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
                    url: "{{ route('superadmin.kelola.perusahaan.data') }}",
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
                        data: 'nama',
                        orderable: true,
                    },
                    {
                        data: 'email',
                        orderable: false,
                    },
                    {
                        data: 'nomor_telp',
                        orderable: false,
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let kelolaOwnerUrl =
                                "{{ route('superadmin.kelola.perusahaan.owner', ['perusahaanId' => ':id']) }}"

                            let kelolaOwnerBtn =
                                `<a href="${kelolaOwnerUrl.replace(':id', row.id)}" class="btn btn-primary mr-1"><i class="fa-regular fa-user"></i></a>`;
                            let kelolaAdminBtn =
                                `<a href="" class="btn btn-info mr-1"><i class="fa-regular fa-users"></i></a>`;
                            let kelolaKasirBtn =
                                `<a href="" class="btn btn-warning mr-1"><i class="fa-regular fa-cash-register"></i></a>`;
                            let kelolaMemberBtn =
                                `<a href="" class="btn btn-secondary"><i class="fa-regular fa-user-friends"></i></a>`;

                            return `${kelolaOwnerBtn}${kelolaAdminBtn}${kelolaKasirBtn}${kelolaMemberBtn}`;
                            mr - 1
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            const deleteUrl =
                                '{{ route('superadmin.kelola.perusahaan.delete', ':id') }}'
                            const formEdit =
                                '{{ route('superadmin.kelola.perusahaan.form', ':id') }}'

                            let editBtn =
                                `<a href='${formEdit.replace(':id', row.id)}' class="btn btn-primary mr-1"><i class="fa-regular fa-edit"></i></a>`;
                            let deleteBtn =
                                `<a href='${deleteUrl.replace(':id', row.id)}' class="btn btn-danger" data-confirm-delete="true"><i class="fa-regular fa-trash"></i></a>`;
                            return `${editBtn}${deleteBtn}`;
                        }
                    }
                ],
            });
        })
    </script>
@endpush
