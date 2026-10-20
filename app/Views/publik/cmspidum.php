<?= $this->extend('template/publik/layout') ?>

<?= $this->section('head') ?>
<link href="<?= base_url('assets/css/cms/plugins.bundle.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('assets/css/cms/style.bundle.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('/assets/css/cms/datatables.bundle.css') ?>" rel="stylesheet" type="text/css" />
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Daftar Perkara Tindak Pidana Umum
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<div class="separator separator-dashed my-5"></div>


<section class="mt-10">
    <div class="container">
        <div class="row">
            <h3 class="text-primary font-bebas fs-1">Data Perkara Tindak Pidana Umum
                <?= $setting !== null ? $setting['nama_satker'] : ''; ?>
            </h3>
            <div style="margin-top:1.5rem;">
                <div id="kt_app_container" class="container">
                    <div class="card border-success h-100">

                        <body id="kt_app_body" data-kt-app-layout="light-header" data-kt-app-header-fixed="true"
                            data-kt-app-toolbar-enabled="true" class="app-default">
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container-fit-desktop container-xxl">
                                    <!--begin::Row-->
                                    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                                        <div class="col-xl-12">
                                            <!--begin::Table widget 14-->
                                            <div class="card card-flush h-md-100">
                                                <!--begin::Body-->
                                                <div class="card-body" id="mainContent">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <h3>Penerimaan SPDP</h3>
                                                            <!-- id="kt_charts_widget_36"  -->
                                                            <div id="ktcStatSpdp" class="min-h-auto w-100 ps-4 pe-6"
                                                                style="height: 300px; min-height: 315px;">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <h3>Top 20 klasifikasi perkara</h3>
                                                            <!-- id="kt_charts_widget_1_chart"  -->
                                                            <div id="ktcTop20"
                                                                style="height: 300px; min-height: 330px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--begin::Table container-->
                                                    <div class="table-responsive">
                                                        <!--begin::Table-->
                                                        <div id="dtPerkara_wrapper"
                                                            class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table-perkara table align-middle table-row-bordered table-row-solid gy-4 gs-9 dataTable no-footer"
                                                                    id="dtPerkara" aria-describedby="dtPerkara_info">
                                                                    <thead
                                                                        class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                                        <tr>
                                                                            <th class="sorting sorting_asc" tabindex="0"
                                                                                aria-controls="dtPerkara" rowspan="1"
                                                                                colspan="1"
                                                                                aria-label="No: activate to sort column descending"
                                                                                aria-sort="ascending">No</th>
                                                                            <!--<th class="sorting" tabindex="0" aria-controls="dtPerkara"-->
                                                                            <!--    rowspan="1" colspan="1"-->
                                                                            <!--    aria-label="No, tgl SPDP: activate to sort column ascending">No,-->
                                                                            <!--    tgl SPDP</th>-->
                                                                            <!--<th class="sorting" tabindex="0" aria-controls="dtPerkara"-->
                                                                            <!--    rowspan="1" colspan="1"-->
                                                                            <!--    aria-label="Tanggal SPDP Diterima: activate to sort column ascending">-->
                                                                            <!--    Tanggal SPDP Diterima</th>-->
                                                                            <th class="sorting" tabindex="0"
                                                                                aria-controls="dtPerkara" rowspan="1"
                                                                                colspan="1"
                                                                                aria-label="Tersangka/ Terdakwa: activate to sort column ascending">
                                                                                Tersangka/ Terdakwa</th>
                                                                            <th class="sorting" tabindex="0"
                                                                                aria-controls="dtPerkara" rowspan="1"
                                                                                colspan="1"
                                                                                aria-label="Penyidik: activate to sort column ascending">
                                                                                Penyidik</th>
                                                                            <th width="30%" class="sorting" tabindex="0"
                                                                                aria-controls="dtPerkara" rowspan="1"
                                                                                colspan="1" style="width: 250px;"
                                                                                aria-label="Pasal yang disangkakan: activate to sort column ascending">
                                                                                Pasal yang disangkakan</th>
                                                                            <th class="sorting" tabindex="0"
                                                                                aria-controls="dtPerkara" rowspan="1"
                                                                                colspan="1"
                                                                                aria-label="Status: activate to sort column ascending">
                                                                                Status
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tperkara">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Table-->
                                                </div>
                                                <!--end: Card Body-->
                                            </div>
                                            <!--end::Table widget 14-->
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Content container-->

                                <div class="modal fade" id="riwayat-modal">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Riwayat penanganan perkara</h4>
                                                <div class="btn btn-sm btn-icon btn-active-color-primary"
                                                    data-bs-dismiss="modal">
                                                    <span class="svg-icon svg-icon-1">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                                rx="1" transform="rotate(-45 6 17.3137)"
                                                                fill="currentColor"></rect>
                                                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                                transform="rotate(45 7.41422 6)" fill="currentColor">
                                                            </rect>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body" id="kasus-txt">
                                                <div class="timeline">
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-content mb-10 mt-n2">
                                                            <div class="overflow-auto pe-3">
                                                                <div class="fs-5 fw-semibold mb-2">Penerimaan SPDP
                                                                </div>
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Nomor SPDP :
                                                                        <span class="spdp_no">0000-00-00</span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Diterima
                                                                        tanggal : <span
                                                                            class="spdp_tgl">0000-00-00</span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-content mb-10 mt-n2">
                                                            <div class="overflow-auto pe-3">
                                                                <div class="fs-5 fw-semibold mb-2">Pengembalian SPDP
                                                                </div>
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Nomor Berkas :
                                                                        <span class="spdp_kembali">0000-00-00</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-content mb-10 mt-n2">
                                                            <div class="overflow-auto pe-3">
                                                                <div class="fs-5 fw-semibold mb-2">Penerimaan Berkas
                                                                </div>
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Nomor Berkas :
                                                                        <span class="berkas_no">0000-00-00</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-content mb-10 mt-n2">
                                                            <div class="overflow-auto pe-3">
                                                                <div class="fs-5 fw-semibold mb-2">Berkas Lengkap
                                                                    (P-21)</div>
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Tanggal :
                                                                        <span class="tgl_p21">0000-00-00</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-content mb-10 mt-n2">
                                                            <div class="overflow-auto pe-3">
                                                                <div class="fs-5 fw-semibold mb-2">Penyerahan
                                                                    Tersangka dan Barang bukti</div>
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Tanggal :
                                                                        <span class="tgl_tahap_2">0000-00-00</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-content mb-10 mt-n2">
                                                            <div class="overflow-auto pe-3">
                                                                <div class="fs-5 fw-semibold mb-2">Pelimpahan ke
                                                                    Pengadilan</div>
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Tanggal :
                                                                        <span class="tgl_p31">0000-00-00</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-content mb-10 mt-n2">
                                                            <div class="overflow-auto pe-3">
                                                                <div class="fs-5 fw-semibold mb-2">Penuntutan</div>
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Tanggal :
                                                                        <span class="tgl_p42">0000-00-00</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-content mb-10 mt-n2">
                                                            <div class="overflow-auto pe-3">
                                                                <div class="fs-5 fw-semibold mb-2">Putusan</div>
                                                                <div class="align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Putusan
                                                                        Tingkat Pertama : <span
                                                                            class="tgl_putus_pn">0000-00-00</span>
                                                                    </div> <br>
                                                                    <div class="text-muted me-2 fs-5">Putusan
                                                                        Tingkat Banding : <span
                                                                            class="tgl_putus_banding">0000-00-00</span>
                                                                    </div> <br>
                                                                    <div class="text-muted me-2 fs-5">Putusan
                                                                        Tingkat Kasasi : <span
                                                                            class="tgl_putus_kasasi">0000-00-00</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-item">
                                                        <div class="timeline-line w-40px"></div>
                                                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                            <div class="symbol-label bg-light">
                                                                <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="timeline-content mb-10 mt-n2">
                                                            <div class="overflow-auto pe-3">
                                                                <div class="fs-5 fw-semibold mb-2">Eksekusi</div>
                                                                <div class="d-flex align-items-center mt-1 fs-6">
                                                                    <div class="text-muted me-2 fs-5">Tanggal :
                                                                        <span class="tgl_p48">0000-00-00</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </body>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>



</section>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/cms/plugins.bundle.js') ?>"></script>
<script src="<?= base_url('assets/js/cms/scripts.bundle.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/cms/util.js') ?>"></script>
<link href="<?= base_url('assets/css/cms/datatables.bundle.css') ?>" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/js/cms/datatables.bundle.js') ?>"></script>
<script src="<?= base_url('assets/js/cms/ajaxer2.js') ?>"></script>
<script src="<?= base_url('assets/js/cms/blocker.js') ?>"></script>
<script src="<?= base_url('assets/js/cms/blockui.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/js/cms/ktc.js') ?>"></script>
<script src="<?= base_url('assets/js/cms/cmspublikpidum.js') ?>"></script>

<?= $this->endSection() ?>