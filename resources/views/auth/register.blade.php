<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PLN Icon Plus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite('resources/css/register.css')
</head>

<body>
    <div class="register-page" style="background-image:url('{{ asset('images/BackgroundPageLogin.png') }}');">
        <div class="overlay"></div>
        <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus" class="logo">

        <div class="main-content">
            <div class="container-fluid h-100">
                <div class="row h-100 align-items-center">

                    <div class="col-lg-7 d-none d-lg-flex align-items-center">
                        <div class="hero-wrapper">
                            <div class="hero-badge">
                                Solusi Digital PLN Icon Plus
                            </div>
                            <h1>
                                Energi Bersih Untuk<br>
                                Indonesia yang<br>
                                Lebih Baik
                            </h1>
                            <p>
                                Menghadirkan solusi digital yang inovatif, terintegrasi, dan terpercaya untuk mendukung
                                transformasi digital Indonesia.
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

                    <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end">
                        <div class="register-card">
                            <div class="register-header">
                                <h2>Buat Akun</h2>
                                <p>Silakan lengkapi data untuk membuat akun baru.</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}"
                                class="register-form register-form-stacked">
                                @csrf

                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap</label>
                                    <div class="custom-input @error('name') input-error @enderror">
                                        <i class="input-icon bi bi-person"></i>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
                                    </div>
                                    @error('name')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <div class="custom-input @error('email') input-error @enderror">
                                        <i class="input-icon bi bi-envelope"></i>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Masukkan email" value="{{ old('email') }}">
                                    </div>
                                    @error('email')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">No. Handphone</label>
                                    <div class="custom-input @error('no_hp') input-error @enderror">
                                        <i class="input-icon bi bi-telephone"></i>
                                        <input type="text" name="no_hp" class="form-control"
                                            placeholder="Masukkan nomor handphone" value="{{ old('no_hp') }}">
                                    </div>
                                    @error('no_hp')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <div class="custom-input password-input @error('password') input-error @enderror">
                                        <i class="input-icon bi bi-lock"></i>
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Masukkan password">
                                        <button type="button" class="password-toggle"
                                            onclick="togglePassword('password', this)" aria-label="Tampilkan password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <div
                                        class="custom-input password-input @error('password_confirmation') input-error @enderror">
                                        <i class="input-icon bi bi-lock"></i>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" placeholder="Ulangi password">
                                        <button type="button" class="password-toggle"
                                            onclick="togglePassword('password_confirmation', this)"
                                            aria-label="Tampilkan password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group full-width">
                                    <button type="submit" class="btn-register">
                                        Daftar
                                    </button>
                                </div>
                            </form>

                            <div class="login-link">
                                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Fitur lihat/sembunyikan password
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        // Fitur Pop-up Notifikasi Berhasil Register
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#008588', 
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            @endif
        });
    </script>
</body>

</html>