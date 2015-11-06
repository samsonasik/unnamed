<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace Application\Factory\Controller;

use Application\Controller\NewPasswordController;
use Zend\Mvc\Controller\ControllerManager;

final class NewPasswordControllerFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        $controller = new NewPasswordController(
            $serviceLocator->get('FormElementManager')->get('Application\Form\NewPasswordForm')
        );

        return $controller;
    }
}
