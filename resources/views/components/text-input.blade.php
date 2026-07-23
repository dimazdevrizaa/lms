@props(['disabled' => false, 'type' => 'text'])

@if($type === 'password')
    <div class="position-relative d-flex align-items-center" style="width: 100%;">
        <input type="password" @disabled($disabled) {{ $attributes->merge(['class' => 'form-control border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm pe-5']) }}>
        <button type="button" class="btn p-0 border-0 position-absolute end-0 me-3 d-flex align-items-center justify-content-center shadow-none text-muted" 
                style="height: 100%; top: 0; background: none; outline: none; z-index: 10;"
                onclick="toggleTextInputPasswordVisibility(this)">
            <i class="far fa-eye"></i>
        </button>
    </div>

    @once
        <script>
            function toggleTextInputPasswordVisibility(btn) {
                const input = btn.previousElementSibling;
                const icon = btn.querySelector('i');
                if (!input) return;
                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) {
                        icon.classList.remove('fa-eye', 'far');
                        icon.classList.add('fa-eye-slash', 'fas');
                    }
                } else {
                    input.type = 'password';
                    if (icon) {
                        icon.classList.remove('fa-eye-slash', 'fas');
                        icon.classList.add('fa-eye', 'far');
                    }
                }
            }
        </script>
    @endonce
@else
    <input type="{{ $type }}" @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm']) }}>
@endif
