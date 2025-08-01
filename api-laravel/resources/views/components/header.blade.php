<header class="top-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0 text-dark fw-semibold">@yield('page-title')</h5>
    <div class="d-flex align-items-center gap-3">
        <!-- Notifikasi -->
        <button class="btn btn-light btn-sm position-relative rounded-circle">
            <i class="far fa-bell text-muted"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill p-1" style="font-size: 0.6rem;">3</span>
        </button>

        <!-- Profile -->
        <div class="d-flex align-items-center gap-2">
            <img src="https://ui-avatars.com/api/?name=Admin&background=4361ee&color=fff&size=128"
                 alt="Profile" class="profile-img rounded-circle">
            <div>
                <span class="d-none d-md-block fw-medium text-dark">Admin</span>
            </div>
        </div>
    </div>
</header>
