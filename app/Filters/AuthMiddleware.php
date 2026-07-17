<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthMiddleware implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Unauthorized',
                    ]);
            }

            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check session timeout (30 minutes)
        $lastActivity = $session->get('last_activity');
        if ($lastActivity && (time() - $lastActivity) > 1800) {
            $session->destroy();
            return redirect()->to('/login')
                ->with('error', 'Session expired. Silakan login kembali.');
        }

        $session->set('last_activity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do
    }
}
