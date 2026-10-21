<x-app-layout>
    <div class="account-page">
        <header class="account-page-header">
            <div class="account-page-heading">
                <span class="account-heading-icon"><i class="bi bi-person-gear"></i></span>
                <div>
                    <p class="account-eyebrow">PENGATURAN AKUN</p>
                    <h1>Akun Saya</h1>
                    <p class="account-subtitle">Perbarui informasi akun dan password Anda.</p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="account-back-link">
                <i class="bi bi-arrow-left"></i><span>Kembali ke Dashboard</span>
            </a>
        </header>

        <div class="account-content-grid">
            <section class="account-card" aria-labelledby="account-info-title">
                <div class="account-card-heading">
                    <div>
                        <h2 id="account-info-title">Nama dan Email</h2>
                        <p>Ubah identitas yang digunakan pada akun Anda.</p>
                    </div>
                    <span class="account-card-heading-icon"><i class="bi bi-person-vcard"></i></span>
                </div>
                @if (session('status') === 'profile-updated')
                    <div class="account-success-message" role="status">
                        <i class="bi bi-check-circle-fill"></i><span>Nama dan email berhasil diperbarui.</span>
                    </div>
                @endif
                @include('profile.partials.update-profile-information-form')
            </section>

            <section class="account-card" aria-labelledby="change-password-title">
                <div class="account-card-heading">
                    <div>
                        <h2 id="change-password-title">Ganti Password</h2>
                        <p>Masukkan password saat ini dan password baru.</p>
                    </div>
                    <span class="account-card-heading-icon"><i class="bi bi-key"></i></span>
                </div>
                @if (session('status') === 'password-updated')
                    <div class="account-success-message" role="status">
                        <i class="bi bi-check-circle-fill"></i><span>Password berhasil diperbarui.</span>
                    </div>
                @endif
                @include('profile.partials.update-password-form')
            </section>
        </div>
    </div>

    <style>
        .account-page { max-width: 1120px; margin: 0 auto; color: #16324f; }
        .account-page-header { display:flex; align-items:center; justify-content:space-between; gap:16px; margin: 2px 0 18px; }
        .account-page-heading { display:flex; align-items:center; gap:12px; min-width:0; }
        .account-heading-icon { width:42px; height:42px; flex:0 0 42px; display:grid; place-items:center; border-radius:11px; color:#147bc1; background:#eaf4fd; font-size:18px; }
        .account-eyebrow { margin:0 0 2px; color:#357eae; font-size:9px; font-weight:600; letter-spacing:.07em; }
        .account-page-header h1 { margin:0; color:#173c5e; font-size:20px; line-height:1.3; font-weight:600; }
        .account-subtitle { margin:3px 0 0; color:#718096; font-size:12px; }
        .account-back-link { flex:0 0 auto; display:inline-flex; align-items:center; gap:7px; padding:8px 11px; border:1px solid #dbe5ef; border-radius:8px; background:#fff; color:#49657d; text-decoration:none; font-size:11px; font-weight:500; }
        .account-back-link:hover { background:#f4f9ff; border-color:#9cc9ed; color:#0875c9; }
        .account-content-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); align-items:start; gap:16px; }
        .account-card { min-width:0; padding:19px; border:1px solid #e1e8f0; border-radius:12px; background:#fff; box-shadow:0 3px 12px rgba(25,55,85,.045); }
        .account-card-heading { display:flex; align-items:center; justify-content:space-between; gap:12px; padding-bottom:13px; border-bottom:1px solid #edf1f5; }
        .account-card-heading h2 { margin:0; color:#234762; font-size:15px; font-weight:600; }
        .account-card-heading p { margin:3px 0 0; color:#7a8998; font-size:11px; line-height:1.5; }
        .account-card-heading-icon { width:33px; height:33px; flex:0 0 33px; display:grid; place-items:center; border-radius:9px; background:#eef6ff; color:#277bb4; font-size:15px; }
        .account-success-message { display:flex; align-items:center; gap:8px; margin-top:13px; padding:9px 11px; border:1px solid #b7e4c7; border-radius:8px; background:#effaf2; color:#18733c; font-size:11px; font-weight:500; }
        .account-form { display:grid; gap:13px; margin-top:15px; }
        .account-field { display:grid; gap:6px; min-width:0; }
        .account-field label { color:#40596f; font-size:11px; font-weight:500; }
        .account-input-wrap { position:relative; }
        .account-input { display:block; width:100%; min-height:41px; padding:9px 40px 9px 11px; border:1px solid #d4dee8; border-radius:8px; outline:none; background:#fbfcfe; color:#1d344a; font:inherit; font-size:12px; transition:border-color .15s,box-shadow .15s,background .15s; }
        .account-input:not([type="password"]) { padding-right:11px; }
        .account-input::placeholder { color:#9aa7b5; }
        .account-input:focus { border-color:#4aa1dc; background:#fff; box-shadow:0 0 0 3px rgba(33,150,227,.1); }
        .account-input.is-invalid { border-color:#dc5a5a; }
        .account-input-toggle { position:absolute; top:50%; right:6px; transform:translateY(-50%); width:30px; height:30px; display:grid; place-items:center; border:0; border-radius:6px; background:transparent; color:#758596; cursor:pointer; }
        .account-input-toggle:hover { background:#eef4f9; color:#1678ba; }
        .account-field-error { margin:0; color:#c53d3d; font-size:10px; line-height:1.45; }
        .account-form-actions { display:flex; align-items:center; justify-content:flex-start; margin-top:2px; }
        .account-submit { display:inline-flex; align-items:center; justify-content:center; gap:7px; min-height:38px; padding:8px 13px; border:0; border-radius:8px; background:#087bd1; color:#fff; font:inherit; font-size:11px; font-weight:600; cursor:pointer; transition:background .15s,opacity .15s,box-shadow .15s; }
        .account-submit:not(:disabled):hover { background:#0569b5; box-shadow:0 3px 8px rgba(8,123,209,.16); }
        .account-submit:disabled { background:#cbd5df; color:#f8fafc; cursor:not-allowed; box-shadow:none; }
        @media (max-width:760px) { .account-content-grid { grid-template-columns:1fr; gap:12px; } }
        @media (max-width:520px) {
            .account-page-header { align-items:flex-start; flex-direction:column; gap:10px; margin-bottom:14px; }
            .account-page-header h1 { font-size:18px; }
            .account-subtitle { font-size:11px; }
            .account-back-link { align-self:flex-start; }
            .account-card { padding:15px; }
            .account-form { gap:11px; margin-top:13px; }
            .account-submit { width:100%; }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordForm = document.getElementById('passwordUpdateForm');
            if (passwordForm) {
                const requiredFields = [...passwordForm.querySelectorAll('[data-password-required]')];
                const submitButton = passwordForm.querySelector('[type="submit"]');
                const updatePasswordButton = () => {
                    submitButton.disabled = !requiredFields.every(field => field.value.length > 0);
                };
                requiredFields.forEach(field => field.addEventListener('input', updatePasswordButton));
                updatePasswordButton();
            }

            const profileForm = document.getElementById('profileUpdateForm');
            if (profileForm) {
                const nameField = profileForm.querySelector('[name="name"]');
                const emailField = profileForm.querySelector('[name="email"]');
                const submitButton = profileForm.querySelector('[type="submit"]');
                const updateProfileButton = () => {
                    const changed = nameField.value !== profileForm.dataset.originalName
                        || emailField.value !== profileForm.dataset.originalEmail;
                    submitButton.disabled = !changed;
                };
                [nameField, emailField].forEach(field => field.addEventListener('input', updateProfileButton));
                updateProfileButton();
            }

            const confirmationConfigs = {
                profileUpdateForm: {
                    title: 'Simpan perubahan akun?',
                    text: 'Nama dan email akun akan diperbarui.',
                    confirmButtonText: 'Ya, simpan perubahan',
                },
                passwordUpdateForm: {
                    title: 'Ganti password sekarang?',
                    text: 'Password akun akan diganti setelah password saat ini berhasil diverifikasi.',
                    confirmButtonText: 'Ya, ganti password',
                },
            };

            Object.entries(confirmationConfigs).forEach(([formId, config]) => {
                const form = document.getElementById(formId);
                if (!form) return;

                form.addEventListener('submit', async function (event) {
                    if (form.dataset.confirmed === 'true') {
                        delete form.dataset.confirmed;
                        return;
                    }

                    event.preventDefault();

                    const result = typeof Swal !== 'undefined'
                        ? await Swal.fire({
                            icon: 'question',
                            title: config.title,
                            text: config.text,
                            showCancelButton: true,
                            confirmButtonText: config.confirmButtonText,
                            cancelButtonText: 'Periksa kembali',
                            confirmButtonColor: '#087bd1',
                            cancelButtonColor: '#64748b',
                            reverseButtons: true,
                            focusCancel: true,
                        })
                        : { isConfirmed: window.confirm(`${config.title}\n${config.text}`) };

                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                });
            });
        });

        document.addEventListener('click', function (event) {
            const toggle = event.target.closest('[data-password-toggle]');
            if (!toggle) return;
            const input = document.getElementById(toggle.dataset.passwordToggle);
            if (!input) return;
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            toggle.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
            toggle.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
        });
    </script>
</x-app-layout>
