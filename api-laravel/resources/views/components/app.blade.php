<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <!-- Custom Admin CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
    <link rel="icon" href="{{ asset('GSG-Logo-Aja.png') }}" type="image/png">
</head>
<body>
    <!-- Toggle Button (Mobile) -->
    <button class="btn toggle-sidebar d-md-none" type="button" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-rocket"></i> <span>AdminPanel</span>
        </div>
        <nav class="mt-4">
            <a href="#" class="active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="#">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="#">
                <i class="fas fa-box"></i>
                <span>Products</span>
            </a>
            <a href="#">
                <i class="fas fa-chart-line"></i>
                <span>Reports</span>
            </a>
            <a href="#">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="top-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark fw-semibold">@yield('page-title')</h5>
            <div class="d-flex align-items-center gap-3">
                <!-- Dark Mode Toggle -->
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                    <i class="fas fa-moon"></i>
                </button>

                <!-- Notification -->
                <button class="btn btn-light btn-sm rounded-circle position-relative">
                    <i class="far fa-bell text-muted"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill">3</span>
                </button>

                <!-- Profile -->
                <div class="d-flex align-items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=4361ee&color=fff&size=128"
                         alt="Profile" class="profile-img rounded-circle">
                    <span class="d-none d-md-block fw-medium">Admin</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dark Mode & Sidebar Toggle Script -->
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        if (localStorage.getItem('theme') === 'dark') {
            html.setAttribute('data-bs-theme', 'dark');
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        } else {
            html.setAttribute('data-bs-theme', 'light');
            themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        }

        themeToggle.addEventListener('click', () => {
            if (html.getAttribute('data-bs-theme') === 'dark') {
                html.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('theme', 'light');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            } else {
                html.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
        });

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }
    </script>
</body>
</html>
