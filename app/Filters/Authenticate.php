<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Authenticate implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session('is_login') && session('user_id') ? (new \App\Models\UserModel())->find(session('user_id')) : null;
        if (!$user || (string) $user['status'] !== '1') {
            if (strpos($request->getUri()->getPath(), 'api/') !== false) {
                return service('response')->setStatusCode(401)->setJSON(['message' => 'Silakan login kembali.']);
            }
            return redirect()->to(site_url());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
