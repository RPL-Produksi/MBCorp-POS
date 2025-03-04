@extends('layouts.app-2')
@section('title', 'Dashboard' . ' - ' . $kasir->perusahaan->nama)

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
    <style>
        #keranjangWrapper {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-6 col-xl-7 mt-3">
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
            <div class="col-12 col-lg-6 col-xl-5 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-primary">Keranjang</h4>
                    </div>
                    <form action="" class="form-group">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12" id="keranjangWrapper"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right">
                                <button class="btn btn-danger">Nguntang</button>
                                <button type="submit" class="btn btn-primary">Bayar</button>
                            </div>
                        </div>
                    </form>
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

                                let cartBtn = `<a onclick='addKeranjang("${row.id}")' class="btn btn-primary mr-1"><i class="fa-regular
                                    fa-shopping-cart"></i></a>`;
                                return `${cartBtn}`;
                            }
                        }
                    ],
                });
            })
        </script>
    @elseif (Request::query('mode') == 'gambar')
    @endif
    <script>
        const rupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number);
        }
    </script>
    <script>
        const cardKeranjang = () => {
            const cardKeranjangUrl = "{{ route('kasir.dashboard.keranjang.data') }}"
            $.ajax({
                url: cardKeranjangUrl,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let keranjangHtml = '';

                    if (response.length == 0) {
                        keranjangHtml = `<h4 class="text-center text-primary">Keranjang Kosong</h4>`;
                    } else {
                        response.forEach((item, i) => {
                            keranjangHtml += `
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-3">
                                            <img src="${item.produk.foto}"
                                                alt="${item.produk.nama}" class="img-fluid rounded">
                                        </div>
                                        <div class="col-6">
                                            <h5 class="card-title text-primary">${item.produk.nama}</h5>
                                            <p class="card-text">${rupiah(item.produk.harga)}</p>
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-primary mr-1 btnQuantityMin">
                                                    <i class="fa-regular fa-minus"></i>
                                                </button>
                                                <div class="d-flex align-items-center justify-content-center card" style="width: 50px;">
                                                    <span>${item.quantity}</span>
                                                </div>
                                                <input type="number" name="quantity" class="form-control" value="${item.quantity}" hidden>
                                                <button type="button" class="btn btn-primary ml-1 btnQuantityPlus">
                                                    <i class="fa-regular fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <button onclick="deleteKeranjang('${item.id}')" type="button"  class="btn btn-danger"><i class="fa-regular fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                        });
                    }

                    $('#keranjangWrapper').html(keranjangHtml);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            })
        }

        const addKeranjang = (id) => {
            const addKeranjangUrl = "{{ route('kasir.dashboard.keranjang.add', ':id') }}"
            $.ajax({
                url: addKeranjangUrl.replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    cardKeranjang();
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            })
        }

        const deleteKeranjang = (id) => {
            const deleteKeranjangUrl = "{{ route('kasir.dashboard.keranjang.delete', ':id') }}"
            $.ajax({
                url: deleteKeranjangUrl.replace(':id', id),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    cardKeranjang();
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            })
        }

        cardKeranjang();
    </script>
    <script>
        $(document).ready(function() {
            $(document).on("click", ".btnQuantityPlus", function() {
                let parent = $(this).closest(".d-flex");
                let quantitySpan = parent.find("span");
                let quantityInput = parent.find("input[name='quantity']");

                let currentQuantity = parseInt(quantitySpan.text());
                quantitySpan.text(currentQuantity + 1);
                quantityInput.val(currentQuantity + 1);
            });

            $(document).on("click", ".btnQuantityMin", function() {
                let parent = $(this).closest(".d-flex");
                let quantitySpan = parent.find("span");
                let quantityInput = parent.find("input[name='quantity']");

                let currentQuantity = parseInt(quantitySpan.text());
                if (currentQuantity > 1) {
                    quantitySpan.text(currentQuantity - 1);
                    quantityInput.val(currentQuantity - 1);
                }
            });
        });
    </script>
@endpush
