<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?>Buat Pengaduan<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $values = session()->getFlashdata('form_values') ?? []; ?>
<h1 class="fs-2 mb-6">Buat laporan pengaduan</h1><?= $this->include('masyarakat/partials/alur') ?>
<div class="card">
    <div class="card-body p-7">
        <form method="post" action="<?= site_url('masyarakat/pengaduan') ?>" enctype="multipart/form-data"
            data-submit-once><?= csrf_field() ?>
            <div class="row g-5">
                <div class="col-md-6"><label class="form-label" for="nama">Nama pelapor</label><input
                        class="form-control" id="nama" name="nama" placeholder="Nama sesuai akun masyarakat"
                        value="<?= esc($user['nama'], 'attr') ?>" readonly></div>
                <div class="col-md-6"><label class="form-label" for="nik">NIK pelapor</label><input class="form-control"
                        id="nik" name="nik" placeholder="16 digit NIK terdaftar" value="<?= esc($user['nik'], 'attr') ?>"
                        readonly></div>
                <div class="col-12"><label class="form-label required" for="alamat">Alamat pelapor</label><textarea
                        class="form-control" id="alamat" name="alamat" rows="3" maxlength="2000"
                        placeholder="Tuliskan alamat lengkap: jalan, RT/RW, kelurahan, kecamatan, kabupaten/kota"
                        required><?= esc($values['alamat'] ?? $user['alamat'] ?? '') ?></textarea></div>
                <div class="col-12"><label class="form-label required" for="uraian">Uraian singkat
                        aduan</label><textarea class="form-control" id="uraian" name="uraian" rows="5" minlength="20"
                        maxlength="3000"
                        placeholder="Jelaskan pokok aduan: apa yang terjadi, kapan, di mana, serta pihak yang terkait (minimal 20 karakter)"
                        required><?= esc($values['uraian'] ?? '') ?></textarea></div>
                <div class="col-12"><label class="form-label required" for="identitas">Dokumen identitas</label><input
                        class="form-control" type="file" id="identitas" name="identitas"
                        accept="application/pdf,image/jpeg,image/png" data-max-mb="2" aria-describedby="identitas-help"
                        required>
                    <p class="form-text" id="identitas-help">Pilih KTP/dokumen identitas dalam format PDF, JPG, atau
                        PNG. Maksimal 2 MB.</p>
                </div>
                <div class="col-12"><label class="form-label required" for="dokumen_aduan">Dokumen uraian aduan
                        lengkap</label><input class="form-control" type="file" id="dokumen_aduan" name="dokumen_aduan"
                        accept="application/pdf" data-max-mb="10" aria-describedby="aduan-help" required>
                    <p class="form-text" id="aduan-help">Pilih PDF berisi uraian lengkap dan bukti dukung yang telah
                        disatukan. Maksimal 10 MB.</p>
                </div>
                <div class="col-12"><label class="form-label" for="bukti">Bukti dukung tambahan (opsional)</label><input
                        class="form-control" type="file" id="bukti" name="bukti[]"
                        accept="application/pdf,image/jpeg,image/png" data-max-mb="10" data-max-count="5" multiple
                        aria-describedby="bukti-help">
                    <p class="form-text" id="bukti-help">Pilih maksimal 5 berkas PDF/JPG/PNG, masing-masing maksimal 10
                        MB.</p>
                </div>
            </div>
            <p class="text-muted mt-5">Nama dan NIK mengikuti akun terverifikasi. Lampiran hanya dapat diakses oleh Anda
                dan admin yang menangani laporan.</p>
            <button class="btn btn-primary">Kirim pengaduan</button> <a class="btn btn-light"
                href="<?= site_url('masyarakat/pengaduan') ?>">Kembali</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>