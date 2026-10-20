<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?>Daftar Akun Masyarakat<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $values = session()->getFlashdata('form_values') ?? []; ?>
<div class="card mx-auto portal-register"><div class="card-body p-8">
<h1 class="fs-2">Daftar akun masyarakat</h1><p class="text-muted">Kolom bertanda * wajib diisi. Aktivasi akun menggunakan OTP yang dikirim ke email Anda.</p>
<form method="post" action="<?= site_url('masyarakat/daftar') ?>" enctype="multipart/form-data" data-submit-once><?= csrf_field() ?>
<div class="row g-5">
<?php foreach ([
    'nama' => ['Nama lengkap', 'text', 'Nama lengkap sesuai dokumen identitas', 150],
    'nik' => ['NIK', 'text', '16 digit NIK, tanpa spasi', 16],
    'no_wa' => ['Nomor WhatsApp', 'tel', 'Contoh: 081234567890', 25],
    'email' => ['Email', 'email', 'Contoh: nama@email.com', 254],
] as $key => $field): ?>
<div class="col-md-6"><label for="<?= $key ?>" class="form-label required"><?= $field[0] ?></label><input class="form-control" id="<?= $key ?>" name="<?= $key ?>" type="<?= $field[1] ?>" placeholder="<?= $field[2] ?>" maxlength="<?= $field[3] ?>" value="<?= esc($values[$key] ?? '', 'attr') ?>" <?= $key === 'nik' ? 'inputmode="numeric" pattern="[0-9]{16}"' : '' ?> required></div>
<?php endforeach ?>
<div class="col-md-6"><label class="form-label required" for="password">Password</label><input class="form-control" id="password" type="password" name="password" minlength="8" maxlength="72" placeholder="Buat password minimal 8 karakter" autocomplete="new-password" required></div>
<div class="col-md-6"><label class="form-label required" for="konfirmasi_password">Konfirmasi password</label><input class="form-control" id="konfirmasi_password" type="password" name="konfirmasi_password" minlength="8" maxlength="72" placeholder="Ulangi password yang dibuat" autocomplete="new-password" required></div>
<div class="col-md-6"><label class="form-label" for="pekerjaan">Pekerjaan (opsional)</label><input class="form-control" id="pekerjaan" name="pekerjaan" maxlength="150" placeholder="Contoh: wiraswasta" value="<?= esc($values['pekerjaan'] ?? '', 'attr') ?>"></div>
<div class="col-md-6"><label class="form-label" for="tanggal_lahir">Tanggal lahir (opsional)</label><input class="form-control" type="date" id="tanggal_lahir" name="tanggal_lahir" max="<?= date('Y-m-d') ?>" value="<?= esc($values['tanggal_lahir'] ?? '', 'attr') ?>"></div>
<div class="col-12"><label class="form-label" for="alamat">Alamat (opsional)</label><textarea class="form-control" name="alamat" id="alamat" rows="3" maxlength="2000" placeholder="Jalan, RT/RW, kelurahan, kecamatan, kabupaten/kota"><?= esc($values['alamat'] ?? '') ?></textarea></div>
<div class="col-12"><label class="form-label" for="foto">Foto profil (opsional)</label><input class="form-control" type="file" name="foto" id="foto" accept="image/jpeg,image/png" data-max-mb="2"><p class="form-text">JPG atau PNG, maksimal 2 MB.</p></div>
</div>
<p class="text-muted fs-7 mt-5">NIK, email, dan nomor WhatsApp tidak boleh sudah digunakan akun lain. OTP saat ini dikirim melalui email; pengiriman WhatsApp akan ditambahkan kemudian.</p>
<?= view('masyarakat/partials/captcha', ['captchaPurpose' => 'register']) ?>
<button class="btn btn-primary">Daftar dan kirim OTP email</button> <a class="btn btn-light" href="<?= site_url('masyarakat/login') ?>">Sudah punya akun?</a>
</form></div></div>
<?= $this->endSection() ?>
