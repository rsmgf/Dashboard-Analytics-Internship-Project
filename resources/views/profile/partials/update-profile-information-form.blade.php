<form id="profileUpdateForm" method="POST" action="{{ route('profile.update') }}"
    class="account-form" data-original-name="{{ $user->name }}" data-original-email="{{ $user->email }}" novalidate>
    @csrf
    @method('PATCH')

    <div class="account-field">
        <label for="profile_name">Nama</label>
        <input id="profile_name" name="name" type="text"
            class="account-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
            value="{{ old('name', $user->name) }}" autocomplete="name">
        @foreach ($errors->get('name') as $message)
            <p class="account-field-error">{{ $message }}</p>
        @endforeach
    </div>

    <div class="account-field">
        <label for="profile_email">Email</label>
        <input id="profile_email" name="email" type="email"
            class="account-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
            value="{{ old('email', $user->email) }}" autocomplete="email">
        @foreach ($errors->get('email') as $message)
            <p class="account-field-error">{{ $message }}</p>
        @endforeach
    </div>

    <div class="account-form-actions">
        <button type="submit" class="account-submit" disabled>
            <i class="bi bi-check2-circle"></i><span>Simpan Nama dan Email</span>
        </button>
    </div>
</form>
