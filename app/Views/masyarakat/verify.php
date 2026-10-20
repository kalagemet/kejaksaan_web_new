<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?>Verifikasi OTP<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card mx-auto portal-auth"><div class="card-body p-8"><h1 class="fs-2">Verifikasi OTP email</h1><p class="text-muted">Masukkan kode 6 angka dari email Anda. OTP berlaku 10 menit dan maksimal 5 percobaan. Periksa juga folder spam.</p>
<form method="post" action="<?= site_url('masyarakat/verifikasi') ?>" data-submit-once><?= csrf_field() ?>
<div class="mb-5"><label class="form-label required" for="otp">Kode OTP</label><input class="form-control fs-2 text-center" id="otp" name="otp" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" autocomplete="one-time-code" placeholder="6 digit OTP" required></div>
<?php if ($purpose === 'reset'): ?>
<div class="mb-5"><label class="form-label required" for="password_baru">Password baru</label><input class="form-control" type="password" name="password_baru" id="password_baru" minlength="8" maxlength="72" autocomplete="new-password" placeholder="Password baru minimal 8 karakter" required></div>
<div class="mb-5"><label class="form-label required" for="konfirmasi_password">Konfirmasi password baru</label><input class="form-control" type="password" name="konfirmasi_password" id="konfirmasi_password" minlength="8" maxlength="72" autocomplete="new-password" placeholder="Ulangi password baru" required></div>
<?php endif ?>
<button class="btn btn-primary w-100">Verifikasi<?= $purpose === 'reset' ? ' dan ganti password' : '' ?></button></form>
<form method="post" action="<?= site_url('masyarakat/otp/kirim-ulang') ?>" class="mt-5" data-submit-once><?= csrf_field() ?><button class="btn btn-light w-100">Kirim ulang OTP</button></form>
<p class="form-text mt-4">Tunggu 60 detik sebelum mengirim ulang. Kode sebelumnya tidak berlaku setelah kode baru diterbitkan.</p>
<a href="<?= site_url(in_array($purpose, ['email_change','wa_change'], true) ? 'masyarakat/profil' : 'masyarakat/login') ?>">Kembali</a></div></div>
<?= $this->endSection() ?>
