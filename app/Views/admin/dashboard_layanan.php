<?= $this->extend('template/admin/layout') ?>
<?= $this->section('title') ?>Dashboard Administrator<?= $this->endSection() ?>
<?= $this->section('content') ?>
<h1 class="fs-2 mb-6">Dashboard layanan masyarakat</h1>
<?php if (!$schemaReady): ?><div class="alert alert-warning">Modul layanan masyarakat belum diaktifkan. Pengelola perlu menerapkan migrasi database terlebih dahulu.</div><?php endif ?>
<div class="row g-5 mb-7">
<?php foreach ([['Pengguna masyarakat',$totalUsers,'admin/masyarakat'],['Pengaduan masuk',$totalReports,'admin/pengaduan'],['Menunggu penelaahan',$waiting,'admin/pengaduan?status=diajukan']] as [$label,$count,$url]): ?>
<div class="col-md-4"><a class="card h-100" href="<?= site_url($url) ?>"><div class="card-body"><span class="text-muted"><?= $label ?></span><strong class="d-block fs-2x text-primary mt-3"><?= (int) $count ?></strong></div></a></div>
<?php endforeach ?></div>
<div class="card"><div class="card-body"><h2 class="fs-4 mb-5">Pengelolaan website</h2><div class="d-flex flex-wrap gap-3"><a class="btn btn-light-primary" href="<?= site_url('admin/berita') ?>">Berita</a><a class="btn btn-light-primary" href="<?= site_url('admin/ulasan') ?>">Ulasan pengunjung</a><a class="btn btn-light-primary" href="<?= site_url('admin/setting') ?>">Pengaturan website</a></div></div></div>
<?= $this->endSection() ?>
