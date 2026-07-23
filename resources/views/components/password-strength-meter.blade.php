@props(['inputId' => 'password', 'confirmInputId' => null])

<div id="{{ $inputId }}-strength-wrapper" class="mt-2 password-strength-wrapper" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <!-- Progress Bar & Score -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <span class="small fw-semibold text-muted" style="font-size: 0.78rem;">Kekuatan Password:</span>
        <span id="{{ $inputId }}-strength-label" class="badge bg-secondary-subtle text-secondary fw-bold" style="font-size: 0.72rem;">Belum Diisi</span>
    </div>
    <div class="progress mb-2" style="height: 6px; background-color: #e9ecef; border-radius: 4px; overflow: hidden;">
        <div id="{{ $inputId }}-strength-bar" class="progress-bar" role="progressbar" style="width: 0%; transition: width 0.3s ease, background-color 0.3s ease;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <!-- Requirements Checklist -->
    <div class="p-2 rounded-3 bg-light border" style="font-size: 0.75rem; color: #495057;">
        <div class="row row-cols-1 row-cols-sm-2 g-1">
            <div class="col d-flex align-items-center gap-1.5" id="{{ $inputId }}-rule-length">
                <i class="far fa-circle text-muted me-1" style="font-size: 0.7rem;"></i> Minimal 8 karakter
            </div>
            <div class="col d-flex align-items-center gap-1.5" id="{{ $inputId }}-rule-upper">
                <i class="far fa-circle text-muted me-1" style="font-size: 0.7rem;"></i> Huruf besar (A-Z)
            </div>
            <div class="col d-flex align-items-center gap-1.5" id="{{ $inputId }}-rule-lower">
                <i class="far fa-circle text-muted me-1" style="font-size: 0.7rem;"></i> Huruf kecil (a-z)
            </div>
            <div class="col d-flex align-items-center gap-1.5" id="{{ $inputId }}-rule-number">
                <i class="far fa-circle text-muted me-1" style="font-size: 0.7rem;"></i> Angka (0-9)
            </div>
            <div class="col d-flex align-items-center gap-1.5" id="{{ $inputId }}-rule-special">
                <i class="far fa-circle text-muted me-1" style="font-size: 0.7rem;"></i> Karakter khusus (!@#$)
            </div>
            @if($confirmInputId)
            <div class="col d-flex align-items-center gap-1.5" id="{{ $inputId }}-rule-match">
                <i class="far fa-circle text-muted me-1" style="font-size: 0.7rem;"></i> Konfirmasi cocok
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('{{ $inputId }}');
    const confirmInput = document.getElementById('{{ $confirmInputId }}');
    const bar = document.getElementById('{{ $inputId }}-strength-bar');
    const label = document.getElementById('{{ $inputId }}-strength-label');

    const ruleLength = document.getElementById('{{ $inputId }}-rule-length');
    const ruleUpper = document.getElementById('{{ $inputId }}-rule-upper');
    const ruleLower = document.getElementById('{{ $inputId }}-rule-lower');
    const ruleNumber = document.getElementById('{{ $inputId }}-rule-number');
    const ruleSpecial = document.getElementById('{{ $inputId }}-rule-special');
    const ruleMatch = document.getElementById('{{ $inputId }}-rule-match');

    function updateRule(element, passed) {
        if (!element) return;
        const icon = element.querySelector('i');
        if (passed) {
            element.classList.remove('text-muted');
            element.classList.add('text-success', 'fw-semibold');
            if (icon) icon.className = 'fas fa-check-circle text-success me-1';
        } else {
            element.classList.remove('text-success', 'fw-semibold');
            element.classList.add('text-muted');
            if (icon) icon.className = 'far fa-circle text-muted me-1';
        }
    }

    function evaluatePassword() {
        if (!input) return;
        const val = input.value;
        let score = 0;

        const hasLength = val.length >= 8;
        const hasUpper = /[A-Z]/.test(val);
        const hasLower = /[a-z]/.test(val);
        const hasNumber = /[0-9]/.test(val);
        const hasSpecial = /[^A-Za-z0-9]/.test(val);

        updateRule(ruleLength, hasLength);
        updateRule(ruleUpper, hasUpper);
        updateRule(ruleLower, hasLower);
        updateRule(ruleNumber, hasNumber);
        updateRule(ruleSpecial, hasSpecial);

        if (confirmInput) {
            const hasMatch = val.length > 0 && val === confirmInput.value;
            updateRule(ruleMatch, hasMatch);
        }

        if (val.length === 0) {
            bar.style.width = '0%';
            bar.className = 'progress-bar';
            label.textContent = 'Belum Diisi';
            label.className = 'badge bg-secondary-subtle text-secondary fw-bold';
            return;
        }

        if (hasLength) score++;
        if (hasUpper) score++;
        if (hasLower) score++;
        if (hasNumber) score++;
        if (hasSpecial) score++;

        if (score <= 1) {
            bar.style.width = '20%';
            bar.style.backgroundColor = '#dc3545';
            label.textContent = 'Sangat Lemah';
            label.className = 'badge bg-danger-subtle text-danger fw-bold';
        } else if (score === 2) {
            bar.style.width = '40%';
            bar.style.backgroundColor = '#fd7e14';
            label.textContent = 'Lemah';
            label.className = 'badge bg-warning-subtle text-dark fw-bold';
        } else if (score === 3) {
            bar.style.width = '65%';
            bar.style.backgroundColor = '#ffc107';
            label.textContent = 'Sedang';
            label.className = 'badge bg-warning text-dark fw-bold';
        } else if (score === 4) {
            bar.style.width = '85%';
            bar.style.backgroundColor = '#0d6efd';
            label.textContent = 'Kuat';
            label.className = 'badge bg-primary-subtle text-primary fw-bold';
        } else {
            bar.style.width = '100%';
            bar.style.backgroundColor = '#198754';
            label.textContent = 'Sangat Kuat 🔥';
            label.className = 'badge bg-success text-white fw-bold';
        }
    }

    if (input) {
        input.addEventListener('input', evaluatePassword);
    }
    if (confirmInput) {
        confirmInput.addEventListener('input', evaluatePassword);
    }
});
</script>
