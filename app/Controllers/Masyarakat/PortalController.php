<?php
namespace App\Controllers\Masyarakat;

use CodeIgniter\Controller;

abstract class PortalController extends Controller
{
    protected $helpers = ['form', 'url', 'masyarakat'];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $db = \Config\Database::connect();
        $setting = $db->tableExists('dt_setting') ? (new \App\Models\SettingModel())->find(1) : null;
        service('renderer')->setVar('setting', $setting);
        $response->setHeader('Cache-Control', 'private, no-store');
        $response->setHeader('X-Content-Type-Options', 'nosniff');
    }

    protected function input(string $name): string
    {
        $value = $this->request->getPost($name);
        return is_string($value) ? trim($value) : '';
    }

    protected function rawPassword(string $name = 'password'): string
    {
        $value = $this->request->getPost($name);
        return is_string($value) ? $value : '';
    }

    protected function failForm(string $path, string $message, array $values = [])
    {
        foreach (['password', 'password_baru', 'konfirmasi_password', 'otp', 'password_saat_ini'] as $key) { unset($values[$key]); }
        return redirect()->to(site_url($path))->with('error', $message)->with('form_values', $values);
    }

    protected function account(): array
    {
        return service('masyarakatAccounts')->active((int) session('masyarakat_id'));
    }

    protected function limit(string $action, int $capacity = 5, int $seconds = 900): void
    {
        $key = 'masyarakat-' . $action . '-' . hash('sha256', $this->request->getIPAddress());
        if (!service('throttler')->check($key, $capacity, $seconds)) {
            throw new \DomainException('Terlalu banyak permintaan. Silakan coba kembali beberapa saat lagi.');
        }
    }
}
