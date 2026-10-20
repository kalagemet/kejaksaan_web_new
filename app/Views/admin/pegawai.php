<?= $this->extend('template/admin/layout') ?>
<?= $this->section('title') ?>Pegawai<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card"><div class="card-body">
<h1>Kelola pegawai</h1><a class="btn btn-primary mb-5" href="<?= site_url('admin/pegawai/form/new') ?>">Tambah pegawai</a>
<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Nama</th><th>Jenis</th><th>Jabatan</th><th>Urutan</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($pegawai as $row): ?>
<tr><td><?= esc($row['nama']) ?></td><td><?= esc($row['jenis_nama']) ?></td><td><?= esc($row['jabatan']) ?></td><td><?= esc($row['urutan']) ?></td><td>
<a class="btn btn-sm btn-primary" href="<?= site_url('admin/pegawai/form/' . $row['id']) ?>">Edit</a>
<form class="d-inline delete-pegawai" action="<?= site_url('api/pegawai/delete/' . $row['id']) ?>" method="post"><?= csrf_field() ?><button class="btn btn-sm btn-danger">Hapus</button></form>
</td></tr>
<?php endforeach ?>
</tbody></table></div></div></div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.querySelectorAll('.delete-pegawai').forEach(function (form) {
    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        if (!confirm('Hapus pegawai ini?')) return;
        try {
            const response = await fetch(form.action, {method: 'POST', body: new FormData(form), headers: {'X-Requested-With': 'XMLHttpRequest'}});
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Penghapusan gagal.');
            window.location.reload();
        } catch (error) { alert(error.message); }
    });
});
</script>
<?= $this->endSection() ?>
