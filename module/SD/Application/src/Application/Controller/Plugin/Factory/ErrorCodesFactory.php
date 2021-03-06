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

use SD\Application\Controller\Plugin\ErrorCodes;
use Zend\Mvc\Controller\PluginManager;

class ErrorCodesFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(PluginManager $pluginManager)
    {
        $controller = $pluginManager->getController();

        $layout = $controller->layout();

        $response = $controller->getResponse();

        $plugin = new ErrorCodes($layout, $response);

        return $plugin;
    }
}
