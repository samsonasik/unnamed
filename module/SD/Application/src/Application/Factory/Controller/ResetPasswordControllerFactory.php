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

use SD\Application\Controller\ResetPasswordController;
use Zend\Mvc\Controller\ControllerManager;

final class ResetPasswordControllerFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        $controller = new ResetPasswordController(
            $serviceLocator->get('FormElementManager')->get('SD\Application\Form\ResetPasswordForm')
        );

        return $controller;
    }
}
