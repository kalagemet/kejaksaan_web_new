<?= $this->extend('template/publik/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Beranda
<?= $this->endSection() ?>
<style>
    .carousel-cell .img-full {
        width: 100%;
        /* Lebar penuh dari viewport */
        height: 500px;
        /* Atur tinggi sesuai kebutuhan */
        object-fit: contain;
        /* Agar gambar tidak terdistorsi */
    }
</style>
<!-- isi halaman -->
<?= $this->section('content') ?>
<div class="shadow bg-primary" style="border-bottom: solid 10px; border-color: var(--bs-warning); background-image: url(<?= base_url('assets/media/logos/bg.jpg') ?>); background-size: cover;
    background-blend-mode: soft-light;">
    <!--begin::Container-->
    <div class="p-0 container">
        <div class="main-carousel align-items-center justify-content-center ">
            <?php foreach ($slider as $key => $value) {
                if ($value['title'] == '') { ?>
                    <div class="container carousel-cell w-100" style="height: 500px">
                        <img class="img-full rounded" src="<?= base_url('uploads/' . $value['gambar']) ?>"
                            style="width: 100%; height: 500px; object-fit: contain;" alt="">
                        <!-- <h3 class="fs-5 text-center text-white fw-bold my-5"><?= $value['subtitle'] ?></h3> -->
                    </div>
                <?php } else { ?>
                    <div class="container carousel-cell align-items-center justify-content-center m-auto">
                        <div class="row align-items-center justify-content-center">
                            <div
                                class="col-lg-6 col-md-12 order-2 order-lg-1 text-center text-lg-start align-items-center justify-content-center pb-20 pb-lg-0">
                                <div style="margin: auto;">
                                    <h3 class="fs-3hx text-white fw-bold mb-5 font-bebas"><?= $value['title'] ?></h3>
                                    <div class="fs-5 text-white fw-bold hover-text" data-text="<?= $value['subtitle'] ?>">
                                        <?= $value['subtitle'] ?>
                                    </div>
                                    <?php if ($value['link'] != '') { ?>
                                        <a href="<?= $value['link'] ?>" class="btn btn-lg mt-10 btn-warning">Selengkapnya</a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 order-1 order-lg-2 text-center d-lg-block d-xl-block">
                                <img src="<?= base_url('uploads/' . $value['gambar']) ?>" width="80%"
                                    style="height: 500px;object-fit: contain;" alt="">
                            </div>
                        </div>
                    </div>
                <?php }
                ?>

            <?php } ?>
        </div>


    </div>

</div>
<!--end::Container-->
</div>


<style>
    .parent-hover:hover {
        background-color: #118B50;
        color: white !important;
    }

    .parent-hover:hover .parent-hover-primary {
        color: white !important;
    }
</style>

<div class="p-0 mb-5" style="background-image: url(<?= base_url('assets/media/logos/pattern.png') ?>); background-size: contain;
    background-color: rgba(255, 255, 255, 0.6);
    background-blend-mode: lighten;">

    <div class="card-group container">
        <?php foreach ($berita as $row) { ?>
            <div class="card news-data" style="border-radius: 0px">
                <img onerror="this.src='<?= base_url('assets/media/logos/noimage.png') ?>';" class="card-img-top"
                    src="<?= base_url('uploads/' . $row['gambar']) ?>" alt="image"
                    style="height: 250px; object-fit: cover; border-radius: 0">
                <div class="mask">
                    <h3><small class="text-white"><?= viewDate($row['created_at']) ?></small> <span
                            class="badge badge-primary">
                            Berita </span></h3>
                    <a class="f-5"
                        href="<?= site_url('berita/detail/' . $row['slug']) ?>"><?= limitString($row['judul'], 90) ?></a>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="container">
        <h3 class="text-center font-bebas fs-1 mt-10">LAYANAN <span class="text-primary">PUBLIK</span></h3>
        <div class="row pb-3 sum-container d-flex justify-content-center p-3">
            <?php foreach ($layanan as $key => $value) { ?>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 p-2 ">
                    <a href="<?= $value['link'] ?>" target="_blank"
                        class="card h-100 hover-elevate-up shadow-sm parent-hover">
                        <div class="card-body d-flex align-items">
                            <span>
                                <i class="bi text-primary parent-hover-primary bi-arrow-up-left-circle-fill fs-4hx"></i>
                            </span>

                            <span class="ms-3 text-primary parent-hover-primary fs-4 fw-bold">
                                <?= $value['title'] ?>
                                <br>
                                <span
                                    class="small text-gray-700 parent-hover-primary fs-6 fw-bold"><?= limitString($value['subtitle'], 50) ?></span>
                            </span>

                        </div>
                    </a>
                </div>

            <?php } ?>
        </div>
    </div>
</div>

<section class="my-5 container-fluid">
    <div class="mt-5 container">
        <div class="row">
            <div class="col-lg-4">
                <h3 class="font-bebas fs-1 mt-3">Siaran Pers <span class="text-primary">Kejaksaan</span></h3>

                <?php foreach ($siaran_pers as $row) { ?>
                    <div class="fs-6 border-primary p-3">
                        <a href="<?= site_url('berita/detail/' . $row['slug']) ?>" target="_blank"
                            class="text-primary fw-bold "><?= limitString($row['judul'], 90) ?></a>
                        <p><?= formatDate($row['created_at']) ?></p>
                    </div>
                <?php } ?>

                <div id="beritaKejagung">
                    <!-- Sedang mengambil data -->
                </div>
                <a href="<?= site_url('siaran-pers') ?>" class="btn btn-success btn-sm my-5">Selengkapnya</a><br>

            </div>
            <div class="col-lg-4">
                <h3 class="font-bebas fs-1 mt-3">Berita</h3>
                <?php foreach ($berita_2 as $row) { ?>
                    <div class="row mb-2 fs-6 border-primary p-3">
                        <div class="col-2 col-lg-3 col-md-2">
                            <div class="bg-primary text-white text-center rounded p-2">
                                <?= date('<\b>j</\b></\b\r> M', strtotime($row['created_at'])) ?>
                            </div>
                        </div>
                        <div class="col-10 col-lg-9 col-md-10 p-1">
                            <a class="text-primary fw-bold" href="<?= site_url('berita/detail/' . $row['slug']) ?>"
                                target="_blank">
                                <?= limitString($row['judul'], 90) ?>
                            </a>

                        </div>
                    </div>
                <?php } ?>
                <a href="<?= site_url('berita') ?>" class="btn btn-success btn-sm  my-5">Selengkapnya</a><br>
            </div>

            <div class="col-lg-4">
                <h3 class="font-bebas fs-1 mt-3">Pengumuman</h3>

                <?php foreach ($pengumuman as $row) { ?>
                    <div class="fs-6 border-primary p-3">
                        <a href="<?= site_url('berita/detail/' . $row['slug']) ?>" target="_blank"
                            class="text-primary fw-bold "><?= $row['judul'] ?></a>
                        <p><?= formatDate($row['created_at']) ?></p>
                    </div>
                <?php } ?>
                <a href="<?= site_url('pengumuman') ?>" class="btn btn-success btn-sm my-5">Selengkapnya</a>
            </div>

        </div>
    </div>
</section>

<div class="bg-primary py-10 " style="background-image: url(<?= base_url('assets/media/logos/pattern.png') ?>); background-size: contain;
    background-color: rgba(17, 139, 80, 0.9);
    background-blend-mode: multiply;">
    <!--begin::Container-->
    <div class="container ">
        <h3 class="text-white badge fs-1 badge-warning font-bebas">Tentang
            <?= $setting !== null ? $setting['nama_satker'] : ''; ?>
        </h3>
        <div class="row">
            <?php foreach ($page as $key => $value) { ?>
                <div class="col-lg-6 col-md-6 col-6 my-2">
                    <div class="card card-flush h-100">
                        <div class="card-body p-0">

                            <div class="p-5">
                                <span
                                    class="card-title text-primary fw-bolder py-1 font-bebas fs-2"><?= $value['judul'] ?></span>
                                <br>
                                <p><?= limitString($value['isi']) ?></p> <a href="<?= site_url('page/' . $value['slug']) ?>"
                                    class="btn btn-primary btn-sm">Masuk</a>

                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <!--end::Container-->
</div>

<section class="mt-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between flex-row mb-5">
                    <h3 class="text-primary fs-1 font-bebas">Informasi Publik</h3>
                    <a href="<?= site_url('dokumen') ?>" class="btn btn-primary">Lihat lainnya</a>
                </div>

                <div class="row">
                    <?php foreach ($dokumen as $row) { ?>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <?= limitString($row['judul'], 50) ?>

                                    <p class="text-muted small">Diunggah pada <?= viewDate($row['created_at']) ?></p>

                                    <div class="separator separator-dashed my-2"></div>

                                    <a href="<?= base_url('uploads/' . $row['file']) ?>" target="_blank"
                                        class="badge badge-primary">Unduh</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="my-5">
                    <img class="rounded"
                        src="<?= $setting !== null ? (($setting['gambar_survey'] != '') ? base_url('logo/' . $setting['gambar_survey']) : base_url('assets/media/svg/files/blank-image.svg')) : base_url('assets/media/svg/files/blank-image.svg') ?>"
                        width="100%" alt="">
                </div>

                <div class="separator separator-dashed my-5"></div>

                <div class="text-secondary fs-1 font-bebas" id="firstyt">Memuat Data...</div>
                <div class="row" id="feedyt"></div>

                <div class="text-center ">
                    <span class="fs-1  mt-10 font-bebas"><?= $setting !== null ? $setting['motto'] : ''; ?></span>
                </div>

                <style>
                    .responsive-iframe {
                        width: 100%;
                        height: 400px;
                        /* Bisa disesuaikan dengan viewport */
                        border: none;
                    }
                </style>

                <div class="row mt-5">
                    <div class="col-lg-6 col-md-6 col-6">

                    </div>
                    <div class="col-lg-6 col-md-6 col-6">

                    </div>
                </div>
            </div>
            <div class="col-lg-4 rounded p-4 border border-gray-300" style="background-color: #F8FAFC">
                <!-- <h3 class="text-primary fs-1 font-bebas">Pencarian</h3>
                <input type="text" class="form-control mb-5" placeholder="Masukkan kata kunci"> -->


                <div class="rounded border border-1 border-primary mb-3">
                    <div class="rounded-top px-3 py-1 bg-primary text-white text-center fs-3 font-bebas">Jam Kerja</div>
                    <div class="p-0">

                        <table class="table table-row-bordered  table-striped  gy-1 fs-6 bg-white">
                            <thead style="font-size: smaller">
                                <tr class="bg-warning">
                                    <th class="text-center fw-bold">Hari</th>
                                    <th class="text-center fw-bold">Buka</th>
                                    <th class="text-center fw-bold">Istirahat</th>
                                    <th class="text-center fw-bold">Tutup</th>
                                </tr>
                            </thead>
                            <tr style="font-size: smaller">
                                <td class="fw-bold p-2 ">Senin-Kamis
                                </td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                                <td class="text-end text-primary fw-bolder p-2">12.00-13.00</td>
                                <td class="text-end text-primary fw-bolder p-2">16.00</td>
                            </tr>
                            <tr style="font-size: smaller">
                                <td class="fw-bold p-2 ">Jumat
                                </td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                                <td class="text-end text-primary fw-bolder p-2">11.30-13.00</td>
                                <td class="text-end text-primary fw-bolder p-2">16.30</td>
                            </tr>

                        </table>
                    </div>
                </div>

                <h5 class="text-primary fs-1 font-bebas">INFOGRAFIS</h5>
                <section class="splide splide_role_model" aria-label="Basic Structure Example">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ($agen as $key => $value): ?>
                                <li class="splide__slide">
                                    <img src="<?= base_url('uploads/' . $value['foto']) ?>" width="100%" alt="">
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>

                <div class="separator separator-dashed my-2"></div>

                <h5 class="text-primary fs-1 font-bebas">HASIL SURVEY</h5>
                <section class="splide splide_survey" aria-label="Basic Structure Example">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <li class="splide__slide">
                                <img src="<?= $setting !== null ? (($setting['gambar_ikm'] != '') ? base_url('logo/' . $setting['gambar_ikm']) : base_url('assets/media/svg/files/blank-image.svg')) : base_url('assets/media/svg/files/blank-image.svg') ?>"
                                    width="100%" alt="">
                            </li>
                            <li class="splide__slide">
                                <img src="<?= $setting !== null ? (($setting['gambar_ipak'] != '') ? base_url('logo/' . $setting['gambar_ipak']) : base_url('assets/media/svg/files/blank-image.svg')) : base_url('assets/media/svg/files/blank-image.svg') ?>"
                                    width="100%" alt="">
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="<?= base_url('assets/js/flickity.pkgd.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/flickity.min.css'); ?>">
<script src="<?= base_url('assets/js/splide.min.js'); ?>"></script>
<link href="<?= base_url('assets/css/splide.min.css'); ?>" rel="stylesheet">

<script>
    $('.main-carousel').flickity({
        // options
        cellAlign: 'left',
        prevNextButtons: false,
        contain: true,
        autoPlay: 4000,
        interval: 4000
    });

    new Splide('.splide_survey', {
        type: 'loop', // Slider loop (opsional)
        perPage: 1, // Tampilkan 1 item per halaman
        autoplay: true, // Slider otomatis berjalan
        interval: 4000, // Interval 3 detik
        arrows: false, // Panah navigasi
        pagination: true, // Pagination (bullets)
    }).mount();

    // Inisialisasi untuk slider role model
    var splideRoleModel = new Splide('.splide_role_model', {
        type: 'loop',
        perPage: 1,
        autoplay: true,
        interval: 4000,
        arrows: false,
        // pagination: true,
    });
    splideRoleModel.mount();

    $(document).ready(function () {
        $.ajax({
            url: '<?= site_url('getFeedIG') ?>', // Endpoint ke controller
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                let card = '';
                if (response.success && response.data.length > 0) {
                    response.data.forEach((item, index) => {
                        if (item.media_type !== "VIDEO") {
                            card = `
                            <li class="splide__slide">
                                <img src="${item.media_url}" width="100%" alt="">
                            </li>`;
                        }
                        splideRoleModel.add(card, index);
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
        $.ajax({
            url: '<?= site_url('getFeedYT') ?>', // Endpoint ke controller
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                let card = `<div class="col-lg-4 col-12">
                            <h5 class="text-primary fs-1 mt-5 font-bebas">GALERI VIDEO</h5>
                            <p>Dokumentasi video kegiatan, agenda, dan program yang telah dilakukan oleh
                                <?= $setting['nama_satker'] ?> dalam mendukung Zona Integritas menuju Wilayah Bebas Korupsi
                                (WBK) dan Wilayah Birokrasi Bersih dan Melayani (WBBM).
                            </p>
                            <a href="<?= site_url('video') ?>" class="btn btn-primary">Lihat lainnya</a>
                        </div>`;
                if (response.success && response.data.length > 0) {
                    response.data.forEach((item, index) => {
                        if (index == 0) {
                            $('#firstyt').empty();
                            $('#firstyt').html(`<iframe class="rounded" width="100%" height="315"
                                src="https://www.youtube.com/embed/${item.link}" title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                            </iframe>`);
                        } else {
                            card += `<div class="col-lg-4 col-6 d-flex justify-content-center align-items-center" >
                                <iframe class = "rounded"
                                    width = "100%"
                                    height = "150"
                                    src = "https://www.youtube.com/embed/${item.link}"
                                    title = "YouTube video player"
                                    frameborder = "0"
                                    allow ="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy = "strict-origin-when-cross-origin"
                                    allowfullscreen>
                                </iframe>
                            </div>`;
                        }
                    });
                    $("#feedyt").append(card);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
        $.ajax({
            url: '<?= site_url('dataBeritaKejagung') ?>', // Endpoint ke controller
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const container = $('#beritaKejagung');
                container.empty(); // Kosongkan container

                if (response.success && response.data.length > 0) {
                    response.data.forEach((item, index) => {
                        const card = `
                           <div class="fs-6  border-primary border-bottom-dashed p-3">
                                <a href="${item.link}"
                                    target="_blank" class="text-primary fw-bold ">${item.title}</a>
                                    <p>${item.date}</p>
                            </div>`;
                        container.append(card);
                    });
                } else {
                    // container.html('<div class="col-12 text-center"><p>Data tidak tersedia</p></div>');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                // $('#beritaKejagung').html('<div class="col-12 text-center"><p>Gagal memuat data.</p></div>');
            }
        });
    });
</script>

<?= $this->endSection() ?>