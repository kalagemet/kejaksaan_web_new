(function () {
    'use strict';
    document.querySelectorAll('[data-refresh-captcha]').forEach(function (button) {
        button.addEventListener('click', function () {
            const group = button.closest('.portal-captcha');
            const image = group.querySelector('[data-captcha-image]');
            const url = new URL(image.src);
            url.searchParams.set('t', Date.now());
            image.src = url.href;
            const input = group.querySelector('[name="captcha_input"]');
            input.value = '';
            input.focus();
        });
    });
    document.querySelectorAll('input[type="file"][data-max-mb]').forEach(function (input) {
        input.addEventListener('change', function () {
            input.setCustomValidity('');
            const files = Array.from(input.files || []);
            const count = Number(input.dataset.maxCount || 1);
            const limit = Number(input.dataset.maxMb) * 1024 * 1024;
            if (files.length > count) input.setCustomValidity('Pilih maksimal ' + count + ' berkas.');
            else if (files.some(function (file) { return file.size > limit; })) input.setCustomValidity('Ukuran setiap berkas maksimal ' + input.dataset.maxMb + ' MB.');
            if (!input.checkValidity()) input.reportValidity();
        });
    });
    document.querySelectorAll('form[data-submit-once]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (form.dataset.submitting === '1') { event.preventDefault(); return; }
            if (!form.checkValidity()) return;
            form.dataset.submitting = '1';
            form.querySelectorAll('button[type="submit"],button:not([type])').forEach(function (button) { button.disabled = true; });
        });
    });
    window.addEventListener('pageshow', function () {
        document.querySelectorAll('form[data-submit-once]').forEach(function (form) {
            delete form.dataset.submitting;
            form.querySelectorAll('button').forEach(function (button) { button.disabled = false; });
        });
    });
    const status = document.getElementById('status-pengaduan');
    const destination = document.getElementById('tujuan-pelimpahan');
    if (status && destination) {
        const update = function () { destination.required = status.value === 'dilimpahkan'; };
        status.addEventListener('change', update);
        update();
    }
}());
