<?= $this->extend('template/publik/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Hakim
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<div class="separator separator-dashed my-5"></div>


<section class="mt-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="text-primary font-bebas fs-1"><?= $label ?></h3>
                <?php foreach ($pegawai as $row) { ?>
                            <table
                                class="table table-row-bordered border-top border-primary border-2  table-striped  gy-1 fs-6 bg-white">
                                <tr>
                                    <td rowspan="6" class="w-150px p-2">
                                        <img src="https://www.pt-sultra.go.id/main/images/easy-access-pn/pn-kka.png" class="rounded"
                                            alt="" width="100%">
                                    </td>
                                    <td class="min-w-200px ps-3 align-middle" colspan="2">
                                        <span class="text-primary fs-2 fw-bolder"><?= $row['nama'] ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle" width="30%">NIP</td>
                                    <td class="ps-3 align-middle"><?= $row['nip'] ?></td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">Jabatan</td>
                                    <td class="ps-3 align-middle"><?= $row['jabatan'] ?></td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">Pangkat/ Golongan</td>
                                    <td class="ps-3 align-middle"><?= $row['pangkat'] ?>/<?= $row['golongan'] ?></td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle">Tempat, Tanggal Lahir</td>
                                    <td class="ps-3 align-middle"><?= $row['tempat_lahir'] ?>, <?= $row['tgl_lahir'] ?></td>
                                </tr>
                                <tr>
                                    <td class="ps-3 align-middle"></td>
                                    <td class="ps-3 align-middle">
                                        <a href="#" class="btn btn-primary btn-sm">Selengkapnya</a>
                                    </td>
                                </tr>
                            </table>
                <?php } ?>
            </div>
            <div class="col-lg-4 rounded p-4 border border-gray-300" style="background-color: #F8FAFC">
                <h3 class="text-primary fs-1 font-bebas">Pencarian</h3>
                <input type="text" class="form-control mb-5" placeholder="Masukkan kata kunci">
                <div class="rounded border border-1 border-primary mb-3">
                    <div class="rounded-top px-3 py-1 bg-primary text-white text-center fs-3 font-bebas">Jam Pelayanan
                        PTSP</div>
                    <div class="p-0">

                        <table class="table table-row-bordered  table-striped  gy-1 fs-6 bg-white">
                            <thead>
                                <tr class="bg-warning">
                                    <th class="text-center fw-bold">Hari</th>
                                    <th class="text-center fw-bold">Buka</th>
                                    <th class="text-center fw-bold">Istirahat</th>
                                    <th class="text-center fw-bold">Tutup</th>
                                </tr>
                            </thead>
                            <tr>
                                <td class="fw-bold p-2 ">Senin <br>s/d Kamis
                                </td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                                <td class="text-end text-primary fw-bolder p-2">12.00-13.00</td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                            </tr>
                            <tr>
                                <td class="fw-bold p-2 ">Jumat
                                </td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                                <td class="text-end text-primary fw-bolder p-2">12.00-13.00</td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                            </tr>

                        </table>
                    </div>
                </div>

                <div class="rounded border border-1 border-primary mb-3">
                    <div class="rounded-top px-3 py-1 bg-primary text-white text-center fs-3 font-bebas">Jam Kerja</div>
                    <div class="p-0">

                        <table class="table table-row-bordered  table-striped  gy-1 fs-6 bg-white">
                            <thead>
                                <tr class="bg-warning">
                                    <th class="text-center fw-bold">Hari</th>
                                    <th class="text-center fw-bold">Buka</th>
                                    <th class="text-center fw-bold">Istirahat</th>
                                    <th class="text-center fw-bold">Tutup</th>
                                </tr>
                            </thead>
                            <tr>
                                <td class="fw-bold p-2 ">Senin <br>s/d Kamis
                                </td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                                <td class="text-end text-primary fw-bolder p-2">12.00-13.00</td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                            </tr>
                            <tr>
                                <td class="fw-bold p-2 ">Jumat
                                </td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                                <td class="text-end text-primary fw-bolder p-2">12.00-13.00</td>
                                <td class="text-end text-primary fw-bolder p-2">08.00</td>
                            </tr>

                        </table>
                    </div>
                </div>


                <div class="separator separator-dashed my-2"></div>
                <div class="rounded border border-1 border-primary mb-3">
                    <div class="rounded-top px-3 py-1 bg-primary text-white text-center fs-3 font-bebas">Statistik
                        Pengunjung</div>
                    <table class="table table-row-bordered  table-striped  gy-1 fs-6 bg-white">
                        <tr>
                            <td class="fw-bold p-2 ">
                                <i class="bi bi-calendar2-week"></i> Hari ini
                            </td>
                            <td class="text-end text-primary fw-bolder p-2"><?= $visitor['today'] ?> orang</td>
                        </tr>
                        <tr>
                            <td class="fw-bold p-2">
                                <i class="bi bi-calendar2-week"></i> Kemarin
                            </td>
                            <td class="text-end text-primary fw-bolder p-2"><?= $visitor['yesterday'] ?> orang</td>
                        </tr>
                        <tr>
                            <td class="fw-bold p-2 ">
                                <i class="bi bi-calendar2-week"></i> Minggu ini
                            </td>
                            <td class="text-end text-primary fw-bolder p-2"><?= $visitor['this_week'] ?> orang</td>
                        </tr>
                        <tr>
                            <td class="fw-bold p-2 ">
                                <i class="bi bi-calendar2-week"></i> Bulan ini
                            </td>
                            <td class="text-end text-primary fw-bolder p-2"><?= $visitor['this_month'] ?> orang</td>
                        </tr>
                        <tr>
                            <td class="fw-bold p-2 ">
                                <i class="bi bi-calendar2-week"></i> Total
                            </td>
                            <td class="text-end text-primary fw-bolder p-2"><?= $visitor['total'] ?> orang</td>
                        </tr>
                    </table>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text bg-primary text-white" id="basic-addon1">Pengunjung Online</span>
                    <input type="text" class="form-control" value="<?= $visitor['active_users'] ?> orang" readonly>
                </div>

            </div>
        </div>

    </div>
</section>



</section>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="<?= base_url('assets/js/flickity.pkgd.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/flickity.min.css'); ?>">
<script src="
<?= base_url('assets/js/splide.min.js'); ?>
"></script>
<link href="
<?= base_url('assets/css/splide.min.css'); ?>
" rel="stylesheet">

<script>
    $('.main-carousel').flickity({
        // options
        cellAlign: 'left',
        contain: true
    });

    var splide = new Splide('.splide', {
        perPage: 6,
        //   rewind : true,
        type: 'loop',
        autoplay: 'start',
        breakpoints: {
            1024: { // Layar sedang, maksimal 1024px
                perPage: 5, // Tampilkan 5 item
            },
            768: { // Layar kecil, maksimal 768px
                perPage: 3, // Tampilkan 3 item
            }
        }
    });

    splide.mount();
</script>

<?= $this->endSection() ?>