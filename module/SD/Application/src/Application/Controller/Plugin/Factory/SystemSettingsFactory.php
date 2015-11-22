<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin\Factory;

use SD\Application\Controller\Plugin\SystemSettings;
use Zend\Mvc\Controller\PluginManager;

class SystemSettingsFactory
{
    /**
     * {@inheritdoc}
     *
     * @param PluginManager $pluginManager
     *
     * @return SystemSettings
     */
    public function __invoke(PluginManager $pluginManager)
    {
        /* @var \Zend\ServiceManager\ServiceLocatorInterface */
        $serviceLocator = $pluginManager->getController()->getServiceLocator();

        $config = $serviceLocator->get('Config');

        /**
         * No need to pass a second argument, just for the theme name. Instead,merge it into the already system config array
         */
        $config['system_config']['theme']['name'] = $config['theme']['name'];
        $plugin = new SystemSettings($config['system_config']);

        return $plugin;
    }
}
