<?= $this->extend('template/admin/layout') ?>
<?= $this->section('title') ?>Penanganan Pengaduan<?= $this->endSection() ?>
<?= $this->section('styles') ?><link rel="stylesheet" href="<?= base_url('assets/css/layanan-masyarakat.css') ?>"><?= $this->endSection() ?>
<?= $this->section('content') ?>
<?= $this->include('masyarakat/partials/flash') ?>
<a class="btn btn-light mb-6" href="<?= site_url('admin/pengaduan') ?>">Kembali ke daftar pengaduan</a>
<?= $this->include('masyarakat/partials/pengaduan_detail') ?>
<?php $next = \App\Models\PengaduanModel::NEXT[$laporan['status']] ?? []; $values = session()->getFlashdata('form_values') ?? []; ?>
<div class="card mt-7"><div class="card-body p-7"><h2 class="fs-4">Tanggapan dan tahapan berikutnya</h2>
<?php if (!$next): ?><p class="text-muted">Penanganan pada portal telah mencapai hasil akhir. Riwayat tersimpan dan tidak dapat diubah melalui halaman ini.</p><?php else: ?>
<p class="text-muted">Tanggapan berikut akan terlihat oleh pelapor. Gunakan keterangan yang sesuai untuk disampaikan kepada masyarakat.</p>
<form action="<?= site_url('admin/pengaduan/' . $laporan['id'] . '/tanggapan') ?>" method="post" data-submit-once><?= csrf_field() ?><input type="hidden" name="version" value="<?= (int) $laporan['version'] ?>">
<div class="mb-5"><label class="form-label required" for="status-pengaduan">Tahap berikutnya</label><select class="form-select" id="status-pengaduan" name="status" required><?php foreach ($next as $status): ?><option value="<?= $status ?>"><?= esc(\App\Models\PengaduanModel::STATUS[$status]) ?></option><?php endforeach ?></select></div>
<div class="mb-5"><label class="form-label required" for="tanggapan">Tanggapan untuk pelapor</label><textarea class="form-control" id="tanggapan" name="tanggapan" minlength="10" maxlength="3000" rows="5" placeholder="Tuliskan hasil penelaahan, perkembangan, alasan penolakan, atau tindak lanjut yang dapat diketahui pelapor" required><?= esc($values['tanggapan'] ?? '') ?></textarea></div>
<div class="mb-5"><label class="form-label" for="tujuan-pelimpahan">Instansi tujuan pelimpahan</label><input class="form-control" id="tujuan-pelimpahan" name="tujuan_pelimpahan" maxlength="255" placeholder="Wajib diisi jika memilih status dilimpahkan" value="<?= esc($values['tujuan_pelimpahan'] ?? '', 'attr') ?>"></div>
<button class="btn btn-primary">Simpan progres dan tanggapan</button></form>
<?php endif ?></div></div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?><script src="<?= base_url('assets/js/layanan-masyarakat.js') ?>" defer></script><?= $this->endSection() ?>
