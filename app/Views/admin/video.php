<!-- pilih template -->
<?= $this->extend('template/admin/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Video
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
                    Data Video 
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
        <div class="card-header">
            <div class="card-title fw-bolder">
                Data Video <span class="small ms-3">(maksimal 10 Video)</span>
            </div>
            <div class="card-toolbar">
                <?php if (count($video) < 10): ?>
                    <button class="btn btn-primary btn-sm" id="addUsaha">Tambah data</button>
                <?php else: ?>
                    <button class="btn btn-primary btn-sm" id="addUsaha" disabled>Tambah data</button>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="p-5">
                <div class="p-2 border border-warning bg-light-warning mb-5 fs-4 rounded text-center">
                    <span class="fw-bolder">Informasi!</span> Unggah video Anda ke laman Youtube terlebih dahulu
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-dashed gx-2 gy-4 align-middle">
                    <tr class="fw-bolder bg-light">
                        <th class="ps-5">No</th>
                        <th class="min-w-100px">Video</th>
                        <th class="min-w-200px">ID</th>
                        <th class="min-w-200px">Aksi</th>
                    </tr>
                    <?php 
                        $i = 1; 
                        $maxVideos = 10; // Batas maksimal video
                        $limitedVideos = array_slice($video, 0, $maxVideos); // Ambil maksimal 10 data
                        foreach ($limitedVideos as $key => $value): 
                    ?>
                    <tr>
                        <td class="ps-5"><?=$i?></td>
                        <td>
                            <iframe width="200" height="120" class="rounded"
                                src="https://www.youtube.com/embed/<?=$value['link']?>?si=rKRFH3woISfS6-IL"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </td>
                        <td>
                            ID YouTube : <br>
                            <span class="fs-3 fw-bold"><?=$value['link']?></span></td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-id="<?=$value['id']?>" id="ubahData">Edit</button>
                            <button class="btn btn-sm btn-warning" data-id="<?=$value['id']?>" id="hapusUsaha">Hapus</button>
                        </td>
                    </tr>
                    <?php 
                        $i++;
                        endforeach; 
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>

<div class="modal fade" id="modal_data" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Masukkan ID Youtube</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_data" method="POST" enctype="multipart/form-data" data-id="new">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_usaha" class="form-label">Link</label>
                        <input type="text" class="form-control" id="link" name="link" required>
                    </div>

                    <div>
                        <h3>Dimana saya bisa mendapatkan ID Youtube?</h3>

                        ID Youtube Anda akan tampak seperi di bawah ini <br>

                        <div class="p-3 my-3 text-center border boder-dark bg-light">
                        https://www.youtube.com/watch?v=<span class="fw-bolder">abcdefghij</span>
                        </div>
                        Contoh ID Youtube : <b>abcdefghij</b>
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

<script>
$(document).on('submit', '#form_data', function(e) {
    e.preventDefault();
    id = $(this).attr('data-id');

    $.ajax({
        type: "POST",
        url: "<?=site_url('api/video/save/');?>" + id,
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

$(document).on('click', '#addUsaha', function(e) {
    e.preventDefault();
    $('#modal_data').modal('show');
    $('#form_data').trigger('reset');
    $('#form_data').attr('data-id', 'new');

});

$(document).on('click', '#ubahData', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    $('#form_data').attr('data-id', id);

    // Fetch data using getJSON
    $.getJSON('<?=site_url('api/video/get/')?>' + id, function(response) {
        // Populate the form fields with the fetched data
        $('#link').val(response.link);



    }).fail(function() {
        alert('Error fetching data. Please try again.');
    });

    $('#modal_data').modal('show');
});

$(document).on('click', '#hapusUsaha', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');

    var url = '<?=site_url('api/video/delete/')?>' + id; // Set the correct URL for your delete endpoint

    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        $.ajax({
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content') // Atau ambil dari input hidden
            },
            body: JSON.stringify({ _method: 'DELETE' }),
            url: url,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    tabel.ajax.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    }

});
</script>

<?= $this->endSection() ?>