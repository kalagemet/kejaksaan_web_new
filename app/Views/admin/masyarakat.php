<?= $this->extend('template/admin/layout') ?>
<?= $this->section('title') ?>Pengguna Masyarakat<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card"><div class="card-header"><h1 class="card-title fw-bold">Daftar pengguna masyarakat</h1></div><div class="card-body"><p class="text-muted">Akun masyarakat terpisah dari akun administrator. Status aktif menunjukkan email telah diverifikasi.</p><div class="table-responsive"><table class="table table-row-bordered align-middle"><thead><tr><th>Nama / NIK</th><th>Email</th><th>Nomor WA</th><th>Profil</th><th>Status</th><th>Terdaftar</th></tr></thead><tbody>
<?php if (!$users): ?><tr><td colspan="6" class="text-center py-8 text-muted">Belum ada pengguna masyarakat.</td></tr><?php endif ?>
<?php foreach ($users as $row): ?><tr><td><?= esc($row['nama']) ?><small class="d-block text-muted"><?= esc($row['nik']) ?></small></td><td class="text-break"><?= esc($row['email']) ?></td><td><?= esc($row['no_wa']) ?><small class="d-block text-muted">Verifikasi WA belum tersedia</small></td><td><?= esc($row['pekerjaan'] ?: '-') ?><br><?= esc($row['tanggal_lahir'] ?: '-') ?><br><small><?= esc($row['alamat'] ?: '-') ?></small></td><td><span class="badge <?= $row['aktif'] ? 'badge-light-success' : 'badge-light-warning' ?>"><?= $row['aktif'] ? 'Aktif / email terverifikasi' : 'Menunggu OTP' ?></span></td><td><?= waktu_layanan($row['created_at']) ?></td></tr><?php endforeach ?>
</tbody></table></div><?= $pager->links('masyarakat','custom_pager') ?></div></div>
<?= $this->endSection() ?>
