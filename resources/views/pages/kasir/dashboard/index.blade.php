@extends('layouts.app-2')
@section('title', 'Dashboard' . ' - ' . $kasir->perusahaan->nama)

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 mt-3">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="text-primary">Daftar Produk |
                                    {{ Request::query('mode') == 'list' ? 'List' : 'Gambar' }}</h4>
                            </div>
                            @if (Request::query('mode') == 'list')
                                <a href="{{ route('kasir.dashboard', ['mode' => 'gambar']) }}" class="btn btn-primary"><i
                                        class="fa-regular fa-image"></i></a>
                            @elseif (Request::query('mode') == 'gambar')
                                <a href="{{ route('kasir.dashboard', ['mode' => 'list']) }}" class="btn btn-primary"><i
                                        class="fa-regular fa-list"></i></a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if (Request::query('mode') == 'list')
                            <table class="table table-bordered nowrap w-100" id="table-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        @elseif (Request::query('mode') == 'gambar')
                            <div class="row">
                                <div class="col-3">
                                    <div class="card">
                                        <img src="" alt="" class="card-img-top">
                                        <div class="card-body">
                                            <h5 class="card-title">Nama Produk</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-center">Mode Tidak Valid</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-primary">Keranjang</h4>
                    </div>
                    <div class="card-body">
                        <p>keranjang</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    @if (Request::query('mode') == 'list')
        <script>
            $(document).ready(() => {
                $('#table-1').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('kasir.dashboard.data') }}",
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
                            data: 'kode',
                            orderable: false,
                        },
                        {
                            data: 'nama',
                            orderable: true,
                        },
                        {
                            data: 'harga',
                            orderable: false,
                            render: function(data, type, row, meta) {
                                return `${rupiah(data)}`;
                            }
                        },
                        {
                            data: 'stok',
                            orderable: true,
                        },
                        {
                            data: null,
                            orderable: false,
                            render: function(data, type, row, meta) {
                                const deleteUrl =
                                    "{{ route('kasir.kelola.barang.delete', ':id') }}"

                                let cartBtn = `<a href='#' class="btn btn-primary mr-1"><i class="fa-regular
                                    fa-shopping-cart"></i></a>`;
                                let deleteBtn =
                                    `<a href='${deleteUrl.replace(":id", row.id).replace(":adminId", row.id)}' class="btn btn-danger" data-confirm-delete="true"><i class="fa-regular fa-trash"></i></a>`;
                                return `${cartBtn}${deleteBtn}`;
                            }
                        }
                    ],
                });
            })

            const rupiah = (number) => {
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR"
                }).format(number);
            }
        </script>
    @elseif (Request::query('mode') == 'gambar')
    @endif
@endpush
