<?= $this->extend('template/publik/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
<?= $berita['judul'] ?? '' ?>
<?= $this->endSection() ?>

<?= $this->section('meta-berita') ?>
<meta name="title" content="<?= $berita['judul'] ?? '' ?>" />
<meta name="description"
    content="Website resmi satuan kerja <?= $setting !== null ? $setting['nama_satker'] : ''; ?>" />
<meta name="keywords" content="Kejaksaan RI, indonesia, hukum, jaksa, adil, undang undang, Tri Krama Adhyaksa" />
<meta property="twitter:title" content="<?= $berita['judul'] ?? '' ?>" />
<meta property="twitter:description"
    content="Website resmi satuan kerja <?= $setting !== null ? $setting['nama_satker'] : ''; ?>" />
<meta property="twitter:card" content="summary_large_image" />
<meta property="twitter:image" content="<?= base_url('uploads/' . $berita['gambar']) ?>" />
<meta property="og:title" content="<?= $berita['judul'] ?? '' ?>" />
<meta property="og:description"
    content="<?= $berita['judul'] ?? '' ?> | Website resmi satuan kerja <?= $setting !== null ? $setting['nama_satker'] : ''; ?>" />
<meta property="og:image" content="<?= base_url('uploads/' . $berita['gambar']) ?>" />
<meta property="og:url" content="<?= current_url(true); ?>" />
<meta property="og:site_name" content="Website <?= $setting !== null ? $setting['nama_satker'] : ''; ?>" />
<meta property="og:type" content="article" />
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<div class="separator separator-dashed my-5"></div>


<section class="mt-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <img class="rounded my-10" src="<?= base_url('uploads/' . $berita['gambar']) ?>" alt=""
                    style="width: 100%; max-height: 612px; object-fit: cover;">
                <h3 class="text-primary font-bebas fs-1"><?= $berita['judul'] ?? '' ?></h3>
                <h6 class="text-dark opacity-50"><i>diunggah pada <?= $timestamp ?></i></h6>
                <a target="_blank" class="btn btn-sm" style="background-color: var(--bs-blue)"
                    href="https://www.facebook.com/sharer/sharer.php?u=" class="fb-xfbml-parse-ignore"><i
                        class="fab fa-facebook"></i></a>
                <a href="https://twitter.com/intent/tweet?text=YOUR_URL" class="btn btn-sm"
                    style="background-color: var(--bs-cyan)"><i class="fab fa-twitter"></i></a>
                <a href="whatsapp://send?text=" class="btn btn-success btn-sm share-wa"
                    data-action="share/whatsapp/share"><i class="fab fa-whatsapp"></i></a>
                <div style="margin-top:1.5rem;">
                    <?= $berita['isi'] ?? '' ?>
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