<?= $this->extend('template/admin/layout') ?>
<?= $this->section('title') ?>Form pegawai<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card"><div class="card-body"><h1><?= $id === 'new' ? 'Tambah' : 'Edit' ?> pegawai</h1>
<form id="pegawai-form" action="<?= site_url('api/pegawai/save/' . $id) ?>" method="post" enctype="multipart/form-data">
<?= csrf_field() ?>
<?php foreach (['nama' => 'Nama', 'nip' => 'NIP', 'jabatan' => 'Jabatan', 'tempat_lahir' => 'Tempat lahir', 'tgl_lahir' => 'Tanggal lahir', 'pangkat' => 'Pangkat', 'golongan' => 'Golongan', 'jenis_kelamin' => 'Jenis kelamin', 'agama' => 'Agama', 'urutan' => 'Urutan'] as $key => $label): ?>
<div class="mb-4"><label class="form-label" for="<?= $key ?>"><?= $label ?></label>
<input class="form-control" id="<?= $key ?>" name="<?= $key ?>" type="<?= $key === 'tgl_lahir' ? 'date' : ($key === 'urutan' ? 'number' : 'text') ?>" value="<?= esc($pegawai[$key] ?? '', 'attr') ?>" <?= $key === 'nama' ? 'required' : '' ?>></div>
<?php endforeach ?>
<div class="mb-4"><label class="form-label" for="jenis">Jenis pegawai</label><select class="form-select" id="jenis" name="jenis" required>
<option value="">Pilih jenis pegawai</option>
<?php foreach ($jenisPegawai as $jenis): ?><option value="<?= esc($jenis['jenis_alias'], 'attr') ?>" <?= ($pegawai['jenis'] ?? '') === $jenis['jenis_alias'] ? 'selected' : '' ?>><?= esc($jenis['jenis_nama']) ?></option><?php endforeach ?>
</select></div>
<div class="mb-4"><label class="form-label" for="foto">Foto JPG, PNG, GIF (maksimal 10 MB)</label><input class="form-control" type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/gif"></div>
<p id="pegawai-error" role="alert" class="text-danger"></p><button class="btn btn-primary">Simpan</button> <a href="<?= site_url('admin/pegawai') ?>">Kembali</a>
</form></div></div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.getElementById('pegawai-form').addEventListener('submit', async function (event) {
    event.preventDefault();
    const button = this.querySelector('button');
    button.disabled = true;
    try {
        const response = await fetch(this.action, {method: 'POST', body: new FormData(this), headers: {'X-Requested-With': 'XMLHttpRequest'}});
        const data = await response.json();
        if (!response.ok) throw new Error(typeof data.message === 'object' ? Object.values(data.message).join(' ') : data.message);
        window.location.href = <?= json_encode(site_url('admin/pegawai')) ?>;
    } catch (error) { document.getElementById('pegawai-error').textContent = error.message; }
    finally { button.disabled = false; }
});
</script>
<?= $this->endSection() ?>
