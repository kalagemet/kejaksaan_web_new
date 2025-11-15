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
                <img class="rounded my-10" src="<?= base_url('uploads/' . $berita['gambar']) ?>" alt=""
                    style="width: 100%; max-height: 612px; object-fit: cover;">
                <h3 class="text-primary font-bebas fs-1"><?= $berita['judul'] ?? '' ?></h3>
                <h6 class="text-dark opacity-50"><i>diunggah pada <?= $timestamp ?></i></h6>
                <a target="_blank" class="btn btn-sm share-fb fb-xfbml-parse-ignore"
                    style="background-color: var(--bs-blue)"><i class="fab fa-facebook"
                        style="color: white;font-size: large;"></i></a>
                <a class="btn btn-sm share-twt" style="background-color: black">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-twitter-x"
                        viewBox="0 0 16 16">
                        <path
                            d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                    </svg>
                </a>
                <a class="btn btn-success btn-sm share-wa" data-action="share/whatsapp/share"><i class="fab fa-whatsapp"
                        style="color: white;font-size: large;"></i></a>
                <div style="margin-top:1.5rem;">
                    <?= $berita['isi'] ?? '' ?>
                </div>
                <?= $bacajuga ? '
                <div style="background-color: #F8FAFC;border-left: solid 7px var(--bs-primary); padding-left: 10px;">
                    <span class="text-dark opacity-50 font-bebas fs-3">Baca Juga: </span><br/>
                    <a style="text-decoration: underline;" class="text-primary font-bebas fs-3"
                        href="' . site_url('berita/detail/') . $bacajuga['slug'] . '">
                        ' . $bacajuga['judul'] . '
                    </a>
                </div>' : "" ?>
            </div>
            <div class="col-lg-4 d-none d-lg-block rounded p-4 sticky-lg-top align-self-start"
                style="background-color: #F8FAFC;top: 8rem; z-index: 1">
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
                <div class="landing-dark-separator text-white" style="margin: 15px 0px"></div>
                <div style="border-left: solid 7px var(--bs-primary); padding-left: 10px;">
                    <span class="text-dark opacity-50 font-bebas fs-3">Berita Lainnya: </span><br>
                    <?php foreach ($beritalain as $row) { ?>
                        <div class="fs-6 border-primary">
                            <a href="<?= site_url('berita/detail/' . $row['slug']) ?>" target="_blank"
                                class="text-primary font-bebas fs-4"><?= limitString($row['judul'], 80) ?></a>
                            <p><?= formatDate($row['created_at']) ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>



</section>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    setTimeout(() => {
        let url = window.location.href
        $(".share-fb").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + url);
        $(".share-twt").attr("href", "https://twitter.com/intent/tweet?text=" + url);
        $(".share-wa").attr("href", "whatsapp://send?text=" + url);
    })
</script>
<script src="<?= base_url('assets/js/flickity.pkgd.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/flickity.min.css'); ?>">
<script src="<?= base_url('assets/js/splide.min.js'); ?>"></script>
<link href="<?= base_url('assets/css/splide.min.css'); ?>" rel="stylesheet">

<script>
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
    });
</script>

<?= $this->endSection() ?>