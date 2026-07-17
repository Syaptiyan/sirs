<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\SecurityConfig;

class SecurityHeadersMiddleware implements FilterInterface
{
    private SecurityConfig $config;

    public function __construct()
    {
        $this->config = config('SecurityConfig');
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        // Do nothing before controller execution
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('X-Frame-Options', $this->config->xFrameOptions);
        $response->setHeader('X-Content-Type-Options', $this->config->xContentTypeOptions);
        $response->setHeader('X-XSS-Protection', $this->config->xXssProtection);
        $response->setHeader('Referrer-Policy', $this->config->referrerPolicy);
        $response->setHeader('Permissions-Policy', $this->config->permissionsPolicy);

        $hstsValue = 'max-age=' . $this->config->hstsMaxAge;
        if ($this->config->hstsIncludeSubdomains) {
            $hstsValue .= '; includeSubDomains';
        }
        if ($this->config->hstsPreload) {
            $hstsValue .= '; preload';
        }
        $response->setHeader('Strict-Transport-Security', $hstsValue);

        $response->setHeader('Content-Security-Policy', $this->config->contentSecurityPolicy);

        $response->setHeader('X-Permitted-Cross-Domain-Policies', 'none');
        $response->setHeader('Cross-Origin-Embedder-Policy', 'require-corp');
        $response->setHeader('Cross-Origin-Opener-Policy', 'same-origin');
        $response->setHeader('Cross-Origin-Resource-Policy', 'same-origin');
    }
}
