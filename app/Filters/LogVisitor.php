<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\VisitModel;

class LogVisitor implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $visitModel = new VisitModel();

        $ipAddress = $request->getIPAddress();
        $userAgent = $request->getUserAgent()->__toString();
        $currentDate = date('Y-m-d');

        $visitModel->insert([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'visited_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
