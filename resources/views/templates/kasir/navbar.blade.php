<nav class="navbar navbar-expand-lg navbar-dark bg-primary p-3">
    <a class="navbar-brand" href="#">{{ $kasir->perusahaan->nama }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ @$menu_type == 'dashboard' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kasir.dashboard', ['mode' => 'list']) }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Cashdraw</a>
            </li>
            <li class="nav-item dropdown {{ @$menu_type == 'kelola' ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                    aria-expanded="false">
                    Kelola
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ @$sub_menu_type == 'barang' ? 'active' : '' }}" href="{{ route('kasir.kelola.barang') }}">Barang</a>
                    <a class="dropdown-item {{ @$sub_menu_type == 'kategori' ? 'active' : '' }}"
                        href="{{ route('kasir.kelola.kategori') }}">Kategori</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link">Riwayat</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->fullname }}</span>
                    <img src="{{ asset('assets/img/avatar-1.png') }}"
                        class="img-profile rounded-circle font-weight-bold" width="35"></img>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Keluar
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Yakin Ingin Keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Keluar" di bawah jika Anda siap mengakhiri sesi Anda saat ini.</div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                <form action="{{ route('logout') }}" method="POST" class="form-with-loading">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-loading">
                        <span class="btn-text">Keluar</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
