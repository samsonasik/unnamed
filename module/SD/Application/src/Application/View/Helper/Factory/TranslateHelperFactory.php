<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\View\Helper\Factory;

use SD\Application\View\Helper\TranslateHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

final class TranslateHelperFactory
{
    /**
     * {@inheritdoc}
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return TranslateHelper
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $pluginManager = $serviceLocator->get('ControllerPluginManager');
        $translate = $pluginManager->get('translate');

        $plugin = new TranslateHelper($translate);

        return $plugin;
    }
}
