<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Factory;

use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;

class SessionFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions([
            'use_cookies'             => true,
            'cookie_path'             => '/',
            'cookie_httponly'         => true,
            'name'                    => 'zpc',
            'cookie_secure'           => $this->isSSL(),
            'hash_bits_per_character' => 6,
            'hash_function'           => 1,
        ]);
        $sessionManager = new SessionManager($sessionConfig);

        return $sessionManager;
    }

    /**
     * Detect SSL/TLS protocol. If true activate cookie_secure key.
     * Same code as the one from Functions.php, but this way it's skips the call to SM. It saves 3 function calls.
     *
     * @return bool
     */
    private function isSSL()
    {
        $stats = false;
        if (isset($_SERVER['HTTPS'])) {
            if ('on' === strtolower($_SERVER['HTTPS']) || '1' === $_SERVER['HTTPS']) {
                $stats = true;
            }
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' === $_SERVER['SERVER_PORT'])) {
            $stats = true;
        }

        return $stats;
    }
}
