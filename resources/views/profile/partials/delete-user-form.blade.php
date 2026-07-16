<section>
    <header style="margin-bottom: 1.5rem;">
        <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="btn btn-danger"
        style="background-color: #C62828 !important; border-color: #C62828 !important;"
    >
        <i class="fas fa-exclamation-triangle"></i> {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" style="padding: 2rem;">
            @csrf
            @method('delete')

            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--text-heading); margin: 0 0 0.75rem 0;">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; margin: 0 0 1.5rem 0;">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div style="margin-bottom: 1.5rem;">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control"
                    style="width: 75%;"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" x-on:click="$dispatch('close')" class="btn btn-outline-secondary-theme">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="btn btn-danger" style="background-color: #C62828 !important; border-color: #C62828 !important;">
                    <i class="fas fa-trash-alt"></i> {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
