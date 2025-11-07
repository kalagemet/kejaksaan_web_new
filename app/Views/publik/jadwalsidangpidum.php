<?= $this->extend('template/publik/layout') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/jquery.dataTables.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Jadwal Sidang Perkara Tindak Pidana Umum
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<div class="separator separator-dashed my-5"></div>


<section class="mt-10">
    <div class="container">
        <div class="row">
            <h3 class="text-primary font-bebas fs-1">Jadwal Sidang <?= $hari ?></h3>
            <div style="margin-top:1.5rem;">
                <div id="kt_app_container" style="min-height:60vh" class="container">
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
                                                <div class="card-body" id="mainContent"
                                                    style="position: static; zoom: 1; overflow: auto; height: auto;">
                                                    <!--begin::Table container-->
                                                    <div class="table-responsive">
                                                        <!--begin::Table-->
                                                        <div id="dtPerkara_wrapper"
                                                            class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                            <div class="table-responsive">
                                                                <table id="tabelJadwal" class="display"
                                                                    style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Tanggal Sidang</th>
                                                                            <th>Nomor Perkara</th>
                                                                            <!--<th>Sidang Keliling</th>-->
                                                                            <th>Ruangan</th>
                                                                            <th>Agenda</th>
                                                                            <!--<th>Link Detil</th>-->
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
<script src="<?php echo base_url('assets/js/blockui.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/js/jquery.dataTables.min.js') ?>"></script>
<script>
    $(document).ready(function () {
        getJadwalSidang().then(jsonData => {
            $('#tabelJadwal').DataTable({
                data: jsonData.data,
                columns: [{
                    data: 'Tanggal Sidang'
                },
                {
                    data: 'Nomor Perkara'
                },
                // { data: 'Sidang Keliling' },
                {
                    data: 'Ruangan'
                },
                {
                    data: 'Agenda'
                },
                    // { 
                    //     data: 'Detil',
                    //     render: function(data, type, row) {
                    //         if (type === 'display' && data) {
                    //             return `<a href="${data}" target="_blank">Lihat Detil</a>`;
                    //         }
                    //         return 'Tidak ada link';
                    //     }
                    // }
                ]
            });
        });
    });

    async function getJadwalSidang() {
        var tanggal = "<?= $date ?>";
        const url = `/dataJadwalSidangPidum?_=${tanggal}`;
        const options = {
            method: 'GET',
        };
        $('#kt_app_container').block({
            message: 'Loading...',
            css: {
                zIndex: '1011',
                position: 'absolute',
                padding: '15px',
                margin: '0px',
                width: '30%',
                top: '280px',
                left: '462px',
                textAlign: 'center',
                color: 'rgb(255, 255, 255)',
                border: 'none',
                backgroundColor: 'rgb(0, 0, 0)',
                cursor: 'wait',
                borderRadius: '10px',
                opacity: '0.8',
            },
            overlayCSS: {
                zIndex: '1000',
                border: 'none',
                margin: '0px',
                padding: '0px',
                width: '100%',
                height: '100%',
                top: '0px',
                left: '0px',
                backgroundColor: 'rgb(0, 0, 0)',
                opacity: '0.6',
                cursor: 'wait',
                position: 'absolute',
            }
        });
        try {
            const response = await fetch(url, options);
            const htmlString = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlString, 'text/html');
            const tbody = doc.querySelector('tbody');
            if (!tbody) {
                console.error('Elemen <tbody> tidak ditemukan.');
                $('#kt_app_container').unblock();
                return null;
            }
            const rows = tbody.querySelectorAll('tr');

            const data = Array.from(rows).map(row => {
                const cells = row.querySelectorAll('td');
                return {
                    "Tanggal Sidang": cells[1] ? cells[1].innerText.trim() : '',
                    "Nomor Perkara": cells[2] ? cells[2].innerText.trim() : '',
                    // "Sidang Keliling": cells[3] ? cells[3].innerText.trim() : '',
                    "Ruangan": cells[4] ? cells[4].innerText.trim() + " PN Banjarnegara" : '',
                    "Agenda": cells[5] ? cells[5].innerText.trim() : '',
                    // "Detail": cells[6] && cells[6].querySelector('a') ? cells[6].querySelector('a')
                    //     .getAttribute('onclick').match(/\(\'(.*?)\'\)/)[1] : ''
                };
            }).filter(perkara => perkara["Nomor Perkara"].includes('/Pid.'));
            const jsonData = {
                data: data
            };
            $('#kt_app_container').unblock();
            return jsonData;
        } catch (error) {
            console.error('Terjadi kesalahan saat mengambil atau memproses data:', error);
            $('#kt_app_container').unblock();
            return null;
        }
    }
</script>

<?= $this->endSection() ?>