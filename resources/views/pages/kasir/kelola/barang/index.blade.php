@extends('layouts.app-2')
@section('title', 'Kelola Barang' . ' - ' . $kasir->perusahaan->nama)

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2-bootstrap-5-theme.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            @include('templates.feedbacks')
            <div class="col-12 col-md-4 mt-3">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title text-primary">Tambah Barang</h4>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('kasir.kelola.barang.store') }}" class="form-group form-with-loading"
                        enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-input mt-2">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control"
                                    placeholder="Masukan Nama Barang" required>
                            </div>
                            <div class="form-input mt-2">
                                <label for="kode">Kode</label>
                                <input type="text" name="kode" id="kode" class="form-control"
                                    placeholder="Masukan Kode Barang" required>
                            </div>
                            <div class="form-input mt-2">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" id="harga" class="form-control"
                                    placeholder="Masukan Harga Barang" required min="0">
                            </div>
                            <div class="form-input mt-2">
                                <label for="stok">Stok</label>
                                <input type="number" name="stok" id="stok" class="form-control"
                                    placeholder="Masukan Stok Barang" required min="0">
                            </div>
                            <div class="form-input mt-2">
                                <label for="kategori_id">Kategori</label>
                                <select name="kategori_id" id="kategori_id" class="form-control">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-input mt-2">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Masukan Deskripsi Barang" required></textarea>
                            </div>
                            <div class="form-input mt-2">
                                <label for="foto">Foto</label>
                                <input type="file" name="foto" id="foto" class="form-control"
                                    placeholder="Masukan Foto Barang" required>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <button type="submit" class="btn btn-primary btn-loading float-right">
                                <span class="btn-text">Simpan</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-8 mt-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title text-primary">Daftar Barang</h4>
                    </div>
                    <div class="card-body">
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
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editBarangModal" tabindex="-1" role="dialog" aria-labelledby="editBarangModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBarangModalLabel">Edit Barang | {{ $kasir->perusahaan->nama }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="formEditBarang" action="" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group mt-2">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" name="nama" id="editNama"
                                placeholder="Masukkan Nama Barang" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="kode">Kode</label>
                            <input type="text" class="form-control" name="kode" id="editKode"
                                placeholder="Masukkan Kode Barang" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="harga">Harga</label>
                            <input type="text" class="form-control" name="harga" id="editHarga"
                                placeholder="Masukkan Harga Barang" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="kategori_id">Kategori</label>
                            <select name="kategori_id" id="editKategori" class="form-control">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="editDeskripsi" class="form-control" placeholder="Masukkan Deskripsi Barang" required></textarea>
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

    <div class="modal fade" id="addStockModal" tabindex="-1" role="dialog" aria-labelledby="addStockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockModalLabel">Tambah Stok | {{ $kasir->perusahaan->nama }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="formTambahStok" action="" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" name="stok" id="stok"
                                placeholder="Masukkan Stok Barang" required>
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

    <div class="modal fade" id="changeImageModal" tabindex="-1" role="dialog" aria-labelledby="changeImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeImageModalLabel">Preview Gambar | {{ $kasir->perusahaan->nama }}
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="formEditImage" action="" method="POST" class="form-with-loading"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-center">
                                <img src="" id="changeImgSrc" class="img-fluid w-50 font-weight-bold">
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-center mt-3">
                                <input onchange="this.form.submit()" class="d-none" type="file" name="foto"
                                    id="imgInput">
                                <button type="button" class="btn btn-primary btn-sm text-center" id="changeBtn">Ubah
                                    Foto</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(() => {
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kasir.kelola.barang.data') }}",
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

                            let editBtn =
                                `<a onclick="edit('${row.id}')" class="btn btn-primary mr-1"><i class="fa-regular fa-edit"></i></a>`;
                            let addStock =
                                `<a onclick="addStock('${row.id}')" class="btn btn-warning mr-1"><i class="fa-regular fa-boxes-stacked"></i></a>`;
                            let changeImageBtn =
                                `<a onclick="changeImage('${row.id}')" class="btn btn-info mr-1"><i class="fa-regular fa-image"></i></a>`;
                            let deleteBtn =
                                `<a href='${deleteUrl.replace(":id", row.id).replace(":adminId", row.id)}' class="btn btn-danger" data-confirm-delete="true"><i class="fa-regular fa-trash"></i></a>`;
                            return `${addStock}${changeImageBtn}${editBtn}${deleteBtn}`;
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
    <script>
        $(document).ready(() => {
            $('#kategori_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih Kategori',
                allowClear: true,
                tags: true
            });
        })
    </script>
    <script>
        const edit = (id) => {
            $.getJSON(`${window.location.origin}/kasir/kelola/barang/data/${id}`, (data) => {
                const editUrl = '{{ route('kasir.kelola.barang.store', ':id') }}';

                $('#formEditBarang').attr('action', editUrl.replace(':id', id));
                $('#editNama').val(data.nama);
                $('#editKode').val(data.kode);
                $('#editHarga').val(data.harga);
                $('#editDeskripsi').val(data.deskripsi);

                $('#editKategori').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Pilih Kategori',
                    allowClear: true,
                    tags: true
                }).val(data.kategori_id).trigger('change');

                const myModal = new bootstrap.Modal(document.getElementById('editBarangModal'));
                myModal.show();
            });
        }

        const addStock = (id) => {
            $.getJSON(`${window.location.origin}/kasir/kelola/barang/data/${id}`, (data) => {
                const addStockUrl = '{{ route('kasir.kelola.barang.stock.add', ':id') }}';

                $('#formTambahStok').attr('action', addStockUrl.replace(':id', id));

                const myModal = new bootstrap.Modal(document.getElementById('addStockModal'));
                myModal.show();
            });
        }

        const changeImage = (id) => {
            $.getJSON(`${window.location.origin}/kasir/kelola/barang/data/${id}`, (data) => {
                const changeImageUrl = '{{ route('kasir.kelola.barang.change.image', ':id') }}';

                $('#formEditImage').attr('action', changeImageUrl.replace(':id', id));
                $('#changeImgSrc').attr('src', data.foto);

                const myModal = new bootstrap.Modal(document.getElementById('changeImageModal'));
                myModal.show();
            });
        }
    </script>
    <script>
        const changeButton = document.getElementById('changeBtn');
        const imageInput = document.getElementById('imgInput');

        changeButton.addEventListener('click', () => {
            imageInput.click();
        })
    </script>
@endpush
