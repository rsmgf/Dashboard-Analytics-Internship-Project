<title>Buat Kata Sandi Baru | PLN Icon Plus</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

@vite('resources/css/reset.css')

<div class="login-page" style="background-image:url('{{ asset('images/BackgroundPageLogin.png') }}');">
    <div class="overlay"></div>

    <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus" class="logo">

    <div class="container-fluid h-100 main-content">
        <div class="row h-100 align-items-center">

            <div class="col-lg-5 d-flex justify-content-center justify-content-lg-start">
                <div class="login-card">
                    <div class="login-header">
                        <h2>Membuat Kata Sandi Baru</h2>
                        <p>
                            Tetapkan kata sandi baru Anda agar dapat masuk
                            dan mengakses Smart Panic Button.
                        </p>
                    </div>

                    <form action="#" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                Kata Sandi Baru
                            </label>
                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input id="password" type="password" class="form-control" placeholder="Masukkan Kata Sandi Baru" required>
                                <span class="input-group-text password-toggle" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input id="confirmPassword" type="password" class="form-control" placeholder="Konfirmasi Kata Sandi Baru" required>
                                <span class="input-group-text password-toggle" onclick="togglePassword('confirmPassword', this)">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100">
                            Atur Ulang Kata Sandi
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7 d-none d-lg-flex align-items-center">
                <div class="hero-wrapper">
                    <span class="hero-badge">
                        Solusi Digital PLN Icon Plus
                    </span>

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
                            <span class="pln-feature-label-pill">
                                Andal
                            </span>
                        </div>

                        <div class="pln-feature-item">
                            <div class="pln-feature-icon-card">
                                <i class="bi bi-globe2"></i>
                            </div>
                            <span class="pln-feature-label-pill">
                                Berkelanjutan
                            </span>
                        </div>

                        <div class="pln-feature-item">
                            <div class="pln-feature-icon-card">
                                <i class="bi bi-lightbulb-fill"></i>
                            </div>
                            <span class="pln-feature-label-pill">
                                Inovatif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function togglePassword(id, element) {
    const input = document.getElementById(id);
    const icon = element.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye-fill");
        icon.classList.add("bi-eye-slash-fill");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye-slash-fill");
        icon.classList.add("bi-eye-fill");
    }
}
</script>