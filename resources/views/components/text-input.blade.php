@props(['disabled' => false, 'type' => 'text'])

@if($type === 'password')
    <div class="position-relative d-flex align-items-center" style="width: 100%;">
        <input type="password" @disabled($disabled) {{ $attributes->merge(['class' => 'form-control border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm pe-5']) }}>
        <button type="button" class="btn p-0 border-0 position-absolute end-0 me-3 d-flex align-items-center justify-content-center shadow-none text-muted" 
                style="height: 100%; top: 0; background: none; outline: none; z-index: 10;"
                onclick="togglePasswordVisibility(this)">
            <i class="fas fa-eye"></i>
        </button>
    </div>

    @once
        <script>
            function togglePasswordVisibility(btn) {
                const input = btn.previousElementSibling;
                const icon = btn.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        </script>
    @endonce
@else
    <input type="{{ $type }}" @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm']) }}>
@endif
