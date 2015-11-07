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

use Application\Controller\Plugin\Mailing;
use Zend\Mvc\Controller\PluginManager;

class MailingFactory
{
    /**
     * {@inheritdoc}
     *
     * @param PluginManager $pluginManager
     *
     * @return Mailing
     */
    public function __invoke(PluginManager $pluginManager)
    {
        /* @var \Zend\Mvc\Controller\Plugin\FlashMessenger */
        $flashmessenger = $pluginManager->get('flashmessenger');

        /* @var \Application\Controller\Plugin\SystemSettings */
        $systemsettings = $pluginManager->get('systemsettings');

        $plugin = new Mailing($flashmessenger, $systemsettings);

        return $plugin;
    }
}
