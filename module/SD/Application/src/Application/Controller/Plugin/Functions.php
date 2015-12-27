<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin;

use SD\Application\Exception\InvalidArgumentException;
use SD\Application\Exception\RuntimeException;
use Zend\Math\Rand;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

final class Functions extends AbstractPlugin
{
    /**
     * Never set the salt parameter for this function unless you are not a security expert who knows what he/she is doing.
     *
     * @link http://blog.ircmaxell.com/2015/03/security-issue-combining-bcrypt-with.html
     *
     * @param string $password the user password in plain text
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     *
     * @return string the encrypted password with the salt. Salt comes from password_hash
     */
    public static function createPassword($password)
    {
        if (empty($password)) {
            throw new InvalidArgumentException('Password cannot be empty');
        }

        if (static::strLength($password) < 8) {
            throw new InvalidArgumentException('Password must be atleast 8 characters long');
        }

        $pw = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);

        if (empty($pw)) {
            throw new RuntimeException('Error while generating password');
        }

        return $pw;
    }

    /**
     * @param string $string The input string
     *
     * @return int The number of bytes
     */
    public static function strLength($string = '')
    {
        return mb_strlen($string, 'utf8');
    }

    /**
     * Generate a random string via the OpenSSL|MCRYPT|M_RAND functions. and return a base64 encode of it.
     *
     * @return string
     */
    public static function generateToken()
    {
        return base64_encode(Rand::getString(mt_rand(1, 100), null, true));
    }
}
