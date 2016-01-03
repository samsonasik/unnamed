<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
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
            'cookie_secure'           => $this->isSsl(),
            'hash_bits_per_character' => 6,
            'hash_function'           => 1,
        ]);
        $sessionManager = new SessionManager($sessionConfig);

        return $sessionManager;
    }

    /**
     * Detect SSL/TLS protocol. If true activate cookie_secure key.
     *
     * @return bool
     */
    private function isSsl()
    {
        $stats = false;

        if (isset($_SERVER['HTTPS']) && in_array($_SERVER['HTTPS'], ['on', '1', 'ON', 'On', 1, 'oN'])) {
            $stats = true;
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' === $_SERVER['SERVER_PORT'])) {
            $stats = true;
        }

        return $stats;
    }
}
