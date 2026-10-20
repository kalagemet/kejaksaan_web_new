<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminOnly implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session('is_login') && session('user_id') ? (new UserModel())->find(session('user_id')) : null;
        if (!$user || (string) $user['status'] !== '1' || $user['role'] !== 'admin') {
            if (strpos($request->getUri()->getPath(), 'api/') === false) {
                return redirect()->to(site_url());
            }
            return service('response')->setStatusCode(403)->setJSON(['message' => 'Akses administrator diperlukan.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
