<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?><?= esc($title) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card"><div class="card-body text-center p-10"><i class="bi bi-hourglass-split text-primary fs-3x"></i><h1 class="fs-2 mt-5"><?= esc($title) ?></h1><span class="badge badge-light-warning my-4">Coming soon</span><p>Layanan ini sedang dipersiapkan dan belum menerima pengajuan.</p><a class="btn btn-primary" href="<?= site_url('masyarakat') ?>">Kembali ke dashboard</a></div></div>
<?= $this->endSection() ?>
