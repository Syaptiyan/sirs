<?php

namespace App\Filters;

use App\Services\Auth\AuthService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RBACMiddleware implements FilterInterface
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized',
                ]);
        }

        if ($arguments) {
            foreach ($arguments as $permission) {
                if (!$this->authService->hasPermission($userId, $permission)) {
                    if ($request->isAJAX()) {
                        return service('response')
                            ->setStatusCode(403)
                            ->setJSON([
                                'success' => false,
                                'message' => 'Forbidden: Insufficient permissions',
                            ]);
                    }

                    return redirect()->back()
                        ->with('error', 'Anda tidak memiliki akses untuk halaman ini.');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do
    }
}
