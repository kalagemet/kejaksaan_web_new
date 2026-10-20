<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?>Profil Masyarakat<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $values = array_merge($user, session()->getFlashdata('form_values') ?? []); ?>
<h1 class="fs-2 mb-6">Profil saya</h1><div class="row g-6"><div class="col-lg-7"><div class="card"><div class="card-body p-7">
<?php if ($user['foto']): ?><img class="portal-avatar mb-5" src="<?= site_url('masyarakat/profil/foto') ?>" alt="Foto profil Anda"><?php endif ?>
<form method="post" action="<?= site_url('masyarakat/profil') ?>" enctype="multipart/form-data" data-submit-once><?= csrf_field() ?>
<div class="mb-5"><label for="nama" class="form-label required">Nama lengkap</label><input class="form-control" id="nama" name="nama" placeholder="Nama lengkap sesuai identitas" minlength="2" maxlength="150" value="<?= esc($values['nama'], 'attr') ?>" required></div>
<div class="mb-5"><label for="nik" class="form-label">NIK</label><input class="form-control" id="nik" value="<?= esc($user['nik'], 'attr') ?>" readonly><p class="form-text">NIK merupakan identitas akun dan tidak dapat diganti.</p></div>
<div class="mb-5"><label for="pekerjaan" class="form-label">Pekerjaan</label><input class="form-control" id="pekerjaan" name="pekerjaan" maxlength="150" placeholder="Contoh: wiraswasta (opsional)" value="<?= esc($values['pekerjaan'] ?? '', 'attr') ?>"></div>
<div class="mb-5"><label for="tanggal_lahir" class="form-label">Tanggal lahir</label><input class="form-control" id="tanggal_lahir" name="tanggal_lahir" type="date" max="<?= date('Y-m-d') ?>" value="<?= esc($values['tanggal_lahir'] ?? '', 'attr') ?>"></div>
<div class="mb-5"><label for="alamat" class="form-label">Alamat</label><textarea class="form-control" id="alamat" name="alamat" maxlength="2000" rows="3" placeholder="Alamat lengkap Anda (opsional)"><?= esc($values['alamat'] ?? '') ?></textarea></div>
<div class="mb-5"><label for="foto" class="form-label">Ganti foto (opsional)</label><input class="form-control" id="foto" name="foto" type="file" accept="image/jpeg,image/png" data-max-mb="2"><p class="form-text">JPG/PNG maksimal 2 MB. Kosongkan untuk mempertahankan foto saat ini.</p></div>
<button class="btn btn-primary">Simpan profil</button></form></div></div></div>
<div class="col-lg-5"><div class="card"><div class="card-body p-7"><h2 class="fs-4">Kontak akun</h2>
<dl><dt>Email terverifikasi</dt><dd class="text-break"><?= esc($user['email']) ?></dd><dt>Nomor WhatsApp</dt><dd><?= esc($user['no_wa']) ?></dd></dl>
<p class="text-muted fs-7">Perubahan email diverifikasi dengan OTP ke email baru. Perubahan nomor WA dikonfirmasi dengan OTP ke email terverifikasi saat ini. Verifikasi kepemilikan nomor melalui WhatsApp belum tersedia.</p>
<form method="post" action="<?= site_url('masyarakat/profil/kontak') ?>" data-submit-once><?= csrf_field() ?>
<div class="mb-5"><label class="form-label" for="jenis">Kontak yang diganti</label><select class="form-select" id="jenis" name="jenis"><option value="email">Email</option><option value="no_wa">Nomor WhatsApp</option></select></div>
<div class="mb-5"><label class="form-label required" for="kontak_baru">Kontak baru</label><input class="form-control" name="kontak_baru" id="kontak_baru" maxlength="254" placeholder="Email baru atau nomor WhatsApp baru" required></div>
<div class="mb-5"><label class="form-label required" for="password_saat_ini">Password saat ini</label><input type="password" class="form-control" name="password_saat_ini" id="password_saat_ini" maxlength="72" autocomplete="current-password" placeholder="Konfirmasi password Anda" required></div>
<button class="btn btn-primary">Kirim OTP perubahan kontak</button></form>
<p class="form-text mt-4">Kontak lama tetap digunakan sampai OTP berhasil diverifikasi. Email dan nomor WA baru harus belum digunakan akun lain.</p>
</div></div></div></div>
<?= $this->endSection() ?>
