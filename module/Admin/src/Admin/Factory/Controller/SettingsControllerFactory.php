<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       TBA
 */
namespace Admin\Factory\Controller;

use Admin\Controller\SettingsController;
use Zend\Mvc\Controller\ControllerManager;

final class SettingsControllerFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $FormElementManager = $controllerManager->getServiceLocator()->get('FormElementManager');

        $controller = new SettingsController(
            $FormElementManager->get('Admin\Form\SettingsMailForm'),
            $FormElementManager->get('Admin\Form\SettingsPostsForm'),
            $FormElementManager->get('Admin\Form\SettingsGeneralForm'),
            $FormElementManager->get('Admin\Form\SettingsDiscussionForm'),
            $FormElementManager->get('Admin\Form\SettingsRegistrationForm')
        );

        return $controller;
    }
}
