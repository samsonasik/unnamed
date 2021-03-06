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

use SD\Application\Controller\Plugin\Translate;
use Zend\Mvc\Controller\PluginManager;

final class TranslateFactory
{
    /**
     * {@inheritdoc}
     *
     * @param PluginManager $pluginManager
     *
     * @return Translate
     */
    public function __invoke(PluginManager $pluginManager)
    {
        /* @var \Zend\ServiceManager\ServiceManager */
        $translator = $pluginManager->getController()->getServiceLocator()->get('translator')->getTranslator();

        $plugin = new Translate($translator);

        return $plugin;
    }
}
