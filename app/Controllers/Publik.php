<?php

namespace App\Controllers;

use App\Models\AgenModel;
use App\Models\SlideshowModel;
use App\Models\LayananModel;
use App\Models\BeritaModel;
use App\Models\MenuModel;
use App\Models\VisitModel;
use App\Models\PagesModel;
use App\Models\SettingModel;
use App\Models\PegawaiModel;
use App\Models\VideoModel;
use App\Models\DokumenModel;
use App\Libraries\InstagramTokenManager;
use CodeIgniter\I18n\Time;
use GuzzleHttp\Client;
use DiDom\Document;
use CodeIgniter\Exceptions\PageNotFoundException;
use function PHPUnit\Framework\returnArgument;


class Publik extends BaseController
{
    private $models;

    public function __construct()
    {
        $this->models = [
            'slideshow' => new SlideshowModel(),
            'layanan' => new LayananModel(),
            'berita' => new BeritaModel(),
            'dokumen' => new DokumenModel(),
            'menu' => new MenuModel(),
            'visit' => new VisitModel(),
            'pages' => new PagesModel(),
            'pegawai' => new PegawaiModel(),
            'setting' => new SettingModel(),
            'video' => new VideoModel(),
            'agen' => new AgenModel(),
        ];
        $this->db = \Config\Database::connect();
    }

    public function home()
    {
        $data = $this->getDefaultData([
            'slider' => $this->models['slideshow']->findAll(),
            'layanan' => $this->models['layanan']->orderBy('created_at', 'DESC')->findAll(6),
            'berita' => $this->models['berita']->where('jenis', 'berita')->orderBy('created_at', 'DESC')->findAll(4),
            'video' => $this->models['video']->orderBy('created_at', 'DESC')->findAll(3),
            'dokumen' => $this->models['dokumen']->orderBy('created_at', 'DESC')->findAll(3),
            'agen' => $this->models['agen']->orderBy('created_at', 'ASC')->findAll(),
            'page' => $this->models['pages']->findFeatured(),
            'berita_2' => $this->models['berita']->where('jenis', 'berita')->orderBy('created_at', 'DESC')->findAll(6, 4),
            'pengumuman' => $this->models['berita']->where('jenis', 'pengumuman')->orderBy('created_at', 'DESC')->findAll(4),
            'siaran_pers' => $this->models['berita']->where('jenis', 'siaran_pers')->orderBy('created_at', 'DESC')->findAll(4)
        ]);
        return view('index', $data);
    }

    private function getDefaultData(array $additionalData = [])
    {
        return array_merge($this->dataSetting, $additionalData, [
            'visitor' => $this->getCountVisitor(),
            'navbar' => $this->getNavbar(),
            'menu' => $this->models['menu']->getMenuGroupingWithGrup(),
        ]);
    }

    public function getFeedIG()
    {
        try {
            $cacheKey = 'instagram_feed_data';
            $jsonData = cache($cacheKey); // Coba ambil dari cache dulu
            if (!$jsonData) {
                $tokenManager = new InstagramTokenManager();
                $token = $tokenManager->getValidToken();
                if (!$token) {
                    log_message('error', 'Tidak dapat mengambil token Instagram yang valid.');
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Token Instagram tidak valid',
                        'data' => ""
                    ]);
                }
                $client = new Client();
                $apiUrl = "https://graph.instagram.com/me/media";
                $response = $client->request('GET', $apiUrl, [
                    'query' => [
                        'fields' => 'media_url,media_type,permalink',
                        'access_token' => $token,
                        'limit' => 5
                    ]
                ]);
                if ($response->getStatusCode() !== 200) {
                    log_message('error', 'Gagal mengambil data dari Instagram API. Status: ' . $response->getStatusCode());
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $response->getStatusCode() . 'Gagal mengambil Data Instagram.',
                        'data' => ""
                    ]);
                }
                $jsonData = $response->getBody()->getContents();
                cache()->save($cacheKey, $jsonData, 1200); // Simpan 20 menit
                $data = json_decode($jsonData, true);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data berhasil diambil (API)',
                    'data' => $data['data']
                ]);
            }
            $data = json_decode($jsonData, true);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil diambil (Cache)',
                'data' => $data['data']
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Kesalahan Instagram Feed: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Kesalahan Instagram Feed: ' . $e->getMessage(),
                'data' => ""
            ]);
        }
    }

    public function dataBeritaKejagung()
    {
        $url = "https://kejaksaan.go.id/conference/news";

        try {
            // Membuat HTTP client
            $client = new Client();

            // Kirim permintaan GET ke URL
            $response = $client->request('GET', $url);

            if ($response->getStatusCode() === 200) {
                $htmlContent = $response->getBody()->getContents();

                // Parse HTML menggunakan DiDOM
                $document = new Document($htmlContent);

                // Temukan artikel-artikel berdasarkan elemen yang sesuai (misalnya <article> atau class 'post')
                $articles = $document->find('article.post');

                $schedule = [];
                $topArticles = array_slice($articles, 0, 5); // Ambil 5 artikel teratas

                foreach ($topArticles as $article) {
                    // Ambil judul, link, deskripsi dari artikel
                    $titleElement = $article->find('h2 a');
                    $link = $titleElement[0]->getAttribute('href');
                    $title = trim($titleElement[0]->text());

                    // Ambil deskripsi
                    $description = trim($article->find('p.mb-0')[0]->text());

                    // Ambil tanggal (mengambil elemen <span> dengan class atau struktur yang sesuai)
                    $dateElement = $article->find('.post-meta span');
                    $date = null;
                    foreach ($dateElement as $span) {
                        if (strpos($span->text(), '-') !== false) {
                            $date = trim($span->text());
                            break;
                        }
                    }

                    // Tambahkan data artikel ke dalam array
                    $schedule[] = [
                        'title' => $title,
                        'link' => $link,
                        'description' => $description,
                        'date' => $date,
                    ];
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data berhasil diambil.',
                    'data' => $schedule
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mendapatkan data dari server.',
                    'data' => []
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    private function getRssItems($url)
    {
        // Inisialisasi cURL
        $ch = curl_init();

        // Set opsi cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout jika perlu
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Untuk mengikuti redirect jika ada

        // Eksekusi cURL dan simpan hasilnya
        $rssContent = curl_exec($ch);

        // Cek jika cURL gagal
        if ($rssContent === false) {
            curl_close($ch);
            return [];
        }

        // Tutup cURL setelah selesai
        curl_close($ch);

        // Proses XML
        $xml = simplexml_load_string($rssContent);
        if ($xml === false) {
            return [];
        }

        // Ambil data item RSS
        $rssItems = [];
        foreach ($xml->channel->item as $item) {
            $rssItems[] = [
                'title' => (string) $item->title,
                'link' => (string) $item->link,
                'description' => (string) $item->description,
                'pubDate' => (string) $item->pubDate,
            ];
        }

        // Kembalikan hanya 5 item pertama
        return array_slice($rssItems, 0, 5);
    }


    public function dynamicView($viewName, $additionalData = [])
    {
        $data = $this->getDefaultData($additionalData);
        return view("publik/$viewName", $data);
    }

    // public function beranda()
    // {
    //     return $this->dynamicView('beranda', [
    //         'slider' => $this->models['slideshow']->findAll(),
    //         'layanan' => $this->models['layanan']->orderBy('created_at', 'DESC')->findAll(6),
    //         'berita' => $this->models['berita']->orderBy('created_at', 'DESC')->findAll(4),
    //     ]);
    // }
    public function pegawai($jenis)
    {
        $pegawaiTypes = [
            'hakim' => 'Hakim',
            'ppnpn' => 'Pegawai Pemerintah Non Pegawai Negeri',
            'panitera' => 'Kepaniteraan',
            'sekretariat' => 'Kesekretariatan',
        ];

        if (!isset($pegawaiTypes[$jenis])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $this->dynamicView('pegawai', [
            'label' => $pegawaiTypes[$jenis],
            'pegawai' => $this->models['pegawai']->getPegawaiByJenis($jenis),
        ]);
    }

    public function berita()
    {
        return $this->dynamicView('berita', [
            'berita' => $this->models['berita']->where('jenis', 'berita')->orderBy('created_at', 'DESC')->paginate(9),
            'pager' => $this->models['berita']->pager,
        ]);
    }

    public function siaran_pers()
    {
        return $this->dynamicView('siaran_pers', [
            'berita' => $this->models['berita']->where('jenis', 'siaran_pers')->orderBy('created_at', 'DESC')->paginate(9),
            'pager' => $this->models['berita']->pager,
        ]);
    }

    public function pengumuman()
    {
        return $this->dynamicView('pengumuman', [
            'berita' => $this->models['berita']->where('jenis', 'pengumuman')->orderBy('created_at', 'DESC')->paginate(9),
            'pager' => $this->models['berita']->pager,
        ]);
    }

    public function video()
    {
        $currentPage = (int) ($this->request->getGet('page') ?? 1);
        return $this->dynamicView('video', [
            'currentPage' => $currentPage
        ]);
    }

    public function getDataVideo()
    {
        $response = $this->getDataFeedYT(true);
        $allVideos = $response['data'];
        $totalVideos = count($allVideos);
        $perPage = 6;
        $currentPage = (int) ($this->request->getGet('page') ?? 1);
        ;
        $offset = ($currentPage - 1) * $perPage;
        $videosForPage = array_slice($allVideos, $offset, $perPage);
        $pager = service('pager');
        $pager->makeLinks(
            $currentPage,
            $perPage,
            $totalVideos,
            'default_full', // Template yang digunakan (pastikan ini ada)
            0,              // Segmen URI 0 berarti menggunakan Query String
            'default'      // Nama Grup (misalnya 'videos'). Ini penting!
        );
        return $this->response->setJSON([
            'video' => $videosForPage,
            'pager' => $pager->links('default', 'custom_pager')
        ]);
    }

    public function getFeedYT()
    {
        $maxResult = $this->request->getGet('limit') ?? 3;
        $result = $this->getDataFeedYT(false, $maxResult);
        return $this->response->setJSON($result);
    }
    private function getDataFeedYT(bool $getall = false, int $maxResults = 3)
    {
        $maxResult = $maxResults;
        $hasilArray = $getall ? $this->models['video']->orderBy('created_at', 'DESC')->findAll() : $this->models['video']->orderBy('created_at', 'DESC')->findAll($maxResult);
        try {
            $token = getenv("widget.youtube.token");
            $id = getenv("widget.youtube.id");
            $apiUrl = "https://www.googleapis.com/youtube/v3/search?key=$token&channelId=$id&part=snippet&order=date";
            // --- IMPLEMENTASI CACHE ---
            $cacheKey = 'youtube_feed_data';
            $jsonData = cache($cacheKey); // Coba ambil dari cache dulu
            $msg = '';
            if (!$jsonData) {
                log_message('info', 'Memanggil YouTube API: Cache tidak ditemukan.');
                $msg = 'Memanggil YouTube API: Cache tidak ditemukan';
                $client = new Client();
                try {
                    $response = $client->request('GET', $apiUrl);
                    $jsonData = $response->getBody()->getContents();
                    // Simpan ke cache HANYA jika sukses
                    cache()->save($cacheKey, $jsonData, 21600); // Simpan 6 jam
                } catch (\Throwable $e) {
                    return [
                        'success' => false,
                        'message' => 'Gagal mengambil Youtube Feed' . $e->getMessage(),
                        'data' => $hasilArray
                    ];
                }
            } else
                $msg = "Cache ditemukan";
            $data = json_decode($jsonData, true);
            // $idCounter = 1;
            // $hasilArray = []; 
            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    //     $videoId = $item['id']['videoId'];
                    //     $title = $item['snippet']['title'];
                    //     $desc = $item['snippet']['description'];
                    //     $thumbnail = $item['snippet']['thumbnails']['high']['url'];
                    //     $createdAt = $item['snippet']['publishedAt'];
                    //     $hasilArray[] = [
                    //         'id' => (string) $idCounter,
                    //         'link' => $videoId,
                    //         'title' => $title,
                    //         'thumbnail' => $thumbnail,
                    //         'desc' => $desc,
                    //         'created_at' => $createdAt
                    //     ];
                    //     $idCounter++;
                    if (isset($item['id']['kind']) && $item['id']['kind'] === 'youtube#video') {
                        $hasilArray[] = [
                            'id' => null,
                            'link' => $item['id']['videoId'],
                            'created_at' => $item['snippet']['publishedAt'],
                            'updated_at' => ''
                        ];
                    }
                }
            }
            usort($hasilArray, function ($a, $b) {
                return strtotime($b['created_at']) <=> strtotime($a['created_at']);
            });
            return [
                'success' => true,
                'message' => $msg,
                'data' => $getall ? $hasilArray : array_slice($hasilArray, 0, $maxResult)
            ];
        } catch (\Throwable $e) {
            log_message('error', 'Kesalahan Youtube Feed: ' . $e->getMessage());
            return [
                'success' => true,
                'message' => 'Kesalahan Youtube Feed: ' . $e->getMessage(),
                'data' => $hasilArray
            ];
        }
    }

    public function dokumen()
    {
        return $this->dynamicView('dokumen', [
            'dokumen' => $this->models['dokumen']->paginate(9),
            'pager' => $this->models['dokumen']->pager,
        ]);
    }

    public function beritaDetail($slug)
    {
        // Cari berita berdasarkan slug
        $berita = $this->models['berita']->findBySlug($slug);
        $bacajuga = $this->models['berita']->select('slug, judul')->findLikeSlug($slug);

        // Jika berita tidak ditemukan, lempar ke halaman 404
        if (!$berita) {
            throw PageNotFoundException::forPageNotFound("Berita dengan slug '{$slug}' tidak ditemukan.");
        }
        $timestamp = formatDate($berita['created_at']);

        // Jika berita ditemukan, tampilkan view
        return $this->dynamicView('beritaDetailNew', [
            'bacajuga' => $bacajuga,
            'berita' => $berita,
            'timestamp' => $timestamp,
            'agen' => $this->models['agen']->orderBy('created_at', 'ASC')->findAll(),
            'beritalain' => $this->models['berita']->where('jenis', 'berita')->orderBy('created_at', 'DESC')->findAll(3)
        ]);
    }

    public function jadwalSidangPidum()
    {
        $tanggal = Time::now();
        $nomorHari = (int) $tanggal->format("N");
        if ($nomorHari > 5) {
            $tanggal = $tanggal->modify('next monday');
        }
        return $this->dynamicView('jadwalsidangpidum', [
            'date' => $tanggal->format('d/m/Y'),
            'hari' => getHari($tanggal) . " " . formatDate($tanggal)
        ]);
    }

    public function getDataJadwalSidangPidum()
    {
        if ($this->request->getMethod() === 'options') {
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', base_url())
                ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Content-Type')
                ->setStatusCode(204);
        }
        $this->response->setHeader('Access-Control-Allow-Origin', base_url());
        $param = $this->request->getGet('_') ?? date('d/m/Y');
        $cacheKey = 'jadwal_sidang_pidum_' . md5($param);
        if ($cachedResponse = cache($cacheKey)) {
            log_message('info', 'Mengambil Jadwal Sidang dari CACHE.');
            $cachedResponse['source'] = 'cache';
            return $this->response->setJSON($cachedResponse);
        }
        log_message('info', 'Cache Jadwal Sidang tidak ada. Memanggil API.');
        try {
            $targetApiUrl = 'https://sipp.pn-banjarnegara.go.id/list_jadwal_sidang/search/1/' . $param;
            $client = new Client();
            $apiResponse = $client->request('GET', $targetApiUrl, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 ... (dll)',
                    'Accept' => 'application/json, text/plain, */*',
                    'Connection' => 'keep-alive',
                ],
                'verify' => false,
                'timeout' => 10,
            ]);
            $body = $apiResponse->getBody()->getContents();
            $finalData = null; // Ini akan jadi 'data' di JSON kita
            try {
                $document = new Document($body);
                $tableElement = $document->first('#tablePerkaraAll');
                $tableElement ? $finalData = $tableElement->html() : $finalData = '<p class="text-danger">Error: Tabel (id=tablePerkaraAll) tidak dapat ditemukan pada server SIPP.</p>';
            } catch (\Throwable $e) {
                log_message('error', 'DiDom Selector Error: ' . $e->getMessage());
                $finalData = '<p class="text-danger">Error parsing HTML: ' . $e->getMessage() . '</p>';
            }
            $successResponse = [
                'success' => true,
                'status_from_api' => $apiResponse->getStatusCode(),
                'data' => $finalData,
                'message' => 'Data berhasil diambil dan diparsing'
            ];
            cache()->save($cacheKey, $successResponse, 10800);
            $successResponse['source'] = 'api';
            return $this->response->setJSON($successResponse);
        } catch (\Throwable $e) {
            log_message('error', 'Guzzle API Proxy Error (SIPP): ' . $e->getMessage());
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'error' => 'Gagal koneksi ke server SIPP',
                    'details' => $e->getMessage(),
                    'source' => 'api_error'
                ]);
        }
    }

    public function cmsPidum()
    {
        $tanggal = Time::now();
        $nomorHari = (int) $tanggal->format("N");
        if ($nomorHari > 5) {
            $tanggal = $tanggal->modify('next monday');
        }
        return $this->dynamicView('cmspidum', [
            'date' => $tanggal->format('d/m/Y'),
            'hari' => getHari($tanggal) . " " . formatDate($tanggal)
        ]);
    }

    public function getDataCmsPidum()
    {
        if ($this->request->getMethod() === 'options') {
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', base_url())
                ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Content-Type')
                ->setStatusCode(204);
        }
        $this->response->setHeader('Access-Control-Allow-Origin', base_url());
        $cacheKey = 'cms_perkara_pidum';
        $cachedResponse = cache($cacheKey);
        if ($cachedResponse) {
            log_message('info', 'Mengambil data Pidum dari CACHE.');
            $cachedResponse['source'] = 'cache';
            return $this->response->setJSON($cachedResponse);
        }
        try {
            $param = $this->request->getGet('_') ?? '';
            $tahun = $this->request->getGet('tahun') ?? date('Y');
            $satker = $this->request->getGet('satker') ?? '11.27.00';
            $client = new Client();
            $targetApiUrl = 'https://cms-publik.kejaksaan.go.id/api/pidum/filtered';
            $apiResponse = $client->request('GET', $targetApiUrl, [
                'query' => [
                    'tahun' => $tahun,
                    'satker' => $satker,
                    '_' => $param,
                ],
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 ... (dll)',
                    'Accept' => 'application/json, text/plain, */*',
                ],
                'verify' => false,
                'timeout' => 10,
            ]);
            $body = $apiResponse->getBody()->getContents();
            $dataFromApi = json_decode($body, true);
            $successResponse = [
                'success' => true,
                'status_from_api' => $apiResponse->getStatusCode(),
                'data' => $dataFromApi['data'] ?? $dataFromApi ?? $body,
                'message' => $dataFromApi['message'] ?? 'Gagal Memproses APi'
            ];
            cache()->save($cacheKey, $successResponse, 21600); //6 Jam
            $successResponse['source'] = 'api';
            return $this->response->setJSON($successResponse);
        } catch (\Throwable $e) {
            log_message('error', 'Guzzle API Proxy Error: ' . $e->getMessage());
            return $this->response
                ->setStatusCode(500) // Set status error di server kita
                ->setJSON([
                    'success' => false,
                    'message' => 'Gagal koneksi ke server pusat',
                    'details' => $e->getMessage()
                ]);
        }
    }

    public function page($id)
    {
        // Cari halaman berdasarkan ID atau slug
        $page = $this->models['pages']->findBySlug($id);

        // Jika halaman tidak ditemukan, lempar ke halaman 404
        if (!$page) {
            throw PageNotFoundException::forPageNotFound("Halaman dengan ID '{$id}' tidak ditemukan.");
        }

        // Jika halaman ditemukan, tampilkan view
        return $this->dynamicView('page', [
            'page' => $page,
        ]);
    }

    public function login()
    {
        return view('login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function validation_test()
    {
        return view('validationtest');
    }

    private function getNavbar()
    {
        $query = $this->db->query("SHOW COLUMNS FROM dt_grup_menu WHERE Field = 'navbar'");
        $row = $query->getRow();

        if ($row && isset($row->Type)) {
            preg_match("/^enum\((.*)\)$/", $row->Type, $matches);
            if (isset($matches[1])) {
                return str_getcsv($matches[1], ',', "'");
            }
        }
        return [];
    }

    private function getCountVisitor()
    {
        $this->db->query("SET sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

        $builder = $this->db->table('dt_visit');

        return [
            'today' => $this->countVisitors($builder, 'DATE(visited_at) = CURDATE()'),
            'yesterday' => $this->countVisitors($builder, 'DATE(visited_at) = CURDATE() - INTERVAL 1 DAY'),
            'this_week' => $this->countVisitors($builder, 'YEARWEEK(visited_at, 1) = YEARWEEK(CURDATE(), 1)'),
            'this_month' => $this->countVisitors($builder, 'MONTH(visited_at) = MONTH(CURDATE()) AND YEAR(visited_at) = YEAR(CURDATE())'),
            'total' => $this->countVisitors($builder),
            'active_users' => $this->countVisitors($builder, 'visited_at >= NOW() - INTERVAL 30 MINUTE'),
        ];
    }

    private function countVisitors($builder, $condition = null)
    {
        if ($condition) {
            $builder->where($condition);
        }
        return $builder->groupBy('ip_address, user_agent')->countAllResults();
    }

    public function displayLogo($filename)
    {
        // Lokasi folder tempat file disimpan
        $filePath = WRITEPATH . 'uploads/' . $filename;

        // Cek apakah file ada
        if (!is_file($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File not found.');
        }

        // Cek apakah ekstensi Fileinfo tersedia
        if (!extension_loaded('fileinfo')) {
            return $this->response->setStatusCode(500)->setBody('Fileinfo extension is not enabled.');
        }

        // Dapatkan tipe MIME file menggunakan finfo_file
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // Mendapatkan tipe MIME
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo); // Menutup resource finfo

        // Tampilkan file dengan MIME yang sesuai
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setBody(file_get_contents($filePath));
    }
}