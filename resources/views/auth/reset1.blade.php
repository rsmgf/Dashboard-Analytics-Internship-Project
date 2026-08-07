<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi | PLN Icon Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    @vite('resources/css/reset.css')
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
                            <h2>Lupa Kata Sandi?</h2>
                            <p>
                                Masukkan email Anda untuk menerima tautan
                                pengaturan ulang kata sandi.
                            </p>
                        </div>
                        
                        <form action="#" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label">Email</label>
                                <div class="input-group custom-input">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope-fill"></i>
                                    </span>
                                    <input type="email" class="form-control" placeholder="Masukkan Email" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login w-100">
                                Atur Ulang Kata Sandi
                            </button>
                            
                            <div class="divider">
                                <span>atau</span>
                            </div>
                            
                            <div class="register">
                                Sudah ingat kata sandi?
                                <a href="/login">Kembali ke Login</a>
                            </div>
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
</body>
</html>