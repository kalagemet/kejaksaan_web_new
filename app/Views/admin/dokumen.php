<!-- pilih template -->
<?= $this->extend('template/admin/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Dokumen
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
                    Data Dokumen
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
                        Dokumen
                    </div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary btn-sm" id="addUsaha">Tambah data</button>
                    </div>
                </div>
                <div class="card-body">
<div class="p2 bg-light-success border border-gray-300 p-2 rounded rounded-2">
    Anda bisa mengunggah file berisi SOP atau dokumen penting lainnya yang dapat diunduh oleh Publik di sini
</div>

                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-dashed gx-2 gy-4 align-middle" id="dt_halaman">
                            <thead>
                                <tr class="fw-bolder bg-light">
                                    <th class="ps-5">No</th>
                                    <th class="min-w-100px">File</th>
                                    <th class="min-w-500px">Judul</th>
                                    <th class="min-w-200px">Aksi</th>
                                    <th class="min-w-200px">Aksi</th>
                                </tr>
                            </thead>


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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_data" enctype="multipart/form-data" data-id="new">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            
                            <div class="mb-3">
                                <label for="nama_usaha" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="judul" name="judul" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama_usaha" class="form-label">Unggah File (pdf)</label>
                                <input type="file" class="form-control" id="gambar" name="gambar">
                            </div>
                            <input type="hidden" id="file_lama" name="file_lama">
                            <p id="file_info" class="mt-2"></p>
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

<?= $this->section('scripts') ?>
<link href="<?= $_baseurl ?>/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<script src="<?= $_baseurl ?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="<?= $_baseurl ?>assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>

<script>
   
    
function strip(html) {
    let doc = new DOMParser().parseFromString(html, 'text/html');
    docx = doc.body.textContent || "";
    if (docx.length < 70) {
        return docx
    } else {
        return docx.substring(0, 70) + ' ...'
    }
}

let dtc = {
    "ajax": {
        "url": "<?= $_baseurl . '/api/dokumen/all' ?>",
    },
    "autoWidth": false,
    "filter": true,
    "info": true,
    "order": [
        [3, 'asc'],
        [0, 'desc']
    ],
    "rowCallback": function(row, data, displayIndex) {
        var pageInfo = $('#dt_halaman').DataTable().page.info();
        var index = pageInfo.start + displayIndex + 1; // Continuous index
        $('td:eq(0)', row).html(index);
        return row;
    },
    "columns": [{
            data: 'id'
        },
        {
            data: 'file',
            render: function(data, type, col, meta) {
                return `<a href="<?= $_baseurl ?>/uploads/${data}" target="_blank" class="btn btn-sm btn-primary">Unduh</a>`;
            }
        },
        {
            data: 'judul',
            render: function(data, type, col, meta) {
                return `<span class="fw-bold fs-6 text-primary">${data}</span><br>
                Tanggal : ${moment(col.created_at).format('DD-MM-YYYY HH:mm:ss')}   
                `;
            }
        },
        {
            data: 'id',
            render: function(data, type, col, meta) {
                return `<button class="btn btn-sm btn-primary" id="ubahData" data-id="${data}">Edit</button> 
                <button class="btn btn-sm btn-warning btn-hapus" id="hapusData" data-id="${data}">Hapus</button>`

            }
        },
        {
            data: 'created_at',
            visible: false
        }
    ],
    "dom": "<'row'" +
        "<'col-sm-6 d-flex align-items-center justify-conten-start'f>" +
        "<'col-sm-6 d-flex align-items-center justify-content-end'>" +
        ">" +

        "<'table-responsive'tr>" +

        "<'row'" +
        "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
        "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
        ">",
    language: {
        search: '<span>Cari </span> _INPUT_',
        searchPlaceholder: 'Masukkan kata kunci pencarian...',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {
            'first': 'Awal',
            'last': 'Akhir',
            'next': '&rarr;',
            'previous': '&larr;'
        }
    },
    drawCallback: function() {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        toastr.clear()
    },
    preDrawCallback: function() {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
    }
}

tabel = $('#dt_halaman').DataTable(dtc);

$(document).on('submit', '#form_data', function(e) {
    e.preventDefault();
    id = $(this).attr('data-id');

    $.ajax({
        type: "POST",
        url: "<?=site_url('api/dokumen/save/');?>" + id,
        data: new FormData(this),
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(response) {
            toastr.success('Data berhasil disimpan');
            $('#modal_data').modal('hide');
            tabel.ajax.reload();
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

$(document).on('click', '#ubahGambar', function(e) {
    $('#gambar').prop('disabled', false);

});

$(document).on('click', '#ubahData', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    $('#form_data').attr('data-id', id);

    // Fetch data using getJSON
    $.getJSON('<?=site_url('api/dokumen/get/')?>' + id, function(response) {
        // Populate the form fields with the fetched data
        $('#judul').val(response.judul);
     

        if (response.file) {
            $('#file_lama').val(response.file);
            $('#file_info').html(
                `<a href="<?=base_url('uploads/')?>${response.file}" target="_blank">
                    Lihat File Lama (${response.file})
                </a>`
            );
        } else {
            $('#file_info').html('Belum ada file yang diunggah.');
        }

    }).fail(function() {
        alert('Error fetching data. Please try again.');
    });

    $('#modal_data').modal('show');
});

$(document).on('click', '#hapusData', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');

    var url = '<?=site_url('api/dokumen/delete/')?>' + id; // Set the correct URL for your delete endpoint

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