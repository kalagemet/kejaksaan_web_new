<!-- pilih template -->
<?= $this->extend('template/admin/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
menu
<?= $this->endSection() ?>

<style>
    .dt-rowGroup {
        background-color: #f9f9f9;
        font-weight: bold;
        text-align: left;
    }
    .select2-container .select2-selection--single {
        height: 50px!important;
    }

</style>

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
                    Data Menu
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
                    <li class="breadcrumb-item text-muted">Menu</li>
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
                        Kelola Menu
                        <div class="ms-3">
                            <select class="form-control" id="filter_navbar">
                                <?php foreach($navbar as $row) { 
                                    if($row !== 'Beranda'){?>
                                    <option value="<?= $row ?>"><?= $row ?></option>
                                <?php }
                            } ?>
                            <option value='footer'>Footer</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-toolbar">
                        
                        &nbsp;
                        <button class="btn btn-primary btn-sm" id="kelolaGrupMenu">Kelola Grup Menu</button>
                        &nbsp;
                        <button class="btn btn-warning btn-sm" id="addMenu">Menu baru</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-dashed gx-2 gy-4 align-middle" id="dt_halaman">
                            <thead>
                                <tr class="fw-bolder bg-light">
                                    <th class="ps-5">No</th>
                                    <th class="min-w-300px">Judul menu</th>
                                    <th class="min-w-200px">Grup Menu</th>
                                    <th class="min-w-200px">Lokasi</th>
                                    <th class="min-w-200px">Link</th>
                                    <th class="min-w-200px">Order</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Data Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_data" method="POST" enctype="multipart/form-data" data-id="new">
            <?= csrf_field(); ?>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="judul" class="form-label required">Lokasi</label>
                        <select name="lokasi" id="lokasi" class="form-control">
                            <option value="navbar">Menu atas (Navbar)</option>
                            <option value="footer">Menu bawah (Footer/Bottom)</option>
                        </select>
                    </div>
                    <div class="mb-3" id="grup_section">
                        <label for="nama_usaha" class="form-label required">Grup Menu</label>
                        <select name="grup_menu" id="grup_menu" class="form-control">
                            <option value="">Pilih Grup Menu</option>
                            <option value="single">Tanpa Grup Menu</option>
                            <?php
                            foreach ($grup_menu as $key => $value) {
                            ?>
                                <option value="<?= $value['id'] ?>"> <?= $value['navbar'] ?> - <?= $value['grup_menu'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="judul" class="form-label required">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="judul" class="form-label required">Tipe Menu</label>
                        <select name="tipe" id="tipe" class="form-control">
                            <option value="">Pilih Tipe Menu</option>
                            <option value="halaman">Halaman</option>
                            <option value="berita">Berita</option>
                            <option value="external">Link External</option>
                        </select>
                    </div>
                    <div class="mb-3" id="content_section" hidden>
                        <label for="content" class="form-label required">Content</label>
                        <select name="content" id="content" class="form-control"></select>
                    </div>
                    <div class="mb-3" id="link_section" hidden>
                        <label for="link" class="form-label required">Link</label>
                        <input type="text" class="form-control" id="link" name="link" required>
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
<!-- <script src="<?= $_baseurl ?>assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script> -->
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

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
        "url": "<?= $_baseurl . '/api/menu/all' ?>?filter="+$('#filter_navbar').val(),
    },
    "autoWidth": false,
    "filter": true,
    "info": true,
    "rowCallback": function(row, data, displayIndex) {
        var pageInfo = $('#dt_halaman').DataTable().page.info();
        var index = pageInfo.start + displayIndex + 1; // Continuous index
        $('td:eq(0)', row).html(index);
        return row;
    },
    "columns": [
        {
            data: null, 
            render: function(data, type, row, meta) {
                return meta.row + 1;
            }
        },
        {
            data: 'judul',
            render: function(data, type, col, meta) {
                return `<span class="fw-bold fs-6">${data}</span>`;
            }
        },
        {
            data: 'grup_menu',
            visible: false,
            render: function(data, type, col, meta) {
                return `<p class="fw-bold text-primary">${col.navbar}</p>${col.grup_menu}`;
            }
        },
        {
            data: 'lokasi',
            render: function(data, type, col, meta) {
                return `<span class="">${data}</span>`;
            }
        },
        {
            data: 'link',
            render: function(data, type, col, meta) {
                if (col.tipe == 'internal') {
                    if (col.relasi == 'halaman') {
                        var url = `Halaman : ${col.relasi_judul}`;
                    } else if (col.relasi == 'berita') {
                        var url = `Berita : ${col.relasi_judul}`;
                    } 
                } else {
                    var url = data;
                }
                return `<span class="">${url}</span>`;
            }
        },
        {
            data: 'urutan',
            render: function(data, type, col, meta) {

                var disabled_naik = '';
                var disabled_turun = '';
                if (col.urutan == 1) {
                    disabled_naik = 'disabled'
                }
                if (col.urutan == col.last_urutan) {
                    disabled_turun = 'disabled'
                }
                let btn_naik = `<button class="btn btn-sm btn-icon btn-primary btn-naik" ${disabled_naik} data-jenis="menu" data-id="${col.id}"><i class="bi bi-caret-up"></i></button> `;
                let btn_turun = `<button class="btn btn-sm btn-icon btn-primary btn-turun" ${disabled_turun} data-jenis="menu" data-id="${col.id}"><i class="bi bi-caret-down"></i></button> `;
                return col.is_child == 1 ? `<span class="text-center">
                            ${data} 
                            &nbsp;
                            ${btn_naik}
                            ${btn_turun}
                        </span>` : '';
            }
        },
        {
            data: 'ins_satkerkd',
            render: function(data, type, col, meta) {
                return `
                <button class="btn btn-sm btn-primary btn-edit" data-id="${col.id}">Edit</button> 
                <button class="btn btn-sm btn-warning btn-hapus" data-id="${col.id}">Hapus</button>`;
            }
        },
        {
            data: 'created_at',
            visible: false
        }
    ],
    "rowGroup": {
        "dataSrc": "grup_menu", // Specify the column name to group by
        "startRender": function (rows, group) {
            // Customize HTML for the group header
            let dataRow = rows.data()[0];
            var disabled_naik = '';
            var disabled_turun = '';
            var urutan = '';
            var btnNaik = '';
            var btnTurun = '';
            if (dataRow.urutan_grup == 1) {
                disabled_naik = 'disabled'
            }
            if (dataRow.urutan_grup == dataRow.last_urutan_grup) {
                disabled_turun = 'disabled'
            }
            if ($('#filter_navbar').val() !== 'footer') {
                urutan = `&nbsp;
                            Urutan : ${dataRow.urutan_grup}
                            -  `;
                btnNaik = `<button class="btn btn-sm btn-icon btn-primary btn-naik" ${disabled_naik} data-jenis="grup_menu" data-id="${dataRow.grup_menu_id}"><i class="bi bi-caret-up"></i></button>`;
                btnTurun = `<button class="btn btn-sm btn-icon btn-primary btn-turun" ${disabled_turun} data-jenis="grup_menu" data-id="${dataRow.grup_menu_id}"><i class="bi bi-caret-down"></i></button>`;
            }
            
            return `
                <tr class="group-header">
                    <td colspan="7">
                        <div class="d-flex align-items-center ms-3">
                            ${btnNaik}
                            &nbsp;
                            ${btnTurun}
                            ${urutan}
                            &nbsp;
                            <span class="fw-bold text-primary">${dataRow.navbar}: ${group}</span>
                            <span class="ms-3 text-muted">${dataRow.is_child == 1 ? "("+rows.count()+ 'Sub Menu)' : '' }</span>
                        </div>
                    </td>
                </tr>
            `;
        }
    },
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
        toastr.clear();
    },
    preDrawCallback: function() {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
    }
};

tabel = $('#dt_halaman').DataTable(dtc);

$(document).on('change', '#filter_navbar', function() {
    var newUrl = "<?= $_baseurl . '/api/menu/all' ?>?filter=" + $(this).val();
    // Update DataTable URL
    tabel.ajax.url(newUrl).load();
});

$(document).on('submit', '#form_data', function(e) {
    e.preventDefault();
    id = $(this).attr('data-id');
    let formData = new FormData(this);
    formData.append('navbar', $('#filter_navbar').val()); 
    
    $.ajax({
        type: "POST",
        url: "<?=site_url('api/menu/save/');?>" + id,
        data: formData,
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

$(document).on('change', '#lokasi', function(e) {
    e.preventDefault();
    if (this.value == 'navbar') {
        $('#grup_section').attr('hidden',false);
        $('#grup_menu').attr('required',true);
    } else {
        $('#grup_section').attr('hidden',true);
        $('#grup_menu').attr('required',false);
    }
});
$(document).on('change', '#tipe', function(e) {
    e.preventDefault();
    if (this.value == 'halaman') {
        $.getJSON('<?=site_url('api/pages/all')?>', function(response) {
            console.log(response)
            $('#content').html('');
            var html = '';
            $.each(response.data,function (key,row) {  
                html += `<option value="${row.slug}"> ${row.judul}</option>`
            })
            $('#content').append(html);
            $('#content').select2({
                dropdownParent: $('#modal_data'),
                placeholder: 'Select an option', // Optional placeholder
                allowClear: true                // Optional clear button
            });
        }).fail(function() {
            alert('Error fetching data. Please try again.');
        });
        $('#content_section').attr('hidden',false);
        $('#link_section').attr('hidden',true);
        $('#link').attr('required',false);
        $('#content').attr('required',true);
    } else if (this.value == 'berita') {
        $.getJSON('<?=site_url('api/berita/all/berita')?>', function(response) {
            console.log(response)
            $('#content').html('');
            var html = '';
            $.each(response.data,function (key,row) {  
                html += `<option value="${row.slug}"> ${row.judul}</option>`
            })
            $('#content').append(html);
            $('#content').select2({
                dropdownParent: $('#modal_data'),
                placeholder: 'Select an option', // Optional placeholder
                allowClear: true                // Optional clear button
            });
        }).fail(function() {
            alert('Error fetching data. Please try again.');
        });
        $('#content_section').attr('hidden',false);
        $('#link_section').attr('hidden',true);
        $('#link').attr('required',false);
        $('#content').attr('required',true);
    } else {
        $('#content_section').attr('hidden',true);
        $('#link_section').attr('hidden',false);
        $('#link').attr('required',true);
        $('#content').attr('required',false);
    }
});

$(document).on('click', '.btn-naik', function(e) {
    e.preventDefault();
    id = $(this).attr('data-id');
    jenis = $(this).attr('data-jenis');
    console.log(jenis)

    changeOrder(id,jenis,'naik')
});

$(document).on('click', '.btn-turun', function(e) {
    e.preventDefault();
    id = $(this).attr('data-id');
    jenis = $(this).attr('data-jenis');
    console.log(jenis)

    changeOrder(id,jenis,'turun')
});

$(document).on('click', '#kelolaGrupMenu', function(e) {
    e.preventDefault();
    window.location.href = `admin/grup-menu`
});

$(document).on('click', '#addMenu', function(e) {
    e.preventDefault();
    $('#modal_data').modal('show');
    $('#ubahGambar').hide();
    $('#form_data').trigger('reset');
    $('#form_data').attr('data-id', 'new');

});

$(document).on('click', '.btn-edit', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    $('#form_data').attr('data-id', id);

    // Fetch data using getJSON
    $.getJSON('<?=site_url('api/menu/get/')?>' + id, function(response) {
        console.log(response)
        // Populate the form fields with the fetched data
        if (response.lokasi == 'navbar') {
            $('#grup_section').attr('hidden',false);
            $('#grup_menu').attr('required',true);
        } else {
            $('#grup_section').attr('hidden',true);
            $('#grup_menu').attr('required',false);
        }
        $('#lokasi').val(response.lokasi);
        $('#grup_menu').val(response.is_child == 1 ? response.grup_menu_id : 'single');
        $('#judul').val(response.judul);
        if (response.tipe == 'external') {
            $('#tipe').val(response.tipe);
            $('#link').val(response.link);

            $('#content_section').attr('hidden',true);
            $('#link_section').attr('hidden',false);
            $('#link').attr('required',true);
            $('#content').attr('required',false);
        }else{
            if (response.relasi == 'halaman') {
                $.getJSON('<?=site_url('api/pages/all')?>', function(response_pages) {
                    $('#content').html('');
                    var html = '';
                    $.each(response_pages.data,function (key,row) {  
                        html += `<option value="${row.slug}" ${row.slug == response.content ? 'selected' : ''}> ${row.judul}</option>`
                    })
                    $('#content').append(html);
                    $('#content').select2({
                        dropdownParent: $('#modal_data'),
                        placeholder: 'Select an option', // Optional placeholder
                        allowClear: true                // Optional clear button
                    });
                    $('#tipe').val('halaman');
                }).fail(function() {
                    alert('Error fetching data. Please try again.');
                });
            } else if (response.relasi == 'berita') {
                $.getJSON('<?=site_url('api/berita/all/berita')?>', function(response_berita) {
                    $('#content').html('');
                    var html = '';
                    $.each(response_berita.data,function (key,row) {  
                        html += `<option value="${row.slug}" ${row.slug == response.content ? 'selected' : ''}> ${row.judul}</option>`
                    })
                    $('#content').append(html);
                    $('#content').select2({
                        dropdownParent: $('#modal_data'),
                        placeholder: 'Select an option', // Optional placeholder
                        allowClear: true                // Optional clear button
                    });
                    $('#tipe').val('berita');
                }).fail(function() {
                    alert('Error fetching data. Please try again.');
                });
            }
            $('#content_section').attr('hidden',false);
            $('#link_section').attr('hidden',true);
            $('#link').attr('required',false);
            $('#content').attr('required',true);
        }
    }).fail(function() {
        alert('Error fetching data. Please try again.');
    });

    $('#modal_data').modal('show');
});

$(document).on('click', '.btn-hapus', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');

    var url = '<?=site_url('api/menu/delete/')?>' + id; // Set the correct URL for your delete endpoint

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
                    location.reload();
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

function changeOrder(id,jenis,direction) {
    if (jenis == 'menu') {
        var url = "<?=site_url('api/menu/change-order/');?>" + direction + "/"+ id;
    } else {
        var url = "<?=site_url('api/grup-menu/change-order/');?>" + direction + "/"+ id;
    }
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        success: function(response) {
            toastr.success('Data berhasil disimpan');
            tabel.ajax.reload();
        },
        error: function(xhr) {
            alert(xhr.responseJSON.messages);
        }
    });
}
</script>
<?= $this->endSection() ?>