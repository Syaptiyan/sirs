<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ThrottleMiddleware implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $ip = $request->getIPAddress();
        $cache = cache();
        $key = 'throttle_' . $ip;
        $maxAttempts = $arguments[0] ?? 5;
        $decaySeconds = $arguments[1] ?? 900; // 15 minutes

        $attempts = $cache->get($key) ?? 0;

        if ($attempts >= $maxAttempts) {
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(429)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Too many requests. Please try again later.',
                    ]);
            }

            return redirect()->back()
                ->with('error', 'Terlalu banyak percobaan. Silakan coba lagi nanti.');
        }

        $cache->save($key, $attempts + 1, $decaySeconds);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do
    }
}
