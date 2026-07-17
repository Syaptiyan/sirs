<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CSRFMiddleware implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $method = $request->getMethod();

        if (in_array($method, ['post', 'put', 'patch', 'delete'])) {
            $token = $request->getHeaderLine('X-CSRF-TOKEN')
                ?? $request->getPost('_token');

            $sessionToken = session()->get('_csrf_token');

            if (!$token || !$sessionToken || $token !== $sessionToken) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON([
                        'success' => false,
                        'message' => 'CSRF token mismatch',
                    ]);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Generate new token for next request
        $token = bin2hex(random_bytes(32));
        session()->set('_csrf_token', $token);
    }
}
