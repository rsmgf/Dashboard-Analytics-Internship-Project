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

        <div class="overlay"></div>

        <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus" class="logo">

        <div class="container-fluid h-100 main-content">
            <div class="row h-100 align-items-center">

                <div class="col-lg-5 d-flex justify-content-center justify-content-lg-start">
                    <div class="login-card">
                        
                        <div class="login-header">
                            <h2>Selamat Datang Kembali!</h2>
                            <p>Silahkan login untuk melanjutkan ke akun Anda</p>
                        </div>

                        <form>
                            <div class="mb-4">
                                <label class="form-label">ID Karyawan</label>
                                <div class="input-group custom-input">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Masukkan ID Karyawan">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kata Sandi</label>
                                <div class="input-group custom-input">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control" placeholder="••••••••••••">
                                    <span class="input-group-text password-toggle">
                                        <i id="eye" class="bi bi-eye-fill" onclick="togglePassword()"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" checked>
                                    <label class="form-check-label" for="remember">Ingat Saya</label>
                                </div>
                                <a href="#" class="forgot-link">Lupa kata sandi?</a>
                            </div>

                            <button class="btn btn-login w-100" type="button">Masuk</button>

                            <div class="divider">
                                <span>atau</span>
                            </div>

                            <div class="register">
                                Belum mempunyai akun? <a href="#">Daftar Sekarang</a>
                            </div>
                        </form>

                    </div>
                </div>

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
                        
                            <div class="pln-feature-item">
                                <div class="pln-feature-icon-card">
                                    <i class="bi bi-patch-check-fill"></i>
                                </div>
                                <span class="pln-feature-label-pill">Andal</span>
                            </div>

                            <div class="pln-feature-item">
                                <div class="pln-feature-icon-card">
                                    <i class="bi bi-globe2"></i>
                                </div>
                                <span class="pln-feature-label-pill">Berkelanjutan</span>
                            </div>

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