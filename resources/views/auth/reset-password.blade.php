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

                    <form action="{{ route('password.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Masukkan Email" value="{{ old('email', $request->email) }}" required
                                    autofocus autocomplete="username">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kata Sandi Baru</label>
                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan Kata Sandi Baru" required autocomplete="new-password">
                                <span class="input-group-text password-toggle"
                                    onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-group custom-input">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input id="confirmPassword" type="password" name="password_confirmation"
                                    class="form-control" placeholder="Konfirmasi Kata Sandi Baru" required
                                    autocomplete="new-password">
                                <span class="input-group-text password-toggle"
                                    onclick="togglePassword('confirmPassword', this)">
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
                {{-- hero section, sama seperti sebelumnya --}}
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
