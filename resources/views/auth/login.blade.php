<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PLN Icon Plus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @vite('resources/css/login.css')
</head>
<body>

    <div class="login-page" style="background-image:url('{{ asset('images/BackgroundPageLogin.png') }}');">

        <!-- Overlay -->
        <div class="overlay"></div>

        <!-- Logo -->
        <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus" class="logo">

        <div class="container-fluid h-100 main-content">
            <div class="row h-100 align-items-center">

                <!-- =========================
                        LOGIN CARD
                ========================== -->
                <div class="col-lg-5 d-flex justify-content-center justify-content-lg-start">
                    <div class="login-card">

                        <div class="login-header">
                            <h2>Selamat Datang Kembali!</h2>
                            <p>Silahkan login untuk melanjutkan ke akun Anda</p>
                        </div>

                        {{-- Session status (contoh: setelah reset password berhasil) --}}
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- ID / Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group custom-input">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input
                                        id="email"
                                        type="text"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Masukkan email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                    >
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- PASSWORD -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <div class="input-group custom-input">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Masukkan kata sandi"
                                        required
                                    >
                                    <span class="input-group-text password-toggle">
                                        <i id="eye" class="bi bi-eye-fill" onclick="togglePassword()"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">Ingat Saya</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa kata sandi?</a>
                                @endif
                            </div>

                            <!-- Button -->
                            <button class="btn btn-login w-100" type="submit">Masuk</button>

                            <!-- Divider -->
                            <div class="divider">
                                <span>atau</span>
                            </div>

                            <!-- Register -->
                            @if (Route::has('register'))
                                <div class="register">
                                    Belum mempunyai akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                                </div>
                            @endif
                        </form>

                    </div>
                </div>

                <!-- =========================
                      HERO SECTION
                ========================== -->
                <div class="col-lg-7 d-none d-lg-flex align-items-center">
                    <div class="hero-wrapper">

                        <span class="hero-badge">Solusi Digital PLN Icon Plus</span>

                        <h1>
                            Energi Bersih <br>
                            Untuk Indonesia <br>
                            yang Lebih Baik
                        </h1>

                        <p>
                            Bersama PLN Icon Plus kami menghadirkan layanan
                            digital yang andal, inovatif, dan berkelanjutan
                            guna mendukung transformasi digital Indonesia.
                        </p>

                        <div class="pln-features-container">
                            <!-- Andal -->
                            <div class="pln-feature-item">
                                <div class="pln-feature-icon-card">
                                    <i class="bi bi-patch-check-fill"></i>
                                </div>
                                <span class="pln-feature-label-pill">Andal</span>
                            </div>

                            <!-- Berkelanjutan -->
                            <div class="pln-feature-item">
                                <div class="pln-feature-icon-card">
                                    <i class="bi bi-globe2"></i>
                                </div>
                                <span class="pln-feature-label-pill">Berkelanjutan</span>
                            </div>

                            <!-- Inovatif -->
                            <div class="pln-feature-item">
                                <div class="pln-feature-icon-card">
                                    <i class="bi bi-lightbulb-fill"></i>
                                </div>
                                <span class="pln-feature-label-pill">Inovatif</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const eye = document.getElementById("eye");

            if (input.type === "password") {
                input.type = "text";
                eye.classList.remove("bi-eye-fill");
                eye.classList.add("bi-eye-slash-fill");
            } else {
                input.type = "password";
                eye.classList.remove("bi-eye-slash-fill");
                eye.classList.add("bi-eye-fill");
            }
        }
    </script>

</body>
</html>