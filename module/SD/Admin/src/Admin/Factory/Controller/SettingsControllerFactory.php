<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Factory\Controller;

use SD\Admin\Controller\SettingsController;
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
            $FormElementManager->get('SD\Admin\Form\SettingsMailForm'),
            $FormElementManager->get('SD\Admin\Form\SettingsPostsForm'),
            $FormElementManager->get('SD\Admin\Form\SettingsGeneralForm'),
            $FormElementManager->get('SD\Admin\Form\SettingsDiscussionForm'),
            $FormElementManager->get('SD\Admin\Form\SettingsRegistrationForm')
        );

        return $controller;
    }
}
