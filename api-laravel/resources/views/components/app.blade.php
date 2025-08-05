<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Admin Dashboard') | PT GSG</title>

    <!-- Google Fonts: Inter -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom Admin CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/direksi.css') }}" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('GSG-Logo-Aja.png') }}" type="image/png" />
</head>

<body>
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Backdrop untuk menutup sidebar di mobile -->
    <div class="sidebar-backdrop" style="display: none;" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        @include('components.header')

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <!-- Custom JavaScript -->
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.querySelector('.sidebar-backdrop');

            sidebar.classList.toggle('show');

            // Hanya tampilkan backdrop di mobile
            if (window.innerWidth < 992) {
                backdrop.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
            } else {
                backdrop.style.display = 'none';
            }
        }

        // Tutup sidebar saat resize ke desktop
        window.addEventListener('resize', () => {
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.querySelector('.sidebar-backdrop');

            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                backdrop.style.display = 'none';
            } else {
                // Biarkan sidebar tetap sesuai toggle
            }
        });

        // Optional: Auto-close sidebar saat klik menu (di mobile)
        document.querySelectorAll('.sidebar nav a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    setTimeout(() => {
                        document.querySelector('.sidebar').classList.remove('show');
                        document.querySelector('.sidebar-backdrop').style.display = 'none';
                    }, 300);
                }
            });
        });

        const header = document.querySelector('.top-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                header.style.boxShadow = '0 8px 30px rgba(67, 97, 238, 0.15)';
                header.style.padding = '0.6rem 1.2rem';
            } else {
                header.style.boxShadow = '0 4px 20px rgba(67, 97, 238, 0.1)';
                header.style.padding = '0.8rem 1.2rem';
            }
        });
    </script>

</body>

</html>
