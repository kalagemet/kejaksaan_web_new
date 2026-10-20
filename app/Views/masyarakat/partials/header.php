<?php
$setting = $setting ?? [];
$logo = !empty($setting['logo_satker']) ? base_url('logo/' . $setting['logo_satker']) : base_url('assets/media/logos/logo.png');
?>
<header>
    <div class="pt-3 portal-public-topbar">
        <div class="container text-center mb-3">
            <div class="marquee fw-bold text-white"><span class="fs-6"><?= esc($setting['running_text'] ?? '') ?></span>
            </div>
        </div>
        <div class="bg-white border-bottom border-gray-300 rounded-3 rounded-bottom-0">
            <div class="container d-flex justify-content-between px-5 py-5">
                <div class="badge text-dark fs-6 d-flex align-items-center"><?= viewNowDate() ?></div>
                <div class="d-none d-lg-flex align-items-center gap-4 text-dark fs-6 fw-bold">
                    <span><i class="bi bi-envelope fs-3 text-dark me-2"
                            aria-hidden="true"></i><?= esc($setting['email_satker'] ?? '') ?></span>
                    <span><i class="bi bi-telephone fs-3 text-dark me-2"
                            aria-hidden="true"></i><?= esc($setting['phone_satker'] ?? '') ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="landing-header text-dark border-bottom border-gray-300">
        <div class="container d-flex align-items-center justify-content-between py-5 gap-4">
            <a href="<?= site_url() ?>" class="d-flex align-items-center text-dark portal-brand">
                <img src="<?= esc($logo, 'attr') ?>" alt="Lambang Kejaksaan" class="logo-default h-40px h-lg-70px">
                <span class="ms-3 fw-bold portal-brand-name">
                    <span class="d-block fs-2 font-bebas">Kejaksaan Republik Indonesia</span>
                    <span
                        class="d-block fs-1 fw-bolder text-primary font-bebas"><?= esc($setting['nama_satker'] ?? 'Layanan Masyarakat') ?></span>
                </span>
            </a>
            <img src="<?= base_url('assets/media/logos/trapsila_berakhlak.png') ?>" class="h-50px d-none d-lg-block"
                alt="Trapsila BerAKHLAK">
        </div>
    </div>
    <nav class="border-bottom border-gray-300" aria-label="Navigasi masyarakat">
        <div class="container d-flex flex-wrap align-items-center justify-content-center gap-6 py-4 fw-semibold">
            <?php if (session('masyarakat_id')): ?>
                <a class="text-dark text-hover-primary" href="<?= site_url('masyarakat') ?>">Dashboard</a>
                <a class="text-dark text-hover-primary" href="<?= site_url('masyarakat/profil') ?>">Profil</a>
                <form method="post" action="<?= site_url('masyarakat/keluar') ?>" class="m-0"><?= csrf_field() ?><button
                        class="btn btn-sm btn-light-danger">Keluar</button></form>
            <?php else: ?>
                <!-- <a class="text-dark text-hover-primary" href="<?= site_url('masyarakat/login') ?>">Masuk</a>
                <a class="text-dark text-hover-primary" href="<?= site_url('masyarakat/daftar') ?>">Daftar akun</a> -->
            <?php endif ?>
        </div>
    </nav>
</header>