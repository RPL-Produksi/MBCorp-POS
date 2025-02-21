<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa-regular fa-fw fa-school"></i>
            {{-- <img src="{{asset('assets/img/E - VOTING (2).png')}}" height="40"> --}}
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name') }}</div>
    </a>

    <hr class="sidebar-divider my-0">


    @can('superadmin')
        <li class="nav-item {{ @$menu_type == 'dashboard' ? 'active' : '' }}">
            <a href="" class="nav-link">
                <i class="fa-regular fa-fw fa-house"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Superadmin</div>

        <li class="nav-item {{ @$menu_type == 'kelola-perusahaan' ? 'active' : '' }}">
            <a href="{{ route('superadmin.kelola.perusahaan') }}" class="nav-link">
                <i class="fa-regular fa-fw fa-school"></i>
                <span>Kelola Perusahaan</span>
            </a>
        </li>
    @endcan

    @can('admin')
        <li class="nav-item {{ @$menu_type == 'dashboard' ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fa-regular fa-fw fa-house"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Admin</div>

        <li class="nav-item {{ @$menu_type == 'kelola-owner' ? 'active' : '' }}">
            <a href="{{ route('admin.kelola.owner') }}" class="nav-link">
                <i class="fa-regular fa-fw fa-user"></i>
                <span>Kelola Owner</span>
            </a>
        </li>
        <li class="nav-item {{ @$menu_type == 'kelola-kasir' ? 'active' : '' }}">
            <a href="{{ route('admin.kelola.kasir') }}" class="nav-link">
                <i class="fa-regular fa-fw fa-cash-register"></i>
                <span>Kelola Kasir</span>
            </a>
        </li>
    @endcan


    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
