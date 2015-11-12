<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
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
    public function clearUserData($errorString)
    {
        $this->auth->clearIdentity();
        throw new AuthorizationException($errorString);
    }

    /**
     * See if user is logged in.
     *
     * @param string $errorString
     *
     * @return boolean|null
     */
    public function checkIdentity($errorString = 'Error')
    {
        if ($this->auth->hasIdentity()) {
            if ($this->auth->getIdentity()) {
                return true;
            }

            return $this->clearUserData($errorString); // something is wrong, clear all user data
        }

        return;
    }

    /**
     * @return array
     */
    public function getIdentity()
    {
        return $this->auth->getIdentity();
    }
}
