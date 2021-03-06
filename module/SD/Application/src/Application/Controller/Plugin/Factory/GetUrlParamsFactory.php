<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin\Factory;

use SD\Application\Controller\Plugin\GetUrlParams;
use Zend\Mvc\Controller\PluginManager;

class GetUrlParamsFactory
{
    /**
     * {@inheritdoc}
     *
     * @param PluginManager $pluginManager
     *
     * @return GetUrlParams
     */
    public function __invoke(PluginManager $pluginManager)
    {
        /*
         * @var \Zend\Mvc\Controller\Plugin\Params
         */
        $params = $pluginManager->get('params');

        $plugin = new GetUrlParams($params);

        return $plugin;
    }
}
