<!-- template untuk publik -->

<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../" />
    <title><?= $this->renderSection('title') ?> | <?= $setting !== null ? $setting['nama_satker'] : ''; ?>
    </title>
    <meta charset="utf-8" />
    <meta name="description" content="Website Resmi <?= $setting !== null ? $setting['nama_satker'] : ''; ?>" />
    <meta name="keywords"
        content="Kejaksaan Negeri <?= $setting !== null ? $setting['nama_satker'] : ''; ?>, Kejaksaan Tinggi Jawa Tengah, Kejaksaan RI" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?= $this->renderSection('meta-berita') ?>
    <?= $this->renderSection('head') ?>
    <link rel="shortcut icon" href="<?= base_url('assets/media/logos/favicon.png') ?>" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" />
    <link href="<?= $_baseurl ?>/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?= $_baseurl ?>/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=League+Gothic&display=swap" rel="stylesheet">

</head>

<body id="kt_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" class="bg-white position-relative app-blank">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Root-->

    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Header Section-->
        <div class="mb-0" id="home">
            <!--begin::Wrapper-->
            <div class="pt-3" style="background-color: #2C3930">
                <div class="container text-center mb-3">
                    <div class="marquee fw-bold text-white">
                        <span style="font-size: 14px;" id="isi_run_text">
                            Selamat Datang di website <?= $setting !== null ? $setting['nama_satker'] : ''; ?></span>
                    </div>
                </div>
                <div class="bg-white border-bottom border-gray-300 rounded-3 rounded-bottom-0 transition-all">
                    <div class="container d-flex flex-row justify-content-between px-5 py-5">
                        <div class="float-start fw-bold">
                            <div class="badge text-dark fs-6 d-flex align-items-center">
                                <?= viewNowDate(); ?>
                            </div>
                        </div>

                        <div class="d-lg-block d-xl-block fs-6 d-none">
                            <div class="badge text-dark fs-6 d-flex align-items-center">
                                <div class="d-lg-block d-xl-block fs-6 d-none">
                                    <span class="d-flex align-items-center"><i
                                            class="bi bi-envelope ms-3 fs-3 text-dark me-2"></i>
                                        <?= $setting !== null ? $setting['email_satker'] : ''; ?></span>

                                </div>
                                <div class="d-lg-block d-xl-block fs-6 d-none">
                                    <span class="ms-2 d-flex align-items-center"> <i
                                            class="bi bi-telephone fs-3 text-dark me-2"></i><?= $setting !== null ? $setting['phone_satker'] : ''; ?></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!--begin::Header-->
            <div class="landing-header text-dark border-bottom border-gray-300" data-kt-sticky="true"
                data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center justify-content-between">
                        <!--begin::Logo-->
                        <div class="d-flex align-items-center flex-equal">
                            <!--begin::Mobile menu toggle-->
                            <button class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none"
                                id="kt_landing_menu_toggle">
                                <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                                <span class="svg-icon svg-icon-2hx">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                                            fill="currentColor" />
                                        <path opacity="0.3"
                                            d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </button>
                            <!--end::Mobile menu toggle-->
                            <!--begin::Logo image-->
                            <!--begin::Logo image-->
                            <a href="/">
                                <img alt="Logo"
                                    src="<?= $setting['logo_satker'] !== null ? base_url('logo/' . $setting['logo_satker']) : base_url('assets/media/logos/logo.png') ?>"
                                    class="logo-default h-40px h-lg-70px" />
                            </a>
                            <a href="/" class="ms-3 fw-bold text-dark" style="line-height: 1">
                                <div class="d-lg-block d-xl-block d-md-block fs-2 font-bebas">Kejaksaan Republik
                                    Indonesia </div>

                                <span class="fs-1 fw-bolder text-primary d-lg-block d-xl-block font-bebas">
                                    <?= $setting !== null ? $setting['nama_satker'] : ''; ?>
                                </span>
                            </a>
                            <!--end::Logo image-->
                            <!--end::Logo image-->
                        </div>
                        <!--end::Logo-->
                        <div class="d-lg-block d-xl-block fs-6 d-none">
                            <a href="#" class="text-white fw-bold me-3">
                                <img src="<?= base_url('logo/trapsila_berakhlak.png') ?>" class="h-50px" alt="">
                            </a>
                        </div>

                    </div>
                    <!--end::Wrapper-->

                </div>
                <!--end::Container-->
            </div>
            <!--end::Header-->
            <!--end::Wrapper-->
        </div>
        <!--end::Header Section-->
        <div style="flex: 1;">
            <div class="--bg-warning--">
                <!--begin::Menu wrapper-->
                <div class="d-lg-block" id="kt_header_nav_wrapper">
                    <div class="d-lg-block p-5 p-lg-0 d-flex justify-content-center" data-kt-drawer="true"
                        data-kt-drawer-name="landing-menu" data-kt-drawer-activate="{default: true, lg: false}"
                        data-kt-drawer-overlay="true" data-kt-drawer-width="200px" data-kt-drawer-direction="start"
                        data-kt-drawer-toggle="#kt_landing_menu_toggle" data-kt-swapper="true"
                        data-kt-swapper-mode="prepend"
                        data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav_wrapper'}">

                        <div class="fw-bold menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0 d-lg-flex justify-content-lg-center"
                            id="kt_app_header_menu" data-kt-menu="true">

                            <?php foreach ($navbar as $menuTitle): ?>
                                <?php if (isset($menu[$menuTitle])): ?>
                                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                        data-kt-menu-placement="bottom-start"
                                        class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">

                                        <!-- Menu Title -->
                                        <span class="menu-link text-dark">
                                            <span class="menu-title text-dark" data-text="<?= $menuTitle; ?>">
                                                <?= $menuTitle; ?>
                                            </span>
                                            <span class="menu-arrow d-lg-none"></span>
                                        </span>

                                        <!-- Submenu -->
                                        <div
                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
                                            <?php foreach ($menu[$menuTitle] as $grup_menu): ?>
                                                <?php
                                                if ($grup_menu['is_child'] == 1) {
                                                    ?>
                                                    <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                                        data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">

                                                        <span class="menu-link">
                                                            <span class="menu-title">
                                                                <?= $grup_menu['grup_menu']; ?>
                                                            </span>
                                                            <span class="menu-arrow"></span>
                                                        </span>
                                                        <div
                                                            class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px">
                                                            <div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                                                                data-kt-menu-placement="right-start"
                                                                class="menu-item menu-lg-down-accordion">
                                                                <?php foreach ($grup_menu['sub_menu'] as $sub_menu): ?>
                                                                    <div class="menu-item">
                                                                        <a class="menu-link text-dark" href="<?= $sub_menu['tipe'] === 'internal'
                                                                            ? site_url($sub_menu['relasi'] === 'halaman'
                                                                                ? 'page/' . $sub_menu['content']
                                                                                : 'berita/detail/' . $sub_menu['content'])
                                                                            : $sub_menu['link']; ?>">
                                                                            <span class="menu-bullet">
                                                                                <span class="bullet bullet-dot"></span>
                                                                            </span>
                                                                            <span class="menu-title"><?= $sub_menu['judul']; ?></span>
                                                                        </a>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="menu-link text-dark" href="<?= $grup_menu['sub_menu'][0]['tipe'] === 'internal'
                                                        ? site_url($grup_menu['sub_menu'][0]['relasi'] === 'halaman'
                                                            ? 'page/' . $grup_menu['sub_menu'][0]['content']
                                                            : 'berita/detail/' . $grup_menu['sub_menu'][0]['content'])
                                                        : $grup_menu['sub_menu'][0]['link']; ?>">
                                                        <span class="menu-title"><?= $grup_menu['sub_menu'][0]['judul']; ?></span>
                                                    </a>
                                                    <?php
                                                }
                                                ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="menu-item me-0">
                                        <a href="<?= site_url(strtolower(str_replace(' ', '-', $menuTitle))); ?>"
                                            class="menu-link hover-text text-dark" data-text="<?= $menuTitle; ?>">
                                            <span class="menu-title text-grey"><?= $menuTitle; ?></span>
                                            <span class="menu-arrow d-lg-none"></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo $this->renderSection('content'); ?>
            <div class="bg-white py-10"></div>
        </div>
        <!--begin::Footer Section-->
        <div class="mb-0" style="">
            <!--begin::Wrapper-->
            <div class="bg-primary pt-20">
                <div class="container">
                    <!--begin::Row-->
                    <div class="row py-10 py-lg-10">
                        <!--begin::Col-->
                        <div class="col-lg-6 pe-lg-16 mb-10 mb-lg-0">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="px-2">
                                        <div class="d-flex">
                                            <h4 class="pb-3 font-bebas fs-1 font-weight-bold text-white">
                                                <?= $setting['nama_satker'] ?>
                                            </h4>
                                        </div>

                                        <dl class="row text-white mb-0 fs-5">
                                            <dt class="col-sm-1 col-1"><i
                                                    class="bi bi-telephone-fill text-white fw-bold"></i></dt>
                                            <dd class="col-sm-11 col-11 mb-2">
                                                <?= $setting !== null ? $setting['phone_satker'] : ''; ?>
                                            </dd>
                                            <dt class="col-sm-1 col-1"><i
                                                    class="bi bi-house-door-fill text-white fw-bold"></i></dt>
                                            <dd class="col-sm-11 col-11 mb-2">
                                                <?= $setting !== null ? $setting['alamat_satker'] : ''; ?>
                                            </dd>
                                            <dt class="col-sm-1 col-1"><i class="bi bi-envelope text-white fw-bold"></i>
                                            </dt>
                                            <dd class="col-sm-11 col-11 mb-2">
                                                <?= $setting !== null ? $setting['email_satker'] : ''; ?>
                                            </dd>
                                            <dt class="col-sm-1 col-1"><i class="bi bi-globe text-white fw-bold"></i>
                                            </dt>
                                            <dd class="col-sm-11 col-11 mb-2"><?= namaWeb() ?></dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-lg-6">
                            <!--begin::Navs-->
                            <div class="d-flex justify-content-left">
                                <!--begin::Links-->
                                <div class="d-flex fw-semibold flex-column me-20" style="width: 100%;">
                                    <!--begin::Subtitle-->
                                    <h4 class="fw-bold text-white font-bebas fs-1 mb-2">Media Sosial</h4>
                                    <!--end::Subtitle-->
                                    <!--begin::Link-->
                                    <a href="<?= $setting !== null ? $setting['url_facebook'] : '#'; ?>" class="mb-2">
                                        <img src="<?= base_url() ?>assets/media/svg/brand-logos/facebook-4.svg"
                                            class="h-20px me-2" alt="">
                                        <span class="text-white opacity-50 text-hover-warning fs-5 mb-2">Facebook</span>
                                    </a>
                                    <!--end::Link-->
                                    <!--begin::Link-->
                                    <a href="<?= $setting !== null ? $setting['url_twitter'] : '#'; ?>" class="mb-2">
                                        <img src="<?= base_url() ?>assets/media/svg/brand-logos/twitter.svg"
                                            class="h-20px me-2" alt="">
                                        <span class="text-white opacity-50 text-hover-primary fs-5 mb-2">Twitter</span>
                                    </a>
                                    <!--end::Link-->
                                    <!--begin::Link-->
                                    <a href="<?= $setting !== null ? $setting['url_instagram'] : '#'; ?>" class="mb-2">
                                        <img src="<?= base_url() ?>assets/media/svg/brand-logos/instagram-2-1.svg"
                                            class="h-20px me-2" alt="">
                                        <span
                                            class="text-white opacity-50 text-hover-primary fs-5 mb-6">Instagram</span>
                                    </a>
                                    <!--end::Link-->
                                    <!--begin::Link-->
                                    <a href="<?= $setting !== null ? $setting['url_youtube'] : '#'; ?>" class="mb-6">
                                        <img src="<?= base_url() ?>assets/media/svg/brand-logos/youtube-3.svg"
                                            class="h-20px me-2" alt="">
                                        <span class="text-white opacity-50 text-hover-primary fs-5 mb-8">Youtube</span>
                                    </a>
                                    <!--end::Link-->
                                    <!--begin::Subtitle-->
                                    <h4 class="fw-bold text-white font-bebas fs-1 mb-2">Link Terkait</h4>
                                    <!--end::Subtitle-->
                                    <?php
                                    if (isset($menu['Menu Footer'])) {
                                        foreach ($menu['Menu Footer'] as $grup_menu): ?>
                                            <?php
                                            foreach ($grup_menu['sub_menu'] as $sub_menu) {
                                                $url = '';
                                                if ($sub_menu['tipe'] == 'internal') {
                                                    if ($sub_menu['relasi'] == 'halaman') {
                                                        $url = site_url('page/' . $sub_menu['content']);
                                                    } else {
                                                        $url = site_url('detail-berita/' . $sub_menu['content']);
                                                    }
                                                } else {
                                                    $url = $sub_menu['link'];
                                                }
                                                ?>
                                                <!--begin::Link-->
                                                <a href="<?= $url ?>"
                                                    class="text-white opacity-50 text-hover-warning fs-5"><?= $sub_menu['judul'] ?></a>
                                                <!--end::Link-->
                                            <?php } ?>
                                        <?php endforeach;
                                    } ?>
                                </div>
                                <!--end::Links-->
                                <!--begin::Statistik-->
                                <div class="d-flex fw-semibold flex-column">
                                    <div class="rounded border border-1 border-primary mb-3">
                                        <div
                                            class="rounded-top px-3 py-1 bg-primary text-white text-center fs-3 font-bebas">
                                            Statistik
                                            Pengunjung</div>
                                        <table class="table table-row-bordered  table-striped  gy-1 fs-6 bg-white">
                                            <tr>
                                                <td class="fw-bold p-2 ">
                                                    <i class="bi bi-calendar2-week"></i> Hari ini
                                                </td>
                                                <td class="text-end text-primary fw-bolder p-2"><span
                                                        class="badge badge-primary"><?= $visitor['today'] ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold p-2">
                                                    <i class="bi bi-calendar2-week"></i> Kemarin
                                                </td>
                                                <td class="text-end text-primary fw-bolder p-2">
                                                    <span
                                                        class="badge badge-primary"><?= $visitor['yesterday'] ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold p-2 ">
                                                    <i class="bi bi-calendar2-week"></i> Minggu ini
                                                </td>
                                                <td class="text-end text-primary fw-bolder p-2">
                                                    <span
                                                        class="badge badge-primary"><?= $visitor['this_week'] ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold p-2 ">
                                                    <i class="bi bi-calendar2-week"></i> Bulan ini
                                                </td>
                                                <td class="text-end text-primary fw-bolder p-2"><span
                                                        class="badge badge-primary"><?= $visitor['this_month'] ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold p-2 ">
                                                    <i class="bi bi-calendar2-week"></i> Total
                                                </td>
                                                <td class="text-end text-primary fw-bolder p-2">
                                                    <span class="badge badge-primary"><?= $visitor['total'] ?></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary text-white"
                                            id="basic-addon1">Online</span>
                                        <input type="text" class="form-control" value="<?= $visitor['active_users'] ?>"
                                            readonly>
                                    </div>
                                </div>
                                <!--end::Statistik-->
                            </div>
                            <!--end::Navs-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--begin::Separator-->
                <div class="landing-dark-separator"></div>
                <!--end::Separator-->
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column flex-md-row flex-stack py-7 py-lg-10">
                        <!--begin::Copyright-->
                        <div class="d-flex align-items-center order-2 order-md-1">
                            <!--begin::Logo-->
                            <!-- <a href="../../demo1/dist/landing.html">
                                <img alt="Logo" src="assets/media/logos/landing.svg" class="h-15px h-md-20px" />
                            </a> -->
                            <!--end::Logo image-->
                            <!--begin::Logo image-->
                            <span class="mx-5 fs-6 fw-semibold text-white fw-bolder pt-1"
                                href="https://www.kejaksaan.go.id/">&copy;
                                <?= date('Y') ?> <?= $setting['nama_satker'] ?>.</span>
                            <!--end::Logo image-->
                        </div>
                        <!--end::Copyright-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Footer Section-->
        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
            <!-- <div style="position:fixed;right:10px;bottom:83px;z-index: 999;"> -->
            <!-- <a href="https://api.whatsapp.com/send?phone=<?= $setting !== null ? $setting['call_center'] : ''; ?>&text=<?= $setting !== null ? $setting['text_call_center'] : ''; ?>"
                    class="btn btn-lg btn-icon text-white bg-success">
                    <i class="fab fa-whatsapp text-white fs-1"></i></a> -->
            <!-- </div> -->
            <!--end::Svg Icon-->
        </div>
        <!--end::Scrolltop-->
    </div>
    <!--end::Root-->

    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="<?php echo base_url(); ?>assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/scripts.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/widgets.bundle.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/custom/widgets.js"></script>
    <!-- <script>
    (function(d) {
        var s = d.createElement("script");
        s.setAttribute("data-account", "Ehe6ujyB9U");
        s.setAttribute("src", "https://cdn.userway.org/widget.js");
        (d.body || d.head).appendChild(s);
    })(document)
    </script><noscript>Please ensure Javascript is enabled for purposes of <a href="https://userway.org">website
            accessibility</a></noscript> -->
    <!--end::Global Javascript Bundle-->
    <!-- <script src="https://code.responsivevoice.org/responsivevoice.js?key=KZfQ5sY8"></script> -->
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=1gvJbppd"></script>

    <?php echo $this->renderSection('scripts'); ?>
</body>
<!--end::Body-->

</html>