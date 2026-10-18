<form id="passwordUpdateForm" method="POST" action="{{ route('password.update') }}" class="account-form">
    @csrf
    @method('PUT')

    <div class="account-field">
        <label for="current_password">Password Saat Ini</label>
        <div class="account-input-wrap">
            <input id="current_password" name="current_password" type="password"
                class="account-input {{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }}"
                autocomplete="current-password" data-password-required required>
            <button type="button" class="account-input-toggle" data-password-toggle="current_password"
                aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
        </div>
        @foreach ($errors->updatePassword->get('current_password') as $message)
            <p class="account-field-error">{{ $message }}</p>
        @endforeach
    </div>

    <div class="account-field">
        <label for="new_password">Password Baru</label>
        <div class="account-input-wrap">
            <input id="new_password" name="password" type="password"
                class="account-input {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}"
                autocomplete="new-password" data-password-required required>
            <button type="button" class="account-input-toggle" data-password-toggle="new_password"
                aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
        </div>
        @foreach ($errors->updatePassword->get('password') as $message)
            <p class="account-field-error">{{ $message }}</p>
        @endforeach
    </div>

    <div class="account-field">
        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
        <div class="account-input-wrap">
            <input id="new_password_confirmation" name="password_confirmation" type="password"
                class="account-input {{ $errors->updatePassword->has('password_confirmation') ? 'is-invalid' : '' }}"
                autocomplete="new-password" data-password-required required>
            <button type="button" class="account-input-toggle" data-password-toggle="new_password_confirmation"
                aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
        </div>
        @foreach ($errors->updatePassword->get('password_confirmation') as $message)
            <p class="account-field-error">{{ $message }}</p>
        @endforeach
    </div>

    <div class="account-form-actions">
        <button type="submit" class="account-submit" disabled>
            <i class="bi bi-check2-circle"></i><span>Simpan Password</span>
        </button>
    </div>
</form>
