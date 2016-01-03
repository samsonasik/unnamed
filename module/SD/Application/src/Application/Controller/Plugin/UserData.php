<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin;

use SD\Application\Exception\AuthorizationException;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

final class UserData extends AbstractPlugin
{
    /**
     * @var AuthenticationService
     */
    private $auth;

    public function __construct()
    {
        $this->auth = new AuthenticationService();
    }

    /**
     * Clear all session data and identities.
     * Throw an exception, which will be captured by the event manager and logged.
     *
     * @param string $errorString
     *
     * @throws AuthorizationException
     */
    public function clearUserData($errorString = 'ERROR')
    {
        $this->auth->clearIdentity();
        throw new AuthorizationException($errorString);
    }

    /**
     * See if user is logged in.
     *
     * @param string $errorString
     *
     * @return bool
     */
    public function hasIdentity($errorString = 'ERROR')
    {
        if ($this->auth->hasIdentity()) {
            if ($this->getIdentity()) {
                return true;
            }

            return $this->clearUserData($errorString); // something is wrong, clear all user data
        }

        return false;
    }

    /**
     * @return int returns user id from database
     */
    public function getIdentity()
    {
        return (int) $this->auth->getIdentity()['id'];
    }
}
