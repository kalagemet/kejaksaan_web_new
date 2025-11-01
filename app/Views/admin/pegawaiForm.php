<!-- pilih template -->
<?= $this->extend('template/admin/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Form Pegawai
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
                <h1 class="page-heading text-dark fw-bold fs-3 justify-content-center my-0">
                    Form Pegawai
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
            <div class="card">

                <div class="card-body">

                    <div class="card shadow mb-5">
                        <div class="card-body row">
                            <div class="col-lg-3 text-center">
                                <img src="#" id="preview" class="w-100" alt="">
                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">Foto (jpg/png)</label>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                </div>
                            </div>
                            <div class="col-lg-9">

                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">Nama, Jenis Kelamin</label>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="nama" name="nama" required>
                                        </div>
                                        <div class="col-lg-4">
                                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">NIP</label>
                                    <input type="text" class="form-control" id="nip" name="nip" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">Agama</label>
                                    <input type="text" class="form-control" id="agama" name="agama">
                                </div>
                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">Pangkat, Golongan</label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="pangkat" name="pangkat">
                                        </div>
                                        <div class="col-lg-6">
                                            <select name="golongan" id="golongan" class="form-control"
                                                data-control="select2">
                                                <option value="I/A">I/A</option>
                                                <option value="I/B">I/B</option>
                                                <option value="I/C">I/C</option>
                                                <option value="I/D">I/D</option>

                                                <option value="II/A">II/A</option>
                                                <option value="II/B">II/B</option>
                                                <option value="II/C">II/C</option>
                                                <option value="II/D">II/D</option>

                                                <option value="III/A">III/A</option>
                                                <option value="III/B">III/B</option>
                                                <option value="III/C">III/C</option>
                                                <option value="III/D">III/D</option>

                                                <option value="IV/A">IV/A</option>
                                                <option value="IV/B">IV/B</option>
                                                <option value="IV/C">IV/C</option>
                                                <option value="IV/D">IV/D</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_usaha" class="form-label">Tempat, Tanggal Lahir</label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="tempat_lahir"
                                                name="tempat_lahir">
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="date" class="form-control" id="tanggal_lahir"
                                                name="tanggal_lahir">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-header">
                            <div class="card-title fw-bolder">
                                Data Pendidikan
                            </div>
                            <div class="card-toolbar">
                                <button class="btn btn-primary btn-sm" id="addUsaha">Tambah data</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-dashed gx-2 gy-4 align-middle"
                                    id="dt_halaman">
                                    <thead>
                                        <tr class="fw-bolder bg-light">
                                            <th class="ps-5">No</th>
                                            <th class="ps-5">Tingkat</th>
                                            <th class="min-w-100px">Instansi</th>
                                            <th class="min-w-100px">Kepala Instansi</th>
                                        </tr>
                                    </thead>


                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <div class="card-title fw-bolder">
                                Data Karir
                            </div>
                            <div class="card-toolbar">
                                <button class="btn btn-primary btn-sm" id="addUsaha">Tambah data</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-dashed gx-2 gy-4 align-middle"
                                    id="dt_halaman">
                                    <thead>
                                        <tr class="fw-bolder bg-light">
                                            <th class="ps-5">No</th>
                                            <th class="ps-5">Jabatan</th>
                                            <th class="min-w-100px">Masa Jabatan</th>
                                        </tr>
                                    </thead>


                                </table>
                            </div>
                        </div>
                    </div>
                    <button class="mt-10 btn btn-primary">Simpan</button>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>


    <?= $this->endSection() ?>

    <?= $this->section('scripts') ?>

    <?= $this->endSection() ?>