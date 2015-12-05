<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Themes\Factory\Controller;

use SD\Themes\Controller\IndexController;
use Zend\Mvc\Controller\ControllerManager;

final class IndexControllerFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();
        $themesConfig = $serviceLocator->get('getThemesFromDir');
        $reloadService = $serviceLocator->get('reloadService');
        $controller = new IndexController($themesConfig, $reloadService);

        return $controller;
    }
}
