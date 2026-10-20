<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?>Masuk Portal Masyarakat<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $values = session()->getFlashdata('form_values') ?? []; ?>
<div class="card mx-auto portal-auth">
    <div class="card-body p-8">
        <h1 class="fs-2 mb-3">Portal Masyarakat</h1>
        <p class="text-muted mb-6">Gunakan NIK dan password untuk mengakses layanan serta memantau layanan Anda.</p>
        <form method="post" action="<?= site_url('masyarakat/login') ?>" data-submit-once><?= csrf_field() ?>
            <div class="mb-5"><label for="nik" class="form-label required">NIK</label><input class="form-control"
                    id="nik" name="nik" inputmode="numeric" pattern="[0-9]{16}" minlength="16" maxlength="16"
                    placeholder="Masukkan 16 digit NIK" autocomplete="username"
                    value="<?= esc($values['nik'] ?? '', 'attr') ?>" required></div>
            <div class="mb-5"><label for="password" class="form-label required">Password</label><input
                    class="form-control" type="password" id="password" name="password"
                    placeholder="Masukkan password Anda" autocomplete="current-password" maxlength="72" required></div>
            <?= view('masyarakat/partials/captcha', ['captchaPurpose' => 'login']) ?>
            <button class="btn btn-primary w-100">Masuk</button>
        </form>
        <div class="d-flex flex-wrap justify-content-between mt-6 gap-3"><a
                href="<?= site_url('masyarakat/daftar') ?>">Daftar akun baru</a><a
                href="<?= site_url('masyarakat/lupa-password') ?>">Lupa password?</a></div>
        <?php if (session('masyarakat_otp')): ?><a class="d-block mt-5"
                href="<?= site_url('masyarakat/verifikasi') ?>">Lanjutkan verifikasi OTP</a><?php endif ?>
    </div>
</div>
<?= $this->endSection() ?>