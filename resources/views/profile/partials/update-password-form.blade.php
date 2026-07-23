<section>
    <header style="margin-bottom: 1.5rem;">
        <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0;">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control" style="width: 100%;" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
            <x-text-input id="update_password_password" name="password" type="password" class="form-control" style="width: 100%;" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            <x-password-strength-meter inputId="update_password_password" confirmInputId="update_password_password_confirmation" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" style="width: 100%;" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    style="font-size: 0.875rem; color: var(--secondary); font-weight: 600; margin: 0;"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
