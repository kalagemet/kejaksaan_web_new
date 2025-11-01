<?= $this->extend('template/publik/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Beranda
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<div class="separator separator-dashed my-5"></div>


<section class="mt-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="text-primary font-bebas fs-1"><?= $berita['judul'] ?? '' ?></h3>
                <div style="margin-top:1.5rem;">
                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                        style="background-image:url('<?= base_url('uploads/' . $berita['gambar']) ?>')">
                    </div>
                </div>
                <div style="margin-top:1.5rem;">
                    <?= $berita['isi'] ?? '' ?>
                </div>
            </div>
            <div class="col-lg-4 rounded p-4" style="background-color: #F5E8C7">
                <h3 class="text-primary fs-1 font-bebas">Pencarian</h3>
                <input type="text" class="form-control mb-5" placeholder="Masukkan kata kunci">
                <h3 class="text-primary fs-1 font-bebas">Jam Pelayanan PTSP</h3>
                <img src="https://drive.google.com/thumbnail?id=1K8sfFd36PMPl5YcfFCaVbPjO7xqKimuv&sz=s4000" width="100%"
                    alt="">

                <div class="separator separator-dashed my-2"></div>
                <h3 class="text-primary fs-1 font-bebas">Statistik pengunjung</h3>
                <table class="table table-row-bordered border-dark gy-1 fs-6">
                    <tr>
                        <td class="fw-bold p-2 ">
                            <i class="bi bi-calendar2-week"></i> Hari ini
                        </td>
                        <td class="text-end text-primary fw-bolder p-2"><?= $visitor['today'] ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold p-2">
                            <i class="bi bi-calendar2-week"></i> Kemarin
                        </td>
                        <td class="text-end text-primary fw-bolder p-2"><?= $visitor['yesterday'] ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold p-2 ">
                            <i class="bi bi-calendar2-week"></i> Minggu ini
                        </td>
                        <td class="text-end text-primary fw-bolder p-2"><?= $visitor['this_week'] ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold p-2 ">
                            <i class="bi bi-calendar2-week"></i> Bulan ini
                        </td>
                        <td class="text-end text-primary fw-bolder p-2"><?= $visitor['this_month'] ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold p-2 ">
                            <i class="bi bi-calendar2-week"></i> Total
                        </td>
                        <td class="text-end text-primary fw-bolder p-2"><?= $visitor['total'] ?></td>
                    </tr>
                </table>
                <div class="separator separator-dashed my-2"></div>
                <h3 class="text-primary fs-1 font-bebas">Pengunjung Online</h3>
                <div class="fs-1 fw-bolder text-primary">
                    <i class="fa fa-users text-primary fs-3"></i>
                    <?= $visitor['active_users'] ?> Orang
                </div>
            </div>
        </div>

    </div>
</section>



</section>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="<?= base_url('assets/js/flickity.pkgd.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/flickity.min.css'); ?>">
<script src="
<?= base_url('assets/js/splide.min.js'); ?>
"></script>
<link href="
<?= base_url('assets/css/splide.min.css'); ?>
" rel="stylesheet">

<script>
    $('.main-carousel').flickity({
        // options
        cellAlign: 'left',
        contain: true
    });

    var splide = new Splide('.splide', {
        perPage: 6,
        //   rewind : true,
        type: 'loop',
        autoplay: 'start',
        breakpoints: {
            1024: { // Layar sedang, maksimal 1024px
                perPage: 5, // Tampilkan 5 item
            },
            768: { // Layar kecil, maksimal 768px
                perPage: 3, // Tampilkan 3 item
            }
        }
    });

    splide.mount();
</script>

<?= $this->endSection() ?>