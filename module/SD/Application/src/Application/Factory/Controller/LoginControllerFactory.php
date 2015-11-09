<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Factory\Controller;

use SD\Application\Controller\LoginController;
use Zend\Mvc\Controller\ControllerManager;

final class LoginControllerFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        /* @var \Zend\Authentication\AuthenticationService */
        $doctrineAuthService = $serviceLocator->get('doctrine.authenticationservice.orm_default');

        $controller = new LoginController(
            $serviceLocator->get('FormElementManager')->get('SD\Application\Form\LoginForm'),
            $doctrineAuthService
        );

        return $controller;
    }
}
