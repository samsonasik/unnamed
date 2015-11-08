<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace Admin\Factory\Controller;

use Admin\Controller\AdministratorController;
use Zend\Mvc\Controller\ControllerManager;

final class AdministratorControllerFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $form = $serviceLocator->get('FormElementManager')->get('Admin\Form\AdministratorForm');
        $form->setObjectManager($entityManager);
        $controller = new AdministratorController($form);

        return $controller;
    }
}
