<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('user/login', 'Utils::login');
$routes->get('user/register', 'Utils::register');
$routes->get('user/oauth_redirect', 'Utils::redirect_oauth');
$routes->get('user/verifikasi_akun', 'Utils::verifikasi_akun');
$routes->get('user', 'User::index', ['filter' => 'authenticate']);
$routes->get('user/profil', 'User::profil', ['filter' => 'authenticate']);


$routes->get('/captcha', 'Utils::generateCaptcha');
$routes->get('/login/admin', 'Utils::login');
$routes->get('/logout', 'Utils::logout');

$routes->get('/dataBeritaKejagung', 'Publik::dataBeritaKejagung');
$routes->get('/dataVideo', 'Publik::getDataVideo');
$routes->get('/dataCmsPidum', 'Publik::getDataCmsPidum');
$routes->get('/dataJadwalSidangPidum', 'Publik::getDataJadwalSidangPidum');
$routes->get('/getFeedIG', 'Publik::getFeedIG');
$routes->get('/getFeedYT', 'Publik::getFeedYT');

$routes->get('/', 'Publik::home');
$routes->get('/beranda', 'Publik::home');
$routes->get('/berita', 'Publik::berita');
$routes->get('/siaran-pers', 'Publik::siaran_pers');
$routes->get('/pengumuman', 'Publik::pengumuman');
$routes->get('/berita/detail/(:any)', 'Publik::beritaDetail/$1');
$routes->get('/detail-berita/(:any)', 'Publik::beritaDetail/$1');
$routes->get('/page/(:any)', 'Publik::page/$1');
$routes->get('/dokumen', 'Publik::dokumen');
$routes->get('/video', 'Publik::video');
$routes->get('/jadwal-sidang-pidum', 'Publik::jadwalSidangPidum');
$routes->get('/cms-perkara-pidum', 'Publik::cmsPidum');

$routes->get('/admin', 'Admin::dashboard', ['filter' => 'adminonly']);
$routes->get('/admin/slideshow', 'Admin::slideshow', ['filter' => 'adminonly']);
$routes->get('/admin/layanan', 'Admin::layanan', ['filter' => 'adminonly']);
$routes->get('/admin/pages', 'Admin::pages', ['filter' => 'adminonly']);
$routes->get('/admin/grup-menu', 'Admin::grup_menu', ['filter' => 'adminonly']);
$routes->get('/admin/menu', 'Admin::menu', ['filter' => 'adminonly']);
$routes->get('/admin/setting', 'Admin::setting', ['filter' => 'adminonly']);
$routes->get('/admin/berita', 'Admin::berita', ['filter' => 'adminonly']);
$routes->get('/admin/pengumuman', 'Admin::pengumuman', ['filter' => 'adminonly']);
$routes->get('/admin/siaran_pers', 'Admin::siaran_pers', ['filter' => 'adminonly']);
$routes->get('/admin/video', 'Admin::video', ['filter' => 'adminonly']);
$routes->get('/admin/agen', 'Admin::agen', ['filter' => 'adminonly']);
$routes->get('/admin/dokumen', 'Admin::dokumen', ['filter' => 'adminonly']);
$routes->get('/admin/ulasan', 'Admin::ulasan', ['filter' => 'adminonly']);
$routes->get('/admin/pegawai', 'Admin::pegawai', ['filter' => 'adminonly']);
$routes->get('/admin/pegawai/form/(:any)', 'Admin::pegawaiForm/$1', ['filter' => 'adminonly']);
$routes->get('logo/(:segment)', 'Publik::displayLogo/$1');

$routes->get('/admin/profil', 'Admin::profil', ['filter' => 'adminonly']);

// Layanan masyarakat: separate account/session from the administrator CMS.
$routes->group('masyarakat', ['namespace' => 'App\Controllers\Masyarakat'], function ($routes) {
    $routes->get('captcha/(:segment)', 'Auth::captcha/$1');
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::doLogin');
    $routes->get('daftar', 'Auth::register');
    $routes->post('daftar', 'Auth::doRegister');
    $routes->get('lupa-password', 'Auth::forgot');
    $routes->post('lupa-password', 'Auth::doForgot');
    $routes->get('verifikasi', 'Auth::verify');
    $routes->post('verifikasi', 'Auth::doVerify');
    $routes->post('otp/kirim-ulang', 'Auth::resend');
    $routes->get('', 'Portal::dashboard', ['filter' => 'masyarakat']);
    $routes->post('keluar', 'Auth::logout', ['filter' => 'masyarakat']);
    $routes->get('besuk', 'Portal::comingSoon/besuk', ['filter' => 'masyarakat']);
    $routes->get('informasi', 'Portal::comingSoon/informasi', ['filter' => 'masyarakat']);
    $routes->get('profil', 'Portal::profile', ['filter' => 'masyarakat']);
    $routes->post('profil', 'Portal::saveProfile', ['filter' => 'masyarakat']);
    $routes->post('profil/kontak', 'Portal::changeContact', ['filter' => 'masyarakat']);
    $routes->get('profil/foto', 'Portal::photo', ['filter' => 'masyarakat']);
    $routes->get('pengaduan', 'Portal::complaints', ['filter' => 'masyarakat']);
    $routes->get('pengaduan/buat', 'Portal::createComplaint', ['filter' => 'masyarakat']);
    $routes->post('pengaduan', 'Portal::saveComplaint', ['filter' => 'masyarakat']);
    $routes->get('pengaduan/(:num)', 'Portal::complaint/$1', ['filter' => 'masyarakat']);
    $routes->get('pengaduan/(:num)/berkas/(:num)', 'Portal::download/$1/$2', ['filter' => 'masyarakat']);
});
$routes->get('admin/masyarakat', 'AdminMasyarakat::users', ['filter' => 'adminonly']);
$routes->get('admin/pengaduan', 'AdminMasyarakat::complaints', ['filter' => 'adminonly']);
$routes->get('admin/pengaduan/(:num)', 'AdminMasyarakat::detail/$1', ['filter' => 'adminonly']);
$routes->post('admin/pengaduan/(:num)/tanggapan', 'AdminMasyarakat::respond/$1', ['filter' => 'adminonly']);
$routes->get('admin/pengaduan/(:num)/berkas/(:num)', 'AdminMasyarakat::download/$1/$2', ['filter' => 'adminonly']);

// -------

$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('ulasan/save', 'Ulasan::save');
    $routes->post('user/login', 'User::doLogin');
    $routes->post('user/oauth_check', 'User::oauth_check_user');
    $routes->post('user/register', 'User::doRegister');
    $routes->post('user/ubah_password', 'User::ubah_password', ['filter' => 'authenticate']);
    $routes->post('user/update_profil', 'User::update_profil', ['filter' => 'authenticate']);
    $routes->post('user/submit_permohonan', 'User::submit_pelayanan_hukum', ['filter' => 'authenticate']);
    $routes->get('user/getPermohonanByUser/', 'User::getPermohonanByUser', ['filter' => 'authenticate']);
    $routes->get('getKejari/(:any)', 'User::getKejari/$1');
    $routes->get('getProfil', 'User::getUserProfil', ['filter' => 'authenticate']);


    $routes->get('pages/all', 'Pages::getAll', ['filter' => 'adminonly']);
    $routes->get('pages/get/(:any)', 'Pages::getById/$1', ['filter' => 'adminonly']);
    $routes->post('pages/save/(:any)', 'Pages::save/$1', ['filter' => 'adminonly']);
    $routes->post('pages/delete/(:any)', 'Pages::delete/$1', ['filter' => 'adminonly']);
    $routes->post('pages/featured/(:any)', 'Pages::featured/$1', ['filter' => 'adminonly']);

    $routes->get('grup-menu/all', 'GrupMenu::getAll', ['filter' => 'adminonly']);
    $routes->get('grup-menu/get/(:any)', 'GrupMenu::getById/$1', ['filter' => 'adminonly']);
    $routes->post('grup-menu/save/(:any)', 'GrupMenu::save/$1', ['filter' => 'adminonly']);
    $routes->post('grup-menu/delete/(:any)', 'GrupMenu::delete/$1', ['filter' => 'adminonly']);
    $routes->post('grup-menu/change-order/(:any)/(:any)', 'GrupMenu::changeOrder/$1/$2', ['filter' => 'adminonly']);


    $routes->get('menu/all', 'Menu::getAll', ['filter' => 'adminonly']);
    $routes->get('menu/get/(:any)', 'Menu::getById/$1', ['filter' => 'adminonly']);
    $routes->post('menu/save/(:any)', 'Menu::save/$1', ['filter' => 'adminonly']);
    $routes->post('menu/delete/(:any)', 'Menu::delete/$1', ['filter' => 'adminonly']);
    $routes->post('menu/change-order/(:any)/(:any)', 'Menu::changeOrder/$1/$2', ['filter' => 'adminonly']);

    $routes->post('setting', 'Setting::kelolaSetting', ['filter' => 'adminonly']);

    $routes->get('berita/all/(:any)', 'Berita::getAll/$1', ['filter' => 'adminonly']);
    $routes->get('berita/get/(:any)', 'Berita::getById/$1', ['filter' => 'adminonly']);
    $routes->post('berita/save/(:any)', 'Berita::save/$1', ['filter' => 'adminonly']);
    $routes->post('berita/delete/(:any)', 'Berita::delete/$1', ['filter' => 'adminonly']);
    $routes->post('berita/uploadImage', 'Berita::imageUpload', ['filter' => 'adminonly']);

    $routes->get('dokumen/all', 'Dokumen::getAll', ['filter' => 'adminonly']);
    $routes->get('dokumen/get/(:any)', 'Dokumen::getById/$1', ['filter' => 'adminonly']);
    $routes->post('dokumen/save/(:any)', 'Dokumen::save/$1', ['filter' => 'adminonly']);
    $routes->post('dokumen/delete/(:any)', 'Dokumen::delete/$1', ['filter' => 'adminonly']);

    $routes->get('pegawai/get/(:num)', 'Pegawai::getById/$1', ['filter' => 'adminonly']);
    $routes->post('pegawai/save/(:segment)', 'Pegawai::save/$1', ['filter' => 'adminonly']);
    $routes->post('pegawai/delete/(:num)', 'Pegawai::delete/$1', ['filter' => 'adminonly']);
    $routes->get('pegawai/all', 'Pegawai::getAll', ['filter' => 'adminonly']);

    $routes->get('slider/get/(:any)', 'Slider::getById/$1', ['filter' => 'adminonly']);
    $routes->post('slider/save/(:any)', 'Slider::save/$1', ['filter' => 'adminonly']);
    $routes->post('slider/delete/(:any)', 'Slider::delete/$1', ['filter' => 'adminonly']);

    $routes->get('layanan/get/(:any)', 'Layanan::getById/$1', ['filter' => 'adminonly']);
    $routes->post('layanan/save/(:any)', 'Layanan::save/$1', ['filter' => 'adminonly']);
    $routes->post('layanan/delete/(:any)', 'Layanan::delete/$1', ['filter' => 'adminonly']);

    $routes->get('agen/get/(:any)', 'Agen::getById/$1', ['filter' => 'adminonly']);
    $routes->post('agen/save/(:any)', 'Agen::save/$1', ['filter' => 'adminonly']);
    $routes->post('agen/delete/(:any)', 'Agen::delete/$1', ['filter' => 'adminonly']);

    $routes->get('video/get/(:any)', 'Video::getById/$1', ['filter' => 'adminonly']);
    $routes->post('video/save/(:any)', 'Video::save/$1', ['filter' => 'adminonly']);
    $routes->post('video/delete/(:any)', 'Video::delete/$1', ['filter' => 'adminonly']);

});
