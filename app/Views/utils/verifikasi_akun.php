<!-- pilih template -->
<?= $this->extend('template/utils/layout') ?>

<!-- set title -->
<?= $this->section('title') ?>
    Verifikasi akun E-Datun
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!--begin::Heading-->
<div class="text-center mb-11">
    <!--begin::Title-->
    <h1 class="text-dark fw-bolder mb-3">Akun anda telah terverifikasi<br/>silahkan login</h1>
    <!--end::Title-->
</div>
<!--begin::Heading-->
<!--end::Separator-->
<!--begin::Input group=-->

<!--end::Input group=-->
<!--begin::Submit button-->
<div class="d-grid mb-5">
    <a href="<?=site_url('login')?>" id="kt_sign_in_submit"
        class="btn btn-warning border border-warning">
        <!--begin::Indicator label-->
        <span class="indicator-label fw-bold text-light">Masuk halaman login</span>
        <!--end::Indicator label-->
        <!--begin::Indicator progress-->
        <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        <!--end::Indicator progress-->
    </a>
</div>
<!--end::Submit button-->
<div
    class="notice d-flex bg-light-secondary rounded border-secondary border border-dashed flex-shrink-0 p-6">
    <!--begin::Icon-->
    <!--begin::Svg Icon | path: icons/duotune/coding/cod004.svg-->
    <span class="svg-icon svg-icon-2tx svg-icon-secondary me-4">
        <i class="bi bi-info-square-fill fs-2"></i>
    </span>
    <!--end::Svg Icon-->
    <!--end::Icon-->
    <!--begin::Wrapper-->
    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
        <!--begin::Content-->
        <div class="mb-3 mb-md-0 fw-semibold">
            <h4 class="text-gray-900 fs-6 fw-bold">INFO PENTING</h4>
            <div class="fs-8 text-gray-700">Lindungi akun Anda dengan tidak
                memberikan ID pengguna dan kata sandi Anda pada siapapun. <br>
                Segala risiko akibat penyalahgunaan ID pengguna menjadi tanggung
                jawab pengguna sepenuhnya.</div>

        </div>
        <!--end::Content-->
    </div>
    <!--end::Wrapper-->
</div>
<?= $this->endSection() ?>