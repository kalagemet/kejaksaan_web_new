<?php
namespace App\Filters;

use App\Models\MasyarakatModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MasyarakatOnly implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session('masyarakat_id') ? (new MasyarakatModel())->find(session('masyarakat_id')) : null;
        if (!$user || !(int) $user['aktif'] || (int) $user['session_version'] !== (int) session('masyarakat_version')) {
            session()->remove(['masyarakat_id', 'masyarakat_version']);
            return redirect()->to(site_url('masyarakat/login'))->with('error', 'Anda harus login sebagai masyarakat untuk mengakses halaman ini.');
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) { }
}
