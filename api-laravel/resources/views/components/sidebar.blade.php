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
        <a href="{{ route('dashboard') }}" class="active">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('direksi.index') }}">
            <i class="fas fa-user-tie"></i>
            <span>Direksi</span>
        </a>
        {{-- <a href="{{ route('bisnis.index') }}"> --}}
            <i class="fas fa-briefcase"></i>
            <span>Bisnis</span>
        </a>
        {{-- <a href="{{ route('news.index') }}"> --}}
            <i class="fas fa-newspaper"></i>
            <span>News</span>
        </a>
        {{-- <a href="{{ route('sosmed.index') }}"> --}}
            <i class="fas fa-hashtag"></i>
            <span>Sosial Media</span>
        </a>
    </nav>
</aside>

<!-- Backdrop -->
<div class="sidebar-backdrop" style="display: none;" onclick="toggleSidebar()"></div>
