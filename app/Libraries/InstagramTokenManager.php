<?php

namespace App\Libraries;

use Config\Services;
use Exception;

/**
 * Class InstagramTokenManager
 * Mengelola Instagram Long-Lived Access Token untuk CodeIgniter 4.
 */
class InstagramTokenManager
{
    /**
     * @var string Path ke file untuk menyimpan data token (JSON).
     */
    private string $tokenFilePath;

    /**
     * @var array Data token yang dimuat dari file.
     */
    private array $tokenData = [];

    public function __construct()
    {
        $tokenFileName = 'instagram_token.json';
        // Mengambil path dari direktori tempat file library ini berada (__DIR__)
        $libraryDirectory = __DIR__;
        $this->tokenFilePath = $libraryDirectory . DIRECTORY_SEPARATOR . $tokenFileName;

        // Periksa apakah direktori library dapat ditulis oleh server
        if (!is_writable($libraryDirectory)) {
            throw new Exception("Direktori library ('" . $libraryDirectory . "') tidak dapat ditulis. Harap periksa izin folder.");
        }

        // Buat file jika belum ada
        if (!file_exists($this->tokenFilePath)) {
            $defaultTokenData = [
                'access_token' => 'IGAAUyXCMmbeNBZAFF5SGNfNWRZAdkVCOHJzSDF3REhOZA0sxTkF4S3JodWpyOXlXM3FFUk44aTlTN3p5Vmp5X0xhN0RmSHF1U0RJZAk5NaVZAUODNzblhoQlp2d2VXNzJOczBhbDZAKbW81aXZAuckNWdUxySUl3',
                'expires_at' => 1768016140
            ];
            file_put_contents(
                $this->tokenFilePath,
                json_encode($defaultTokenData, JSON_PRETTY_PRINT)
            );
        }

        $this->loadTokenFromFile();
    }

    /**
     * Memuat data token dari file JSON.
     */
    private function loadTokenFromFile(): void
    {
        $content = file_get_contents($this->tokenFilePath);
        $this->tokenData = json_decode($content, true) ?: [];
    }

    /**
     * Menyimpan data token ke file JSON.
     *
     * @param string $accessToken Token baru.
     * @param int $expiresIn Durasi masa aktif token dalam detik.
     */
    private function saveTokenToFile(string $accessToken, int $expiresIn): void
    {
        // Hitung timestamp kapan token akan kedaluwarsa
        $expiresAt = time() + $expiresIn;

        $data = [
            'access_token' => $accessToken,
            'expires_at' => $expiresAt, // Simpan sebagai timestamp UNIX
        ];

        // Simpan ke file dengan format JSON yang rapi
        file_put_contents($this->tokenFilePath, json_encode($data, JSON_PRETTY_PRINT));

        // Muat ulang data token ke properti class
        $this->tokenData = $data;
    }

    /**
     * Memperpanjang (refresh) Long-Lived Access Token menggunakan API Instagram.
     *
     * @return string|null Token baru jika berhasil, null jika gagal.
     */
    private function refreshToken(): ?string
    {
        $currentToken = $this->tokenData['access_token'] ?? null;
        if (!$currentToken) {
            return null;
        }

        // Gunakan HTTP Client bawaan CodeIgniter 4
        $client = Services::curlrequest();

        $endpoint = "https://graph.instagram.com/refresh_access_token";
        $params = [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $currentToken,
        ];

        try {
            $response = $client->request('GET', $endpoint, ['query' => $params]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);

                if (isset($data['access_token'], $data['expires_in'])) {
                    // Simpan token baru yang berhasil didapatkan
                    $this->saveTokenToFile($data['access_token'], $data['expires_in']);
                    return $data['access_token'];
                }
            }
        } catch (Exception $e) {
            // Tangani error koneksi atau response, Anda bisa menambahkan logging di sini
            log_message('error', 'Gagal refresh token Instagram: ' . $e->getMessage());
            return null;
        }

        // Gagal refresh token
        return null;
    }

    /**
     * Metode utama untuk mendapatkan token yang valid.
     * Metode ini akan memeriksa masa aktif dan memperpanjangnya jika perlu.
     *
     * @param int $daysBeforeExpiry Jumlah hari sebelum kedaluwarsa untuk memicu refresh. Default 7 hari.
     * @return string|null Access token yang valid.
     * @throws Exception
     */
    public function getValidToken(int $daysBeforeExpiry = 7): ?string
    {
        if (empty($this->tokenData['access_token']) || empty($this->tokenData['expires_at'])) {
            // Token awal belum ada, developer harus mengisinya manual pertama kali.
            throw new Exception("File token ('" . basename($this->tokenFilePath) . "') kosong atau tidak valid. Harap isi dengan Long-Lived Token pertama Anda secara manual.");
        }

        $expiryTimestamp = $this->tokenData['expires_at'];
        $threshold = $daysBeforeExpiry * 24 * 60 * 60; // Konversi hari ke detik

        // Periksa apakah token akan kedaluwarsa dalam $daysBeforeExpiry hari ke depan
        if ($expiryTimestamp < (time() + $threshold)) {
            // Token akan segera kedaluwarsa. Lakukan refresh.
            return $this->refreshToken();
        }

        // Token masih valid.
        return $this->tokenData['access_token'];
    }
}