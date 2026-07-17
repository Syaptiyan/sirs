<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Session extends BaseConfig
{
    public string $driver = 'CodeIgniter\Session\Handlers\FileHandler';
    public ?string $savePath = null;
    public string $cookieName = 'sirs_session';
    public int $expiration = 1800;
    public bool $matchIP = false;
    public bool $matchUserAgent = true;
    public string $cookieDomain = '';
    public string $cookiePath = '/';
    public bool $cookieSecure = false;
    public bool $cookieHTTPOnly = true;
    public string $cookieSameSite = 'Lax';
    public bool $destroyOnLogout = true;
}