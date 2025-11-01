<!-- pilih template -->
<?= $this->extend('template/admin/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Pages
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
                    Data Halaman
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
                        Halaman dan Artikel
                    </div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary btn-sm" id="addUsaha">Tambah data</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-dashed gx-2 gy-4 align-middle" id="dt_halaman">
                            <thead>
                                <tr class="fw-bolder bg-light">
                                    <th class="ps-5">No</th>
                                    <th class="min-w-200px">Judul</th>
                                    <th class="min-w-500px">Isi</th>
                                    <th class="min-w-100px">Informasi cepat</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Data Halaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_data" method="POST" enctype="multipart/form-data" data-id="new">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_usaha" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_usaha" class="form-label">Isi</label>
                        <textarea name="isi" id="isi" class="form-control"></textarea>
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
<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>

<script>
let editor; // Variabel global untuk menyimpan instance editor

ClassicEditor
    .create(document.querySelector('#isi'), {
        // toolbar: [
        //     'heading', '|', 'bold', 'italic', '|', 'alignment', 'imageUpload', 'blockQuote', 'undo', 'redo'
        // ],
        // alignment: {
        //     options: ['left', 'center', 'right', 'justify'] // Optional: Customize alignment options
        // },
        ckfinder: {
            // Opsi konfigurasi CKFinder jika digunakan
            uploadUrl: '<?=site_url('api/berita/uploadImage')?>' // Ganti dengan endpoint untuk upload gambar
        }
    })
    .then(instance => {
        editor = instance; // Simpan instance editor di variabel global
    })
    .catch(error => {
        console.error('Error initializing CKEditor:', error);
    });

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
        "url": "<?= $_baseurl . '/api/pages/all' ?>",
    },
    "autoWidth": false,
    "filter": true,
    "info": true,
    "order": [],
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
            data: 'judul',
            render: function(data, type, col, meta) {
                return `<span class="fw-bold fs-6 text-primary">${data}</span><br/>Dibuat : ${moment(col.created_at).format('DD-MM-YYYY HH:mm:ss')}<br/>Diperbarui : ${moment(col.updated_at).format('DD-MM-YYYY HH:mm:ss')}`;
            }
        },
        {
            data: 'isi',
            render: function(data, type, col, meta) {
                return `<span class="">${strip(data)}</span>`;
            }
        },
        {
            data: 'featured',
            render: function(data, type, col, meta) {
                return `<div class="form-check">
                    <input class="form-check-input" type="checkbox" ${data > 0 ? 'checked' : ''} onclick="checkFeatured('${col.id}')" id="checkbox_${col.id}">
                </div>`;
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
        url: "<?=site_url('api/pages/save/');?>" + id,
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
    $('#form_data').trigger('reset');
    editor.setData('');
    $('#modal_data').modal('show');
    document.getElementById('preview').src = "<?= base_url('assets/media/svg/avatars/blank.svg') ?>";
    $('#gambar').prop('disabled', false);
    $('#ubahGambar').hide();
    
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
    $.getJSON('<?=site_url('api/pages/get/')?>' + id, function(response) {
        // Populate the form fields with the fetched data
        $('#judul').val(response.judul);
        if (typeof editor !== 'undefined') {
            editor.setData(response.isi);
        } else {
            console.error('Editor instance is not defined.');
        }



    }).fail(function() {
        alert('Error fetching data. Please try again.');
    });

    $('#modal_data').modal('show');
});

$(document).on('click', '#hapusData', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');

    var url = '<?=site_url('api/pages/delete/')?>' + id; // Set the correct URL for your delete endpoint

    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content') // Atau ambil dari input hidden
            }
        });
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

function checkFeatured(id) {  
    $.ajax({
        type: "POST",
        url: "<?=site_url('api/pages/featured/');?>" + id,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                toastr.success('Data berhasil disimpan');
                location.reload(); 
            }else{
                toastr.error(response.message);
                $('#checkbox_'+id).prop('checked',false);
                setTimeout(function () {
                    location.reload(); 
                },2000);  
            }
        },
        error: function(xhr) {
            toastr.error(xhr);
            $('#checkbox_'+id).prop('checked',false);
        }
    });
}
</script>
<?= $this->endSection() ?>