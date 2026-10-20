<?= $this->extend('template/admin/layout') ?>
<?= $this->section('title') ?>Ulasan Pengunjung<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div class="app-container container-fluid">
            <div class="card">
                <div class="card-header"><h1 class="card-title fw-bold">Ulasan Pengunjung</h1></div>
                <div class="card-body">
                    <p class="text-muted">Daftar rating, saran, dan kritik pelayanan website. Ulasan terbaru ditampilkan terlebih dahulu.</p>
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-dashed gx-3 gy-4 align-middle">
                            <thead><tr class="fw-bold bg-light"><th>No</th><th>Tanggal</th><th>Nama</th><th>Nomor telepon</th><th>Email</th><th>Rating</th><th>Saran atau kritik</th></tr></thead>
                            <tbody>
                            <?php if (!$ulasan): ?>
                                <tr><td colspan="7" class="text-center text-muted py-10">Belum ada ulasan dari pengunjung.</td></tr>
                            <?php endif ?>
                            <?php foreach ($ulasan as $index => $row): ?>
                                <tr>
                                    <td><?= ($pager->getCurrentPage('ulasan') - 1) * 20 + $index + 1 ?></td>
                                    <td class="text-nowrap"><?= esc($row['created_at']) ?></td>
                                    <td><?= esc($row['nama']) ?></td>
                                    <td class="text-nowrap"><?= esc($row['no_hp']) ?></td>
                                    <td><?= esc($row['email']) ?></td>
                                    <td class="text-nowrap"><span class="text-warning" aria-hidden="true"><?= str_repeat('★', (int) $row['rating']) ?></span><span class="d-block fs-7"><?= (int) $row['rating'] ?>/5 bintang</span></td>
                                    <td style="min-width:240px;max-width:480px;white-space:pre-wrap;overflow-wrap:anywhere"><?= esc($row['isi']) ?></td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <?= $pager->links('ulasan', 'custom_pager') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
