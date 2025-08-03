<header class="top-header d-flex justify-content-between align-items-center"
    style="border-radius: 16px; padding: 0.8rem 1.2rem;
               backdrop-filter: blur(12px);
               background: rgba(255, 255, 255, 0.8);
               border: 1px solid #e0e7ff;
               transition: all 0.3s ease;
               position: relative;
               z-index: 100;">

    <!-- Tombol Hamburger (Mobile) -->
    <button class="btn btn-link d-md-none me-3 p-0" type="button" onclick="toggleSidebar()" aria-label="Toggle sidebar"
        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #f0f4ff; color: #4361ee;">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Judul Halaman -->
    <h5 class="mb-0 text-dark fw-semibold flex-grow-1 text-center text-md-start px-2" style="font-size: 1.15rem;">
        @yield('page-title')
    </h5>

    <!-- Dropdown Profil -->
    <div class="dropdown">
        <button class="d-flex align-items-center gap-2 text-decoration-none" type="button" id="profileDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://ui-avatars.com/api/?name=Admin&background=4361ee&color=fff&size=128" alt="Profile"
                class="profile-img rounded-circle">
            <span class="d-none d-md-block fw-medium text-dark">Admin</span>
            <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem; color: #6c757d;"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="profileDropdown"
            style="min-width: 230px; border-radius: 16px; box-shadow: 0 20px 40px rgba(67, 97, 238, 0.18) !important;">

            <li>
                <h6 class="dropdown-header text-dark py-3 px-4">
                    <i class="fas fa-user-circle me-2"></i> Halo, Admin!
                </h6>
            </li>
            <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4" href="#"><i
                        class="fas fa-user text-primary"></i> Profil Saya</a></li>
            <li><a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4" href="#"><i
                        class="fas fa-cog text-success"></i> Pengaturan</a></li>
            <li>
                <hr class="dropdown-divider m-0">
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-3 py-2 px-4 text-danger"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</header>

<!-- Form Logout -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
