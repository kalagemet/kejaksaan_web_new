<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use RuntimeException;

class GoogleOAuth
{
    private ClientInterface $client;

    public function __construct(?ClientInterface $client = null)
    {
        $this->client = $client ?? new Client(['timeout' => 15, 'connect_timeout' => 5]);
    }

    public function authorizationUrl(string $redirectUri): string
    {
        $state = bin2hex(random_bytes(32));
        session()->set('google_oauth', ['state' => $state, 'created' => time()]);
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => getenv('GOOGLE_CLIENT_ID'),
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
        ]);
    }

    public function identity(string $code, string $state, string $redirectUri): array
    {
        $pending = session()->get('google_oauth');
        session()->remove('google_oauth');
        if (!$code || !$state || !is_array($pending)
            || time() - $pending['created'] > 600 || !hash_equals($pending['state'], $state)) {
            throw new RuntimeException('Permintaan OAuth tidak valid atau sudah kedaluwarsa.');
        }
        if (!getenv('GOOGLE_CLIENT_ID') || !getenv('GOOGLE_CLIENT_SECRET')) {
            throw new RuntimeException('Google OAuth belum dikonfigurasi.');
        }
        // Exchange a one-use code on the server; never accept browser-supplied identity fields.
        $tokens = json_decode((string) $this->client->request('POST', 'https://oauth2.googleapis.com/token', [
            'form_params' => [
                'code' => $code,
                'client_id' => getenv('GOOGLE_CLIENT_ID'),
                'client_secret' => getenv('GOOGLE_CLIENT_SECRET'),
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ],
        ])->getBody(), true);
        if (empty($tokens['access_token'])) {
            throw new RuntimeException('Google tidak memberikan token akses.');
        }
        $profile = json_decode((string) $this->client->request('GET', 'https://openidconnect.googleapis.com/v1/userinfo', [
            'headers' => ['Authorization' => 'Bearer ' . $tokens['access_token']],
        ])->getBody(), true);
        if (empty($profile['sub']) || empty($profile['email'])
            || !filter_var($profile['email'], FILTER_VALIDATE_EMAIL)
            || ($profile['email_verified'] ?? false) !== true) {
            throw new RuntimeException('Identitas Google belum terverifikasi.');
        }
        return $profile;
    }
}
