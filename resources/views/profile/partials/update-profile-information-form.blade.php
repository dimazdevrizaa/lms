<section>
    <header style="margin-bottom: 1.5rem;">
        <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0;">
            {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.25rem;">
        @csrf
        @method('patch')

        <!-- Profile Picture Section -->
        <div class="d-flex align-items-center gap-4 mb-2">
            <div class="position-relative overflow-hidden rounded-circle bg-success-subtle d-flex align-items-center justify-content-center border" 
                 style="width: 100px; height: 100px; border-width: 2px !important; border-color: var(--primary) !important; flex-shrink: 0;">
                <img id="avatar-preview" src="{{ $user->avatar ? $user->avatar_url : '' }}" alt="Preview" 
                     class="{{ $user->avatar ? '' : 'd-none' }}" 
                     style="width: 100%; height: 100%; object-fit: cover;">
                <div id="avatar-placeholder" class="text-success fw-bold {{ $user->avatar ? 'd-none' : '' }}" 
                     style="font-size: 2.2rem; font-family: 'Plus Jakarta Sans', sans-serif;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </div>
            <div class="flex-grow-1">
                <x-input-label for="avatar" :value="__('Foto Profil')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
                <input id="avatar" name="avatar" type="file" class="form-control" accept="image/jpeg,image/png,image/jpg" style="font-size: 0.875rem;" onchange="previewImage(event)">
                <div class="small text-muted mt-1">Format: JPG, JPEG, PNG. Maks: 2MB.</div>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <script>
            function previewImage(event) {
                const reader = new FileReader();
                reader.onload = function(){
                    const output = document.getElementById('avatar-preview');
                    const placeholder = document.getElementById('avatar-placeholder');
                    output.src = reader.result;
                    output.classList.remove('d-none');
                    if (placeholder) {
                        placeholder.classList.add('d-none');
                    }
                };
                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                }
            }
        </script>

        {{-- ponytail: display read-only academic metadata for the user's role --}}
        @if($user->role === 'siswa' && $user->student)
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <x-input-label :value="__('NIS (Nomor Induk Siswa)')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
                    <x-text-input type="text" class="form-control" :value="$user->student->nis" disabled />
                </div>
                <div class="col-md-6">
                    <x-input-label :value="__('Kelas')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
                    <x-text-input type="text" class="form-control" :value="$user->student->schoolClass?->name" disabled />
                </div>
            </div>
        @elseif($user->role === 'guru' && $user->teacher)
            <div>
                <x-input-label :value="__('NIP (Nomor Induk Pegawai)')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
                <x-text-input type="text" class="form-control" :value="$user->teacher->nip" disabled />
            </div>
        @elseif(($user->role === 'admin' || $user->role === 'tatausaha') && $user->adminStaff)
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <x-input-label :value="__('NIP (Nomor Induk Pegawai)')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
                    <x-text-input type="text" class="form-control" :value="$user->adminStaff->nip" disabled />
                </div>
                <div class="col-md-6">
                    <x-input-label :value="__('Jabatan')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
                    <x-text-input type="text" class="form-control" :value="$user->adminStaff->position" disabled />
                </div>
            </div>
        @endif

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
            <x-text-input id="name" name="name" type="text" class="form-control" style="width: 100%;" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <x-input-label for="email" :value="__('Email')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
                <x-text-input id="email" name="email" type="email" class="form-control" style="width: 100%;" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div style="margin-top: 0.75rem;">
                        <p style="font-size: 0.875rem; color: var(--text-body);">
                            {{ __('Alamat email Anda belum terverifikasi.') }}

                            <button form="send-verification" style="background: none; border: none; padding: 0; color: var(--primary-light); text-decoration: underline; font-size: 0.875rem; cursor: pointer; font-weight: 600;">
                                {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p style="margin-top: 0.5rem; font-weight: 600; font-size: 0.875rem; color: var(--secondary);">
                                {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ponytail: allow editing of phone number stored on student/teacher/admin profile --}}
            <div class="col-md-6">
                <x-input-label for="phone" :value="__('No. Telepon')" style="font-weight: 600; color: var(--primary); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;" />
                <x-text-input id="phone" name="phone" type="text" class="form-control" style="width: 100%;" :value="old('phone', $user->student?->phone ?? $user->teacher?->phone ?? $user->adminStaff?->phone)" autocomplete="tel" placeholder="Contoh: 08123456789" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="background-color: var(--primary); border: none; border-radius: var(--radius-sm); font-weight: 600; padding: 0.5rem 1.5rem;">
                <i class="fas fa-save me-1"></i> Simpan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    style="font-size: 0.875rem; color: var(--secondary); font-weight: 600; margin: 0;"
                >{{ __('Berhasil disimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
