<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?>Detail Pengaduan<?= $this->endSection() ?>
<?= $this->section('content') ?>
<a class="btn btn-light mb-5" href="<?= site_url('masyarakat/pengaduan') ?>">Kembali ke daftar aduan</a>
<?= $this->include('masyarakat/partials/pengaduan_detail') ?>
<?= $this->endSection() ?>
