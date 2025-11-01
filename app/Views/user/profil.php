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
                <h1 class="page-heading text-dark fw-bold fs-3 justify-content-center my-0">Selamat
                    datang kembali, <span class="text-warning"><?=$_SESSION['nama']?></span>
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
                <div class="col-lg-12 col-xl-12 col-xxl-8 mb-5 mb-xl-0">
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
                                                <span class="text-gray-800 fs-3 fw-bold">Profil user</span>
                                            </div>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Heading-->
                                        <!--end::Header-->
                                        <!--begin::Body-->
                                        <form action="" id="form_profil">
                                            <div class="mb-3">
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label">Nama lengkap</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="nama_lengkap"
                                                            name="nama_lengkap" value="<?=$profil->nama_lengkap?>">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label">NIK</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="nik" name="nik"
                                                            value="<?=$profil->nik?>">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label">No HP</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="no_hp" name="no_hp"
                                                            value="<?=$profil->no_hp?>">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label">Alamat</label>
                                                    <div class="col-sm-10">
                                                        <textarea name="alamat" class="form-control" rows="2"
                                                            id="alamat"><?=$profil->alamat?></textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label">Wilayah hukum</label>
                                                    <div class="col-sm-5">
                                                        <select name="pilihKejati" id="pilihKejati" class="form-select"
                                                            data-control="select2">
                                                            <option value="">Pilih Kejaksaan Tinggi</option>
                                                            <?php foreach($kejati as $key){ ?>
                                                            <option value="<?=$key->ins_satkerkd?>"><?=$key->inst_nama?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <select name="pilihKejari" id="pilihKejari" class="form-select">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <!--begin::Footer-->
                                            <button class="btn-block btn btn-warning mt-10">Submit</button>
                                            <button class="btn-block btn btn-danger mt-10 float-end btn-ubah-password">Ubah password</button>
                                            <!--end::Footer-->
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

<!-- Modal -->
<div class="modal fade" id="modalUbahPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Reset Password</h5>
      </div>
      <form action="" id="formUbahPassword">
        <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
            <!-- jika user login dengan google dan belum pernah set password, maka tidak perlu mengisi password lama -->
            <?php if($_SESSION['status'] == '1'){?> 
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Password lama</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="password_lama">
                </div>
            </div>
            <?php } ?>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Password baru</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="password_baru">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">ulangi password baru</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="password_baru_confirm">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<!--tambah file js / script-->
<?= $this->section('scripts') ?>

<script src="<?=base_url()?>assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>
<!-- <script src="<?=base_url()?>assets/plugins/custom/ckeditor/ckeditor-inline.bundle.js"></script>
<script src="<?=base_url()?>assets/plugins/custom/ckeditor/ckeditor-balloon.bundle.js"></script>
<script src="<?=base_url()?>assets/plugins/custom/ckeditor/ckeditor-balloon-block.bundle.js"></script>
<script src="<?=base_url()?>assets/plugins/custom/ckeditor/ckeditor-document.bundle.js"></script> -->

<script>
$(document).ready(function() {
    let kejati = '<?=substr($profil->instansi,0,2)?>'
    let kejari = '<?=$profil->instansi?>'

    if (kejari != null) {
        // alert(kejari);
        $('#pilihKejati').val(kejati);
        getKejari(kejati);
    }

    $('#pilihKejati').on('change', function() {
        getKejari(this.value);
    });

    function getKejari(kejati) {
        $.getJSON("<?=site_url()?>/api/getKejari/" + kejati, function(json) {
            optionKejari = '';
            $.each(json, function() {
                optionKejari +=
                    `<option value="${this.ins_satkerkd}" >${this.inst_nama}</option>`
            });
            $('#pilihKejari').html(optionKejari);
        });
        if (kejari != null) {
            setTimeout(changeKejari,2000);  
        }
    }
        
    function changeKejari() {
        $('#pilihKejari').val(kejari);
    }

    $(document).on('submit', '#form_profil', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?=site_url('api/user/update_profil');?>",
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success('Data berhasil disimpan');
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });

    $(document).on('click','.btn-ubah-password',function(e) {
        e.preventDefault();
        $('#modalUbahPassword').modal('show');
    });

    $(document).on('submit', '#formUbahPassword', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?=site_url('api/user/ubah_password');?>",
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success('Password telah diubah');
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    })

});
</script>

<?= $this->endSection() ?>