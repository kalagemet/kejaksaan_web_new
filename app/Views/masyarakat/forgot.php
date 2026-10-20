<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?>Lupa Password<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card mx-auto portal-auth"><div class="card-body p-8"><h1 class="fs-2">Lupa password</h1><p class="text-muted">Masukkan NIK dan email terdaftar. OTP pemulihan dikirim ke email yang telah terverifikasi.</p>
<form action="<?= site_url('masyarakat/lupa-password') ?>" method="post" data-submit-once><?= csrf_field() ?>
<div class="mb-5"><label class="form-label required" for="nik">NIK</label><input class="form-control" id="nik" name="nik" pattern="[0-9]{16}" maxlength="16" inputmode="numeric" placeholder="Masukkan 16 digit NIK terdaftar" required></div>
<div class="mb-5"><label class="form-label required" for="email">Email terdaftar</label><input type="email" class="form-control" id="email" name="email" maxlength="254" placeholder="Masukkan email terverifikasi Anda" required></div>
<button class="btn btn-primary">Kirim OTP email</button> <a href="<?= site_url('masyarakat/login') ?>" class="btn btn-light">Kembali</a></form>
<p class="form-text mt-5">Pemulihan melalui WhatsApp belum tersedia.</p></div></div>
<?= $this->endSection() ?>
