<?= $this->extend('template/publik/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Dokumen
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<section class="bg-light" style="height: 100%;">
    <div class="container">
        <h3 class="text-primary font-bebas py-2 py-lg-5" style="font-size: 30px">Halaman Download</h3>

        <table class="table table-bordered gy-2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($dokumen as $row): ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td class="fw-bolder">
                            <?= $row['judul'] ?>

                            <p class="text-muted small">Diunggah pada <?= viewDate($row['created_at']) ?></p>
                        </td>
                        <td>
                            <a href="<?= base_url('uploads/' . $row['file']) ?>" target="_blank"
                                class="badge badge-primary">Unduh</a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                endforeach; ?>

            </tbody>
        </table>
        <div>
            <?= $pager->links('default', 'custom_pager') ?>
        </div>
    </div>
</section>

<link rel="stylesheet" href="<?= base_url('assets/css/flickity.min.css'); ?>">
<style>
    .flickity-page-dots {
        bottom: 25px !important;
    }
</style>

<?= $this->endSection() ?>