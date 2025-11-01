<?= $this->extend('template/publik/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Video
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<section class="bg-light" style="height: 100%;">
    <div class="container">
        <h3 class="text-primary font-bebas py-2 py-lg-5" style="font-size: 30px">Video Kejaksaan</h3>
        <div class="row">
            <?php foreach ($video as $row): ?>
                <div class="col-lg-4 mb-lg-5 mb-5">
                    <!--begin::Feature post-->
                    <div class="card-xl-stretch border border-2 border-grey p-2 mb-2 rounded h-100">
                        <iframe class="rounded" width="100%" height="250"
                            src="https://www.youtube.com/embed/<?= $row['link'] ?>" title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                        </iframe>
                    </div>
                    <!--end::Feature post-->
                </div>
            <?php endforeach; ?>
        </div>
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