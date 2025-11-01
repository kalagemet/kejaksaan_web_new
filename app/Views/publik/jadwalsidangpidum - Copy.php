<?= $this->extend('template/publik/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Jadwal Sidang Perkara Tindak Pidana Umum
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<div class="separator separator-dashed my-5"></div>


<section class="mt-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="text-primary font-bebas fs-1">Beranda</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                    culpa qui officia deserunt mollit anim id est laborum.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Vivamus tincidunt orci nisi,
                    non vestibulum purus convallis ac. Fusce vel ipsum quis tortor fermentum malesuada. Suspendisse
                    potenti. Mauris et orci vitae libero euismod tempus. Integer vitae tristique magna, ac scelerisque
                    elit. Donec eget diam et orci tempor tristique ac sed odio. Curabitur malesuada augue eu nunc
                    ullamcorper, at tempus velit iaculis.</p>
                <p>Curabitur vulputate malesuada felis, sed euismod turpis volutpat eu. Fusce convallis tristique nisi,
                    in sodales eros fermentum ac. Integer feugiat quam ac sapien bibendum, nec sollicitudin justo
                    eleifend. Nulla facilisi. Ut at erat in eros pharetra vehicula ac sed orci. Etiam condimentum, risus
                    sit amet rhoncus eleifend, ante lectus maximus erat, non lobortis enim urna sit amet mi. Donec
                    bibendum ante a libero tincidunt suscipit.</p>
                <p>Aliquam erat volutpat. Fusce ultricies varius justo, id lobortis lorem euismod non. Suspendisse ac
                    urna fringilla, fermentum nisi sed, tristique magna. Nam id lorem sit amet ipsum auctor rutrum.
                    Aliquam tempor risus vel ligula maximus, nec fringilla neque consequat. Phasellus sit amet leo et
                    lacus efficitur pharetra a ac orci. Curabitur vulputate quam ac sollicitudin feugiat. Donec iaculis
                    libero quis nulla pharetra, non convallis leo dictum.</p>
                <p>Sed vitae tortor et erat ullamcorper ullamcorper. Cras volutpat mi a nisi pharetra, eget fringilla
                    metus suscipit. Donec tempus gravida magna sit amet lacinia. Ut suscipit nec mi in sodales. Sed nec
                    ex ac lectus tincidunt placerat eget eget turpis. Vivamus id nisl quis nisi sodales vulputate. Morbi
                    at justo non ante scelerisque scelerisque. Nunc mollis mi ac orci laoreet, non feugiat magna tempus.
                </p>
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