<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Themes;

use SD\Themes\Service\ReloadService;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

final class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    private $service;

    /**
     * Listen to the bootstrap event.
     *
     * @param EventInterface|\Zend\Mvc\MvcEvent $event
     *
     * @return array|void
     */
    public function onBootstrap(EventInterface $event)
    {
        $app = $event->getApplication();
        $this->service = $app->getServiceManager();
        $eventManager = $app->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        $eventManager->attach('render', [$this, 'loadTheme'], 100);
        $sharedEventManager->attach(ReloadService::class, 'reload', [$this, 'reloadConfig'], 101);
    }

    /**
     * Listen for theme change and override Config.
     */
    public function reloadConfig()
    {
        $request = $this->service->get('Request');

        $config = $this->service->get('Config');
        $this->service->setAllowOverride(true);
        $config['theme']['name'] = $request->getPost()['themeName'];
        $this->service->setService('Config', $config);
        $this->service->setAllowOverride(false);
    }

    /**
     * Setup theme.
     *
     * @param MvcEvent $event
     *
     * @return array|object
     */
    public function loadTheme(MvcEvent $event)
    {
        /*
         * Exclude modules that doesn't need a dinamicly changed layout
         */
        if (!in_array($event->getRouteMatch()->getMatchedRouteName(), ['admin', 'admin/default', 'themes', 'themes/default'])) {
            return $this->service->get('initThemes');
        }

        return [];
    }

    /**
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }
}
