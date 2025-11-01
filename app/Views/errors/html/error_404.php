<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= lang('Errors.pageNotFound') ?></title>

    <link href="<?= base_url() ?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />

</head>

<body id="kt_body" class="app-blank">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="card card-flush w-lg-400px w-400px m-auto border shadow">
            <div class="card-body text-center">
                <div class="wrap mb-10">
                    <img src="<?= base_url('assets/media/logos/404.png') ?>" alt="">

                    <p class="mt-10">
                        <?php if (ENVIRONMENT !== 'production'): ?>
                            <?= nl2br(esc($message)) ?>
                        <?php else: ?>
                            <?= lang('Errors.sorryCannotFind') ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <a style="color: var(--bs-primary)" href="<?= site_url() ?>">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>

</body>


</html>