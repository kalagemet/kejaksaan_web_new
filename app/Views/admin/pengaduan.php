<?= $this->extend('template/admin/layout') ?>
<?= $this->section('title') ?>Pengaduan Masyarakat<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card"><div class="card-header"><h1 class="card-title fw-bold">Daftar laporan pengaduan masyarakat</h1></div><div class="card-body">
<form method="get" action="<?= site_url('admin/pengaduan') ?>" class="d-flex flex-wrap gap-3 mb-6"><label class="align-self-center" for="filter-status">Filter status</label><select class="form-select w-auto" name="status" id="filter-status"><option value="">Semua status</option><?php foreach (\App\Models\PengaduanModel::STATUS as $value=>$label): ?><option value="<?= $value ?>" <?= $statusFilter === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach ?></select><button class="btn btn-primary">Tampilkan</button></form>
<div class="table-responsive"><table class="table table-row-bordered align-middle"><thead><tr><th>Nomor / tanggal</th><th>Pelapor / NIK</th><th>Uraian singkat</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php if (!$laporan): ?><tr><td colspan="5" class="text-center py-8 text-muted">Belum ada laporan pada daftar ini.</td></tr><?php endif ?>
<?php foreach ($laporan as $row): ?><tr><td><?= esc($row['nomor']) ?><small class="d-block text-muted"><?= waktu_layanan($row['created_at']) ?></small></td><td><?= esc($row['nama']) ?><small class="d-block text-muted"><?= esc($row['nik']) ?></small></td><td><?= esc(mb_strimwidth($row['uraian'],0,150,'...')) ?></td><td><?= esc(\App\Models\PengaduanModel::STATUS[$row['status']] ?? $row['status']) ?></td><td><a class="btn btn-sm btn-light-primary" href="<?= site_url('admin/pengaduan/' . $row['id']) ?>">Detail dan tanggapi</a></td></tr><?php endforeach ?>
</tbody></table></div><?= $pager->links('aduan','custom_pager') ?></div></div>
<?= $this->endSection() ?>
