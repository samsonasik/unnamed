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

use Application\Controller\Plugin\SetLayoutMessages;
use Zend\Mvc\Controller\PluginManager;

class SetLayoutMessagesFactory
{
    /**
     * {@inheritdoc}
     *
     * @param PluginManager $pluginManager
     *
     * @return SetLayoutMessages
     */
    public function __invoke(PluginManager $pluginManager)
    {
        /** @var \Zend\View\Model\ViewModel */
        $layout = $pluginManager->getController()->layout();

        /** @var \Zend\Mvc\Controller\Plugin\FlashMessenger */
        $flashmessenger = $pluginManager->get('flashmessenger');

        $plugin = new SetLayoutMessages($layout, $flashmessenger);

        return $plugin;
    }
}
