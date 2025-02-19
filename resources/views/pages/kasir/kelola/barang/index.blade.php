@extends('layouts.app-2')
@section('title', 'Kelola Barang' . ' - ' . $kasir->perusahaan->nama)

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
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
                                    placeholder="Masukan Harga Barang" required>
                            </div>
                            <div class="form-input mt-2">
                                <label for="stok">Stok</label>
                                <input type="number" name="stok" id="stok" class="form-control"
                                    placeholder="Masukan Stok Barang" required>
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
                            <tbody>
                                @foreach ($barang as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->harga }}</td>
                                        <td>{{ $item->stok }}</td>
                                        <td>
                                            <button class="btn btn-primary"><i class="fa-regular fa-edit"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editKategoriModal" tabindex="-1" role="dialog" aria-labelledby="editKategoriModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori | {{ $kasir->perusahaan->nama }}
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="formEditKategori" action="" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" name="nama" id="editNama"
                                placeholder="Masukkan Nama Kategori" required>
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
            });
        })
    </script>
    <script>
        const edit = (id) => {
            $.getJSON(`${window.location.origin}/kasir/kelola/kategori/data/${id}`, (data) => {
                const editUrl = '{{ route('kasir.kelola.kategori.store', ':id') }}'

                $('#formEditKategori').attr('action', editUrl.replace(':id', id))
                $('#editNama').val(data.nama)

                const myModal = new bootstrap.Modal(document.getElementById('editKategoriModal'))
                myModal.show()
            })
        }
    </script>
@endpush
