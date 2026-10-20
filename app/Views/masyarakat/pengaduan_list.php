<?= $this->extend('template/masyarakat/layout') ?>
<?= $this->section('title') ?>Pengaduan Saya<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-4 mb-6">
    <h1 class="fs-2">Laporan pengaduan masyarakat</h1><a class="btn btn-primary"
        href="<?= site_url('masyarakat/pengaduan/buat') ?>">Buat aduan baru</a>
</div>
<?= $this->include('masyarakat/partials/alur') ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nomor laporan</th>
                        <th>Tanggal</th>
                        <th>Uraian singkat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$laporan): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-8">Anda belum membuat pengaduan. Klik “Buat
                                aduan baru” untuk memulai.</td>
                        </tr><?php endif ?>
                    <?php foreach ($laporan as $row): ?>
                        <tr>
                            <td><?= esc($row['nomor']) ?></td>
                            <td><?= waktu_layanan($row['created_at']) ?></td>
                            <td class="portal-summary"><?= esc(mb_strimwidth($row['uraian'], 0, 120, '...')) ?></td>
                            <td><span
                                    class="badge badge-light-primary portal-status"><?= esc(\App\Models\PengaduanModel::STATUS[$row['status']] ?? $row['status']) ?></span>
                            </td>
                            <td><a class="btn btn-sm btn-light-primary"
                                    href="<?= site_url('masyarakat/pengaduan/' . $row['id']) ?>">Lihat progres</a></td>
                        </tr><?php endforeach ?>
                </tbody>
            </table>
        </div><?= $pager->links('aduan', 'custom_pager') ?>
    </div>
</div>
<?= $this->endSection() ?>