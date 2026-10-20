<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Akun pengguna</title></head>
<body><main><h1>Selamat datang, <?= esc(session('nama')) ?></h1><p>Akun Anda telah aktif.</p><a href="<?= site_url('/') ?>">Kembali ke website</a> | <a href="<?= site_url('logout') ?>">Keluar</a></main></body></html>
