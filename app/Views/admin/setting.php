<!-- pilih template -->
<?= $this->extend('template/admin/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Beranda
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<style>
.image-container {
    width: 100%;
    height: 250px;
    border: 2px solid #ccc;
    overflow: hidden;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f9f9f9;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.image-container:hover {
    border-color: #007bff;
}

.upload-file {
    display: none;
}
</style>
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading text-dark fw-bold fs-3 justify-content-center my-0">Setting
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="../../demo1/dist/index.html" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Dashboards</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
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
                                                <span class="text-gray-800 fs-3 fw-bold">Data Satuan Kerja</span>
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Heading-->
                                        <!--end::Header-->
                                        <form id="form_data" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field(); ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="nama_usaha" class="form-label">Logo Satuan
                                                            Kerja</label>
                                                        <div class="image-container"
                                                            onclick="triggerUpload('logo_satker')">
                                                            <img id="preview_logo_satker"
                                                                src="<?= $data !== null ? base_url('logo/'.$data['logo_satker']) : base_url('assets/media/svg/files/blank-image.svg') ?>"
                                                                alt="Profile Image">
                                                        </div>
                                                        <input class="upload-file" type="file" id="logo_satker"
                                                            name="logo_satker" accept="image/*"
                                                            onchange="previewImage(event,'preview_logo_satker')">

                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <label for="nama_usaha" class="form-label">Nama Satuan
                                                            Kerja</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data !== null ? $data['nama_satker'] : '' ?>"
                                                            id="nama_satker" name="nama_satker">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nama_usaha" class="form-label">Alamat</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data !== null ? $data['alamat_satker'] : '' ?>"
                                                            id="alamat_satker" name="alamat_satker">
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label for="nama_usaha" class="form-label">Email</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?= $data !== null ? $data['email_satker'] : '' ?>"
                                                                    id="email_satker" name="email_satker">
                                                            </div>
                                                            <div class="col-6">
                                                                <label for="nama_usaha" class="form-label">No.
                                                                    Telepon/Fax</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?= $data !== null ? $data['phone_satker'] : '' ?>"
                                                                    id="phone_satker" name="phone_satker">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label for="nama_usaha" class="form-label">Call
                                                                    Center</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?= $data !== null ? $data['call_center'] : '' ?>"
                                                                    id="call_center" name="call_center">
                                                            </div>
                                                            <div class="col-6">
                                                                <label for="nama_usaha" class="form-label">Text Call
                                                                    Center</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?= $data !== null ? $data['text_call_center'] : '' ?>"
                                                                    id="text_call_center" name="text_call_center">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="nama_usaha" class="form-label">Link Facebook
                                                            (opsional)</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data !== null ? $data['url_facebook'] : '' ?>"
                                                            id="url_facebook" name="url_facebook">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="nama_usaha" class="form-label">Link Instagram
                                                            (opsional)</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data !== null ? $data['url_instagram'] : '' ?>"
                                                            id="url_instagram" name="url_instagram">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="nama_usaha" class="form-label">Link Youtube Channel
                                                            (opsional)</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data !== null ? $data['url_youtube'] : '' ?>"
                                                            id="url_youtube" name="url_youtube">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="nama_usaha" class="form-label">Link X/ Twitter
                                                            (opsional)</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data !== null ? $data['url_twitter'] : '' ?>"
                                                            id="url_twitter" name="url_twitter">
                                                    </div>
                                                </div>

                                                <div class="separator separator-dashed my-5"></div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="nama_usaha" class="form-label">Running Text</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data !== null ? $data['running_text'] : '' ?>"
                                                            id="running_text" name="running_text">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="nama_usaha" class="form-label">Motto Instansi</label>
                                                        <input type="text" class="form-control"
                                                            value="<?= $data !== null ? $data['motto'] : '' ?>"
                                                            id="motto" name="motto">
                                                    </div>
                                                </div>
                                            </div>
<div class="separator separator-dashed my-5"></div>
                                            <h3>Banner</h3>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="nama_usaha" class="form-label">Banner ini akan ditampilkan di bawah <b>Jadwal Sidang </b></label>
                                                    <div class="image-container"
                                                        onclick="triggerUpload('gambar_survey')">
                                                        <img id="preview_gambar_survey"
                                                            src="<?= $data !== null ? (($data['gambar_survey'] != '') ? base_url('logo/'.$data['gambar_survey']) : base_url('assets/media/svg/files/blank-image.svg') ) : base_url('assets/media/svg/files/blank-image.svg') ?>"
                                                            alt="Profile Image">
                                                    </div>
                                                    <input class="upload-file" type="file" id="gambar_survey"
                                                        name="gambar_survey" accept="image/*"
                                                        onchange="previewImage(event,'preview_gambar_survey')">

                                                </div>

                                            </div>
                                            <div class="separator separator-dashed my-5"></div>
                                            <h3>Banner</h3>
                                            <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="nama_usaha" class="form-label">Hasil Survey IKM</label>
                                                    <div class="image-container" onclick="triggerUpload('gambar_ikm')">
                                                        <img id="preview_gambar_ikm"
                                                            src="<?= $data !== null ? (($data['gambar_ikm'] != '') ? base_url('logo/'.$data['gambar_ikm']) : base_url('assets/media/svg/files/blank-image.svg') ) : base_url('assets/media/svg/files/blank-image.svg') ?>"
                                                            alt="Profile Image">
                                                    </div>
                                                    <input class="upload-file" type="file" id="gambar_ikm"
                                                        name="gambar_ikm" accept="image/*"
                                                        onchange="previewImage(event,'preview_gambar_ikm')">

                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="nama_usaha" class="form-label">Hasil Survey IPAK</label>
                                                    <div class="image-container" onclick="triggerUpload('gambar_ipak')">
                                                        <img id="preview_gambar_ipak"
                                                            src="<?= $data !== null ? (($data['gambar_ipak'] != '') ? base_url('logo/'.$data['gambar_ipak']) : base_url('assets/media/svg/files/blank-image.svg') ) : base_url('assets/media/svg/files/blank-image.svg') ?>"
                                                            alt="Profile Image">
                                                    </div>
                                                    <input class="upload-file" type="file" id="gambar_ipak"
                                                        name="gambar_ipak" accept="image/*"
                                                        onchange="previewImage(event,'preview_gambar_ipak')">

                                                </div>

                                            </div>
                                            </div>



                                            <button type="submit" class="btn btn-primary w-100">Simpan</button>
                                        </form>
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
    $(document).on('submit', '#form_data', function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "<?=site_url('api/setting');?>",
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success('Data berhasil disimpan');
                $('#modal_data').modal('hide');
                location.reload(); // Reload the page or refresh the data table
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });

});


function triggerUpload(id) {
    document.getElementById(id).click();
}

function previewImage(event, id) {
    const file = event.target.files[0];
    const preview = document.getElementById(id);

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        // If no file is selected, reset to default profile image
        preview.src = 'default-profile.png';
    }
}
</script>
<?= $this->endSection() ?>