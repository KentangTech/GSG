<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-md-block" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('GSG-kecil.png') }}" alt="Logo GSG" style="height: 45px; margin-right: 8px;">
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - News -->
    <li class="nav-item {{ request()->routeIs('news.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('news.index') }}">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>News</span>
        </a>
    </li>

    <!-- Nav Item - Direksi -->
    <li class="nav-item {{ request()->routeIs('direksi.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('direksi.index') }}">
            <i class="fas fa-fw fa-user-tie"></i>
            <span>Direksi</span>
        </a>
    </li>

</ul>
<!-- End of Sidebar -->
