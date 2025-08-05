<aside class="sidebar">
    <!-- Tombol Tutup -->
    <button class="btn-close-sidebar" type="button" onclick="toggleSidebar()">
        <i class="fas fa-times" style="font-size: 0.9rem;"></i>
    </button>

    <!-- Logo -->
    <div class="logo">
        <i class="fas fa-rocket"></i> <span>GSG Panel</span>
    </div>

    <!-- Menu Navigasi -->
    <nav class="mt-4">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('direksi.index') }}" class="{{ request()->routeIs('direksi.*') ? 'active' : '' }}">
            <i class="fas fa-user-tie"></i>
            <span>Direksi</span>
        </a>

        <a href="{{ route('bisnis.index') }}" class="{{ request()->routeIs('bisnis.*') ? 'active' : '' }}">
            <i class="fas fa-briefcase"></i>
            <span>Bisnis</span>
        </a>

        <a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i>
            <span>News</span>
        </a>

        <a href="{{ route('medsos.index') }}" class="{{ request()->routeIs('medsos.*') ? 'active' : '' }}">
            <i class="fas fa-hashtag"></i>
            <span>Sosial Media</span>
        </a>
    </nav>
</aside>

<!-- Backdrop -->
<div class="sidebar-backdrop" style="display: none;" onclick="toggleSidebar()"></div>
