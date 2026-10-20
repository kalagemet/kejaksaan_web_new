<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?>Dashboard Masyarakat<?= $this->endSection() ?>
<?= $this->section('content') ?>
<h1 class="fs-2 mb-3">Selamat datang, <?= esc($user['nama']) ?></h1>
<p class="text-muted mb-7">Pilih layanan yang Anda butuhkan. Anda memiliki <?= (int) $jumlah ?> laporan pengaduan.</p>
<div class="row g-5">
    <?php foreach ([['masyarakat/pengaduan', 'bi-megaphone', 'Laporan pengaduan masyarakat', 'Buat laporan dan pantau status pengaduan secara online.', ''], ['https://halojpn.kejaksaan.go.id/', 'bi-chat-square-text', 'Konsultasi hukum', 'Konsultasi hukum melalui portal Halo JPN.', ''], ['masyarakat/besuk', 'bi-person-check', 'Pengajuan izin besuk tahanan', 'Pengajuan izin kunjungan tahanan.', 'Coming soon'], ['masyarakat/informasi', 'bi-file-earmark-text', 'Informasi penanganan perkara / informasi publik', 'Permintaan informasi melalui layanan masyarakat.', 'Coming soon']] as [$url, $icon, $title, $description, $badge]):
        $external = strpos($url, 'https://') === 0; ?>
        <div class="col-md-6 col-xl-4"><a class="card h-100 hover-elevate-up text-dark"
                href="<?= $external ? $url : site_url($url) ?>" <?= $external ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                <div class="card-body p-7"><i class="bi <?= $icon ?> fs-2x text-primary"></i>
                    <h2 class="fs-4 mt-5"><?= $title ?></h2>
                    <p class="text-muted"><?= $description ?></p><?php if ($badge): ?><span
                            class="badge badge-light-warning"><?= $badge ?></span><?php endif ?>
                </div>
            </a></div>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>