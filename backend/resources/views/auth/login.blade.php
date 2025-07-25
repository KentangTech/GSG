<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - PT Graha Sarana Gresik</title>
    <link rel="shortcut icon" href="{{ asset('GSG-Logo-Aja.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('Dashboard/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Auth/Login.css') }}">
</head>

<body class="bg-gradient-primary overflow-hidden">

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-5">

                        <div class="text-center mb-4">
                            <h1 class="h4 fw-bold text-dark">Selamat Datang!</h1>
                            <p class="text-muted small mb-0">Masukkan email dan password untuk melanjutkan.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <strong>Oops!</strong> Ada kesalahan:
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Form Login -->
                        <form method="POST" action="{{ route('login') }}" class="user">
                            @csrf

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <div class="input-wrapper position-relative">
                                    <input type="email" name="email"
                                        class="form-control form-control-user @error('email') is-invalid @enderror"
                                        placeholder="Alamat Email" value="{{ old('email') }}" required autofocus>
                                    <i class="fas fa-envelope input-icon"></i>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-3">
                                <div class="input-wrapper position-relative">
                                    <input type="password" name="password" id="passwordInput"
                                        class="form-control form-control-user @error('password') is-invalid @enderror"
                                        placeholder="Kata Sandi" required>

                                    <!-- Ikon kunci -->
                                    <i class="fas fa-lock input-icon start-icon"></i>

                                    <!-- Ikon toggle mata -->
                                    <span class="toggle-password end-icon" id="togglePassword" role="button"
                                        tabindex="0">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>

                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Submit -->
                            <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Dashboard
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const btn = document.querySelector('.btn-primary');
        if (btn) {
            btn.addEventListener('mouseenter', () => {
                btn.style.transform = 'translateY(-2px)';
                btn.style.boxShadow = '0 4px 12px rgba(78, 115, 223, 0.3)';
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = '';
                btn.style.boxShadow = '';
            });
        }

        document.querySelectorAll('.form-control-user').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.toggle('filled', this.value.length > 0);
            });
        });

        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');

        togglePassword.addEventListener('click', () => {
            const icon = togglePassword.querySelector('i');
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>
