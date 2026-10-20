<?php
helper('masyarakat');
$labels = \App\Models\PengaduanModel::STATUS;
$terminal = in_array($laporan['status'], ['ditolak', 'penyelidikan', 'dilimpahkan'], true);
$passed = array_column($riwayat, 'status');
$base = $isAdmin ? 'admin/pengaduan/' : 'masyarakat/pengaduan/';
?>
<div class="d-flex flex-wrap justify-content-between gap-4 mb-5">
    <div>
        <h1 class="fs-2"><?= esc($laporan['nomor']) ?></h1><span class="text-muted">Diajukan
            <?= waktu_layanan($laporan['created_at']) ?></span>
    </div><span
        class="badge badge-light-primary portal-status align-self-start"><?= esc($labels[$laporan['status']] ?? $laporan['status']) ?></span>
</div>
<!-- <ol class="laporan-steps" aria-label="Progres pengaduan">
    <?php foreach (['diajukan' => 'Laporan diajukan', 'penelaahan' => 'Penelaahan', 'puldata_buket' => 'Puldata / buket', 'tindak_lanjut' => 'Tindak lanjut', 'hasil' => 'Hasil penanganan'] as $step => $title):
        $done = $step === 'hasil' ? $terminal : in_array($step, $passed, true);
        $active = $step === 'hasil' ? $terminal : $step === $laporan['status']; ?>
    <li class="<?= $done ? 'is-done' : '' ?> <?= $active ? 'is-current' : '' ?> <?= $step === 'hasil' && $laporan['status'] === 'ditolak' ? 'is-rejected' : '' ?>"
        <?= $active ? 'aria-current="step"' : '' ?>>
        <span><?= esc($title) ?></span><small><?= $step === 'hasil' && $terminal ? esc($labels[$laporan['status']]) : ($terminal && !$done ? 'Tidak dilalui' : ($done ? 'Tercatat' : 'Menunggu')) ?></small>
    </li>
    <?php endforeach ?>
</ol> -->
<div class="row g-6">
    <div class="col-lg-7">
        <div class="card mb-6">
            <div class="card-body p-7">
                <h2 class="fs-4">Data laporan</h2>
                <dl class="row mt-5">
                    <dt class="col-sm-3">Nama</dt>
                    <dd class="col-sm-9"><?= esc($laporan['nama']) ?></dd>
                    <dt class="col-sm-3">NIK</dt>
                    <dd class="col-sm-9"><?= esc($laporan['nik']) ?></dd>
                    <dt class="col-sm-3">Alamat</dt>
                    <dd class="col-sm-9 portal-prewrap"><?= esc($laporan['alamat']) ?></dd>
                </dl>
                <h3 class="fs-5 mt-6">Uraian singkat</h3>
                <p class="portal-prewrap"><?= esc($laporan['uraian']) ?></p>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-7">
                <h2 class="fs-4">Dokumen dan bukti dukung</h2>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($berkas as $file): ?>
                        <li class="border-bottom py-4"><span
                                class="badge badge-light mb-2"><?= esc(ucfirst($file['jenis'])) ?></span><br><a
                                class="text-break"
                                href="<?= site_url($base . $laporan['id'] . '/berkas/' . $file['id']) ?>"><?= esc($file['nama_asli']) ?></a><small
                                class="d-block text-muted"><?= number_format($file['ukuran'] / 1024, 1) ?> KB &middot; Unduh
                                dokumen</small></li><?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body p-7">
                <h2 class="fs-4">Riwayat dan tanggapan</h2>
                <ol class="laporan-history list-unstyled mt-5">
                    <?php foreach ($riwayat as $item): ?>
                        <li>
                            <h3 class="fs-6"><?= esc($labels[$item['status']] ?? $item['status']) ?></h3><small
                                class="text-muted"><?= waktu_layanan($item['created_at']) ?><?= $item['admin_nama'] ? ' · ' . esc($item['admin_nama']) : '' ?></small>
                            <p class="portal-prewrap mt-3"><?= esc($item['tanggapan']) ?></p>
                            <?php if ($item['tujuan_pelimpahan']): ?>
                                <p><strong>Tujuan pelimpahan:</strong>
                                    <?= esc($item['tujuan_pelimpahan']) ?></p><?php endif ?>
                        </li><?php endforeach ?>
                </ol>
            </div>
        </div>
    </div>
</div>