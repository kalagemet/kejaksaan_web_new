<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $this->renderSection('title') ?></title>
    <meta charset="utf-8" />
    <meta name="description" content="Sistem Informasi Piutang PNBP - Kejaksaan RI" />
    <meta name="keywords" content="piutang, kejaksaan, uang pengganti, pnbp" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="https://www.kejaksaan.go.id/assets/img/logo-kejak.png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="<?=base_url()?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url()?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <style>
    [required]::after {
        content: " *";
        color: red;
        font-weight: bold;
    }
    </style>
</head>

<body id="kt_body" class="app-blank" style="background-image: url(<?= $_baseurl ?>/assets/media/logos/pattern.png);">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="card card-flush w-lg-400px w-400px m-auto border shadow">
            <div class="card-body">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="<?=base_url()?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?=base_url()?>assets/js/scripts.bundle.js"></script>
    <?= $this->renderSection('script') ?>
</body>

</html>