<!doctype html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= $this->renderSection('title') ?> | Layanan Masyarakat</title>
    <link rel="shortcut icon" href="<?= base_url('assets/media/logos/favicon.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=League+Gothic&display=swap">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/global/plugins.bundle.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.bundle.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/layanan-masyarakat.css') ?>">
</head>

<body id="kt_body" class="bg-white position-relative app-blank portal-masyarakat">
    <?= $this->include('masyarakat/partials/header') ?>
    <main class="container py-8">
        <?= $this->include('masyarakat/partials/flash') ?>
        <?= $this->renderSection('content') ?>
    </main>
    <script src="<?= base_url('assets/plugins/global/plugins.bundle.js') ?>"></script>
    <script src="<?= base_url('assets/js/layanan-masyarakat.js') ?>" defer></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>