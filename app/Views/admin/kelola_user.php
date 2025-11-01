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
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Data user
                    satker
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
            <!--begin::Actions-->
            <div class="d-flex flex-row">
                                <div class="p2"><select name="kejati" id="kejati" class="form-control w-200px me-3"
                                        data-control="select2">
                                        <option value="">Pilih kejati</option>
                                        <?php foreach($kejati as $k) { 
                                    echo '<option value="'.$k->ins_satkerkd.'">'.$k->inst_nama.'</option>';
                                }?>
                                    </select></div>
                                <button class="btn btn-success btn-sm" id="btn_refresh">Refresh</button>
                            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Layout-->
            <div class="row g-5 g-xl-10 mb-xl-10">
                <!--begin::Col-->
                <div class="col-lg-12 col-xl-12 col-xxl-12 mb-5 mb-xl-0">
                    <!--begin::Chart widget 3-->
                    <div class="card card-flush overflow-hidden h-md-100">

                        <!--begin::Card body-->
                        <div class="card-body">
                           

                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table
                                    class="table table-row-bordered table-row-dashed gy-4 align-middle dataTable no-footer"
                                    id="dt_permohonan">
                                    <!-- Table heading -->
                                    <thead class="fs-7 text-gray-400 fw-bold text-uppercase">
                                        <tr>
                                            <th class="text-center" style="vertical-align:middle;">No.</th>
                                            <th class="text-center" style="vertical-align:middle;">Instansi</th>
                                            <th class="text-center min-w-150px" style="vertical-align:middle;">Username
                                            </th>
                                            <th class="text-center" style="vertical-align:middle;">Alamat</th>
                                            <th class="text-center" style="vertical-align:middle;">No HP</th>
                                            <th class="text-center min-w-150px" style="vertical-align:middle;">Login
                                                terakhir</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <!-- // Table heading END -->
                                </table>
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Chart widget 3-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Layout-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>

<div class="modal" tabindex="-1" id="modalUbahSatker">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data satker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUbahSatker" method="POST">
                <input type="hidden" name="id_laporan" id="id_laporan">
                <div class="modal-body">
                    <div class="fw-bold fs-4 mb-5">Satker : 00.00 - XXXXXX</div>
                <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp">
                    </div>
                    <div class="mb-10">
                        <label for="exampleInputEmail1" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat">
                    </div>
                    <h3>Ganti password</h3>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Password baru</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Ulangi Password baru</label>
                        <input type="password" class="form-control" id="password_baru" name="password_baru">
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
        "url": "<?= $_baseurl . '/api/admin/getUser/' ?>",
    },
    "autoWidth": false,
    "filter": true,
    "info": true,
    "order": [
        [7, 'asc'],
        [0, 'desc']
    ],
    "rowCallback": function(row, data, iDisplayIndex) {
        var index = iDisplayIndex + 1;
        $('td:eq(0)', row).html(index);
        return row;
    },
    "columns": [{
            data: 'ins_satkerkd'
        },
        {
            data: 'inst_nama',
            render: function(data, type, col, meta) {
                return `<span class="fw-bold fs-6 text-primary">${col.ins_satkerkd} - ${data}</span>`;
            }
        },
        {
            data: 'email',
            render: function(data, type, col, meta) {
                return `<span class="fw-bold">${data}</span>`;
            }
        },
        {
            data: 'alamat',
            render: function(data, type, col, meta) {
                return `<span class="fw-bold">${data}</span>`;
            }
        },
        {
            data: 'no_hp',
            render: function(data, type, col, meta) {
                return data;
            }
        },
        {
            data: 'last_login',
            render: function(data, type, col, meta) {
                if ((data == null) || (data == '0000-00-00 00:00:00')) {
                    return `-`;
                } else {
                    return `(${moment(data).startOf('day').fromNow() }) <br/> ${moment(data).format("DD-MM-YYYY")}`;
                }

            }
        },
        {
            data: 'ins_satkerkd',
            render: function(data, type, col, meta) {
                return `<button class="btn btn-sm btn-primary btn-edit">Edit</button>`

            }
        },
        {
            data: 'ins_satkerkd',
            visible: false
        },
        {
            data: 'ins_satkerkd',
            visible: false,
            render: function(data, type, col, meta) {
                return data.slice(0, 2);

            }
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

tabel = $('#dt_permohonan').DataTable(dtc);

$(document).on('change', '#kejati', function() {
    kjt = $(this).val();
    tabel.columns(8).search(kjt).draw();

});

$('#btn_refresh').on('click', function() {
    // alert(1)
    tabel.columns(8).search('').draw();
});

$(document).on('click', '.btn-edit', function() {
    // alert(stkr.slice(0, 2));
    // $('#instansi').val(stkr);
    $('#modalUbahSatker').modal('show');
    // //alert(stkr);

});

$(document).on('submit', '#formUbahSatker', function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "<?=site_url('api/pelayanan_hukum/ubah_satker');?>",
        data: new FormData(this),
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(response) {
            tabel.ajax.reload();
            $('#modalUbahSatker').modal('hide');
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    });
});
</script>
<?= $this->endSection() ?>