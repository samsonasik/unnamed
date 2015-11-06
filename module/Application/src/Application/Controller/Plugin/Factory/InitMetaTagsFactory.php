<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace Application\Controller\Plugin\Factory;

use Application\Controller\Plugin\InitMetaTags;
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
        /*
         * @var \Zend\View\HelperPluginManager
         */
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
         * @var \Application\Controller\Plugin\SystemSettings
         */
        $systemSettings = $pluginManager->get('systemsettings');

        /*
         * @var InitMetaTags
         */
        $plugin = new InitMetaTags($placeholderContainer, $headMeta, $request, $systemSettings);

        return $plugin;
    }
}
