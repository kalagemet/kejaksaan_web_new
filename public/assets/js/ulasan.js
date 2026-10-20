(function () {
    'use strict';
    const form = document.getElementById('form-ulasan');
    if (!form) return;
    const message = document.getElementById('ulasan-message');
    const button = document.getElementById('ulasan-submit');
    const stars = form.querySelectorAll('.ulasan-star');
    form.addEventListener('change', function (event) {
        if (event.target.name !== 'rating') return;
        stars.forEach(function (star, index) {
            star.classList.toggle('text-warning', index < Number(event.target.value));
            star.classList.toggle('text-gray-500', index >= Number(event.target.value));
        });
    });
    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        if (button.disabled || !form.reportValidity()) return;
        button.disabled = true;
        button.textContent = 'Mengirim...';
        message.textContent = '';
        form.querySelectorAll('[data-ulasan-error]').forEach(function (node) { node.textContent = ''; });
        form.querySelectorAll('[aria-invalid]').forEach(function (node) { node.removeAttribute('aria-invalid'); });
        try {
            const response = await fetch(form.action, {
                method: 'POST', body: new FormData(form),
                headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
            });
            const data = await response.json();
            if (!response.ok || !data.success) {
                Object.entries(data.errors || {}).forEach(function (entry) {
                    const error = form.querySelector('[data-ulasan-error="' + entry[0] + '"]');
                    if (error) error.textContent = entry[1];
                    const input = form.elements.namedItem(entry[0]);
                    if (input && input.setAttribute) input.setAttribute('aria-invalid', 'true');
                });
                throw new Error(data.message || 'Ulasan gagal dikirim. Silakan muat ulang halaman lalu coba lagi.');
            }
            form.reset();
            stars.forEach(function (star) {
                star.classList.remove('text-warning');
                star.classList.add('text-gray-500');
            });
            message.className = 'alert alert-success';
            message.textContent = data.message;
        } catch (error) {
            message.className = 'alert alert-danger';
            message.textContent = error instanceof SyntaxError
                ? 'Sesi halaman mungkin kedaluwarsa. Muat ulang halaman lalu coba lagi.'
                : error.message;
        } finally {
            button.disabled = false;
            button.textContent = 'Kirim ulasan';
            message.focus();
        }
    });
}());
