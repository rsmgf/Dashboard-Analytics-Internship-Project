<title>Password Berhasil Diubah | PLN Icon Plus</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

@vite('resources/css/reset.css')

<div class="login-page" style="background-image:url('{{ asset('images/BackgroundPageLogin.png') }}');">

    <div class="overlay"></div>

    <img src="{{ asset('images/logo-iconplus.png') }}" alt="PLN Icon Plus" class="logo">

    <div class="container-fluid h-100 main-content">
        <div class="row h-100 align-items-center">

            <div class="col-lg-5 d-flex justify-content-center justify-content-lg-start">
                <div class="login-card text-center">
                    <div class="success-icon">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <div class="login-header">
                        <h2>Password Berhasil Diubah</h2>
                        <p>
                            Kata sandi Anda telah berhasil diperbarui.
                            Silakan masuk kembali menggunakan
                            kata sandi yang baru.
                        </p>
                    </div>

                    <a href="/login" class="btn btn-login w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Kembali ke Login
                    </a>
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