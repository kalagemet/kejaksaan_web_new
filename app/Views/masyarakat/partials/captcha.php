<div class="mb-5 portal-captcha">
    <label for="captcha_input" class="form-label required">Kode CAPTCHA</label>
    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
        <img data-captcha-image src="<?= site_url('masyarakat/captcha/' . $captchaPurpose) ?>" width="150" height="40" alt="Kode CAPTCHA berupa lima angka">
        <button type="button" class="btn btn-sm btn-light-primary" data-refresh-captcha aria-label="Muat ulang CAPTCHA"><i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Ganti kode</button>
    </div>
    <input class="form-control" id="captcha_input" name="captcha_input" inputmode="numeric" pattern="[0-9]{5}" minlength="5" maxlength="5" placeholder="Masukkan 5 angka pada gambar" autocomplete="off" required>
</div>
