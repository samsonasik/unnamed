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

use SD\Application\Controller\Plugin\InitMetaTags;
use Zend\Mvc\Controller\PluginManager;

class InitMetaTagsFactory
{
    /**
     * {@inheritdoc}
     *
     * @param PluginManager $pluginManager
     *
     * @return InitMetaTags
     */
    public function __invoke(PluginManager $pluginManager)
    {
        /* @var \Zend\ServiceManager\ServiceManager */
        $viewHelper = $pluginManager->getController()->getServiceLocator()->get('ViewHelperManager');

        /*
         * @var \Zend\View\Helper\Placeholder\Container
         */
        $placeholderContainer = $viewHelper->get('placeholder')->getContainer('customHead');

        /*
         * @var \Zend\View\Helper\HeadMeta
         */
        $headMeta = $viewHelper->get('HeadMeta');

        /*
         * @var \Zend\Http\PhpEnvironment\Request
         */
        $request = $pluginManager->getController()->getRequest();

        /*
         * @var \SD\Application\Controller\Plugin\SystemSettings
         */
        $systemSettings = $pluginManager->get('systemsettings');

        /*
         * @var InitMetaTags
         */
        $plugin = new InitMetaTags($placeholderContainer, $headMeta, $request, $systemSettings);

        return $plugin;
    }
}
