<?= $this->extend('template/publik/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Berita
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<section class="bg-light" style="height: 100%;">
    <div class="container">
        <h3 class="text-primary font-bebas py-2 py-lg-5" style="font-size: 30px">Berita Kejaksaan</h3>
        <div class="row">
            <?php foreach ($berita as $row): ?>
                <div class="col-lg-4 mb-lg-5 mb-5">
                    <!--begin::Feature post-->
                    <div class="card-xl-stretch border border-2 border-grey p-2 mb-2 rounded h-100">
                        <!--begin::Image-->
                        <a href="<?= site_url('berita/detail/' . $row['slug']) ?>">
                            <img style="height: 200px; object-fit: cover;" class="w-100 rounded mb-2"
                                onerror="this.src='<?= base_url('assets/media/logos/noimage.png') ?>';"
                                src="<?= base_url('uploads/' . $row['gambar']) ?>" alt="">
                        </a>
                        <!--end::Image-->
                        <!--begin::Body-->
                        <div class="m-0 p-5">
                            <!--begin::Title-->
                            <a href="<?= site_url('berita/detail/' . $row['slug']) ?>"
                                class="fs-4 text-dark fw-bold text-hover-primary text-dark lh-base"><?= substr($row['judul'], 0, 50) ?></a>
                            <!--end::Title-->
                            <!--begin::Text-->
                            <div class="fw-semibold fs-5 text-gray-600 text-dark my-4">
                                <?= substr(strip_tags($row['isi']), 0, 100) ?> ...
                            </div>
                            <!--end::Text-->
                            <!--begin::Content-->
                            <div class="fs-6 fw-bold">
                                <!--begin::Date-->
                                <span class="text-muted">on <?= viewDate($row['created_at']) ?></span>
                                <!--end::Date-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Feature post-->
                </div>
            <?php endforeach; ?>
        </div>
        <div>
            <?= $pager->links('default', 'custom') ?>
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