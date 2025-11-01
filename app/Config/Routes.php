<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//auth sso

$routes->get('/captcha', 'Utils::generateCaptcha');
$routes->get('/login/admin', 'Utils::login');
$routes->get('/logout', 'Utils::logout');

$routes->get('/dataBeritaKejagung', 'Publik::dataBeritaKejagung');
$routes->get('/getFeedIG', 'Publik::getFeedIG');
$routes->get('/getFeedYT', 'Publik::getFeedYT');

$routes->get('/', 'Publik::home');
$routes->get('/beranda', 'Publik::home');
$routes->get('/berita', 'Publik::berita');
$routes->get('/siaran-pers', 'Publik::siaran_pers');
$routes->get('/pengumuman', 'Publik::pengumuman');
$routes->get('/berita/detail/(:any)', 'Publik::beritaDetail/$1');
$routes->get('/detail-berita/(:any)', 'Publik::detail_berita/$1');
$routes->get('/page/(:any)', 'Publik::page/$1');
$routes->get('/dokumen', 'Publik::dokumen');
$routes->get('/video', 'Publik::video');
$routes->get('/jadwal-sidang-pidum', 'Publik::jadwalSidangPidum');
$routes->get('/cms-perkara-pidum', 'Publik::cmsPidum');

$routes->get('/admin', 'Admin::dashboard', ['filter' => 'authenticate']);
$routes->get('/admin/slideshow', 'Admin::slideshow', ['filter' => 'authenticate']);
$routes->get('/admin/layanan', 'Admin::layanan', ['filter' => 'authenticate']);
$routes->get('/admin/pages', 'Admin::pages', ['filter' => 'authenticate']);
$routes->get('/admin/grup-menu', 'Admin::grup_menu', ['filter' => 'authenticate']);
$routes->get('/admin/menu', 'Admin::menu', ['filter' => 'authenticate']);
$routes->get('/admin/setting', 'Admin::setting', ['filter' => 'authenticate']);
$routes->get('/admin/berita', 'Admin::berita', ['filter' => 'authenticate']);
$routes->get('/admin/pengumuman', 'Admin::pengumuman', ['filter' => 'authenticate']);
$routes->get('/admin/siaran_pers', 'Admin::siaran_pers', ['filter' => 'authenticate']);
$routes->get('/admin/video', 'Admin::video', ['filter' => 'authenticate']);
$routes->get('/admin/agen', 'Admin::agen', ['filter' => 'authenticate']);
$routes->get('/admin/dokumen', 'Admin::dokumen', ['filter' => 'authenticate']);
$routes->get('/admin/pegawai', 'Admin::pegawai', ['filter' => 'authenticate']);
$routes->get('/admin/pegawai/form/(:any)', 'Admin::pegawaiForm/$1', ['filter' => 'authenticate']);
$routes->get('logo/(:segment)', 'Publik::displayLogo/$1');

$routes->get('/admin/profil', 'Admin::profil', ['filter' => 'authenticate']);

// -------

$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('user/login', 'User::doLogin');
    $routes->post('user/oauth_check', 'User::oauth_check_user');
    $routes->post('user/register', 'User::doRegister');
    $routes->post('user/ubah_password', 'User::ubah_password', ['filter' => 'authenticate']);
    $routes->post('user/update_profil', 'User::update_profil', ['filter' => 'authenticate']);
    $routes->post('user/submit_permohonan', 'User::submit_pelayanan_hukum', ['filter' => 'authenticate']);
    $routes->get('user/getPermohonanByUser/', 'User::getPermohonanByUser', ['filter' => 'authenticate']);
    $routes->get('getKejari/(:any)', 'User::getKejari/$1');
    $routes->get('getProfil', 'User::getUserProfil', ['filter' => 'authenticate']);


    $routes->get('pages/all', 'Pages::getAll', ['filter' => 'authenticate']);
    $routes->get('pages/get/(:any)', 'Pages::getById/$1', ['filter' => 'authenticate']);
    $routes->post('pages/save/(:any)', 'Pages::save/$1', ['filter' => 'authenticate']);
    $routes->post('pages/delete/(:any)', 'Pages::delete/$1', ['filter' => 'authenticate']);
    $routes->post('pages/featured/(:any)', 'Pages::featured/$1', ['filter' => 'authenticate']);

    $routes->get('grup-menu/all', 'GrupMenu::getAll', ['filter' => 'authenticate']);
    $routes->get('grup-menu/get/(:any)', 'GrupMenu::getById/$1', ['filter' => 'authenticate']);
    $routes->post('grup-menu/save/(:any)', 'GrupMenu::save/$1', ['filter' => 'authenticate']);
    $routes->post('grup-menu/delete/(:any)', 'GrupMenu::delete/$1', ['filter' => 'authenticate']);
    $routes->post('grup-menu/change-order/(:any)/(:any)', 'GrupMenu::changeOrder/$1/$2', ['filter' => 'authenticate']);

    $routes->get('jadwal/all', 'Jadwal::getAll', ['filter' => 'authenticate']);
    $routes->get('jadwal/get/(:any)', 'Jadwal::getById/$1', ['filter' => 'authenticate']);
    $routes->post('jadwal/save/(:any)', 'Jadwal::save/$1', ['filter' => 'authenticate']);
    $routes->delete('jadwal/delete/(:any)', 'Jadwal::delete/$1', ['filter' => 'authenticate']);

    $routes->get('menu/all', 'Menu::getAll', ['filter' => 'authenticate']);
    $routes->get('menu/get/(:any)', 'Menu::getById/$1', ['filter' => 'authenticate']);
    $routes->post('menu/save/(:any)', 'Menu::save/$1', ['filter' => 'authenticate']);
    $routes->post('menu/delete/(:any)', 'Menu::delete/$1', ['filter' => 'authenticate']);
    $routes->post('menu/change-order/(:any)/(:any)', 'Menu::changeOrder/$1/$2', ['filter' => 'authenticate']);

    $routes->post('setting', 'Setting::kelolaSetting', ['filter' => 'authenticate']);

    $routes->get('berita/all/(:any)', 'Berita::getAll/$1', ['filter' => 'authenticate']);
    $routes->get('berita/get/(:any)', 'Berita::getById/$1', ['filter' => 'authenticate']);
    $routes->post('berita/save/(:any)', 'Berita::save/$1', ['filter' => 'authenticate']);
    $routes->post('berita/delete/(:any)', 'Berita::delete/$1', ['filter' => 'authenticate']);
    $routes->post('berita/uploadImage', 'Berita::imageUpload');

    $routes->get('dokumen/all', 'Dokumen::getAll', ['filter' => 'authenticate']);
    $routes->get('dokumen/get/(:any)', 'Dokumen::getById/$1', ['filter' => 'authenticate']);
    $routes->post('dokumen/save/(:any)', 'Dokumen::save/$1', ['filter' => 'authenticate']);
    $routes->post('dokumen/delete/(:any)', 'Dokumen::delete/$1', ['filter' => 'authenticate']);

    $routes->get('pegawai/all', 'Pegawai::getAll', ['filter' => 'authenticate']);

    $routes->get('slider/get/(:any)', 'Slider::getById/$1', ['filter' => 'authenticate']);
    $routes->post('slider/save/(:any)', 'Slider::save/$1', ['filter' => 'authenticate']);
    $routes->post('slider/delete/(:any)', 'Slider::delete/$1', ['filter' => 'authenticate']);

    $routes->get('layanan/get/(:any)', 'Layanan::getById/$1', ['filter' => 'authenticate']);
    $routes->post('layanan/save/(:any)', 'Layanan::save/$1', ['filter' => 'authenticate']);
    $routes->post('layanan/delete/(:any)', 'Layanan::delete/$1', ['filter' => 'authenticate']);

    $routes->get('agen/get/(:any)', 'Agen::getById/$1', ['filter' => 'authenticate']);
    $routes->post('agen/save/(:any)', 'Agen::save/$1', ['filter' => 'authenticate']);
    $routes->post('agen/delete/(:any)', 'Agen::delete/$1', ['filter' => 'authenticate']);

    $routes->get('video/get/(:any)', 'Video::getById/$1', ['filter' => 'authenticate']);
    $routes->post('video/save/(:any)', 'Video::save/$1', ['filter' => 'authenticate']);
    $routes->post('video/delete/(:any)', 'Video::delete/$1', ['filter' => 'authenticate']);

});