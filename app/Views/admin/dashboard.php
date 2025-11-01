<!-- pilih template -->
<?= $this->extend('template/admin/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Beranda
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading text-dark fw-bold fs-3 justify-content-center my-0">Dashboard 
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row g-5 g-xl-10 mb-xl-10">
                <!--begin::Col-->
                <div class="col-lg-12 col-xl-12 col-xxl-12 mb-5 mb-xl-0">
                    <div class="card card-flush h-xl-100">
                        <!--begin::Body-->
                        <div class="card-body py-9">
                            <!--begin::Row-->
                            <div class="row gx-9 h-100">
                                <!--begin::Col-->
                                <div class="col-sm-12">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column h-100">
                                        <!--begin::Header-->
                                        <!--begin::Headin-->
                                        <div class="d-flex flex-stack mb-6">
                                            <!--begin::Title-->
                                            <div class="flex-shrink-0 me-5">
                                                <span class="text-gray-800 fs-3 fw-bold">Title</span>
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Heading-->
                                        <!--end::Header-->
                                        
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Layout-->

        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
<?= $this->endSection() ?>

<!--tambah file js / script-->
<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    function getUserProfil() {
        $('.btn-pelayanan').prop('disabled', true);

        $.getJSON("<?=site_url()?>/api/getProfil/", function(json) {
            let cek = 0;
            if (json.nama_lengkap != null) {
                $('.cek_1').text('Checked').addClass('text-success').removeClass('text-danger');
                cek++;
            }
            if (json.nik != null) {
                $('.cek_3').text('Checked').addClass('text-success').removeClass('text-danger');
                cek++;
            }
            if (json.no_hp != null) {
                $('.cek_2').text('Checked').addClass('text-success').removeClass('text-danger');
                cek++;
            }
            if (json.instansi != null) {
                $('.cek_4').text('Checked').addClass('text-success').removeClass('text-danger');
                cek++;
            }

            if (cek == 4) {
                $('.btn-pelayanan').prop('disabled', false);
                $('.btn-pelayanan').click(function() {
                    window.location.href = '/user/form';
                    return false;
                });
            }
        });


    }

    getUserProfil()
});
</script>
<?= $this->endSection() ?>