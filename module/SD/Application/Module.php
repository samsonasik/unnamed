<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Validator\AbstractValidator;

final class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    private $service;

    /**
     * {@inheritdoc}
     */
    public function onBootstrap(EventInterface $event)
    {
        $app = $event->getApplication();
        $moduleRouteListener = new ModuleRouteListener();
        $eventManager = $app->getEventManager();
        $this->service = $app->getServiceManager();
        $moduleRouteListener->attach($eventManager);

        $sessionManager = $this->service->get('initSession');
        if (!$sessionManager->sessionExists()) {
            $sessionManager->start();
            Container::setDefaultManager($sessionManager);
        }

        $eventManager->attach('dispatch', [$this, 'setTitleAndTranslation']);
        $eventManager->attach('dispatch.error', [$this, 'onError'], 2);
    }

    /**
     * Log errors.
     *
     * @param MvcEvent $event
     */
    public function onError(MvcEvent $event)
    {
        $service = $this->service->get('ErrorHandling');
        $service->logError($event, $this->service);
    }

    /**
     * Handle layout titles onDispatch.
     *
     * @param MvcEvent $event
     */
    public function setTitleAndTranslation(MvcEvent $event)
    {
        $route = $event->getRouteMatch();
        $title = $this->service->get('ControllerPluginManager')->get('systemsettings');
        $viewHelper = $this->service->get('ViewHelperManager');
        $lang = new Container('translations');

        $translator = $this->service->get('translator');

        /*
         * Load translations.
         */
        $renderer = $this->service->get('ViewManager')->getRenderer();
        $renderer->plugin('formRow')->setTranslator($translator, 'SD_Translations');
        $renderer->plugin('formCollection')->setTranslator($translator, 'SD_Translations');
        $renderer->plugin('formLabel')->setTranslator($translator, 'SD_Translations');
        $renderer->plugin('formSelect')->setTranslator($translator, 'SD_Translations');
        $renderer->plugin('formSubmit')->setTranslator($translator, 'SD_Translations');

        AbstractValidator::setDefaultTranslator($translator, 'formandtitle');
        $translator->setLocale($lang->offsetGet('languageName'))->setFallbackLocale('en');

        $viewModel = $event->getViewModel();
        $viewModel->setVariable('lang', $translator->getLocale());

        /*
         * Custom flash messenger.
         */
        $msg = $lang->offsetGet('flashMessages');
        $viewModel->setVariable('flashMessages', $msg);

        /*
         * Load page title
         */
        $action = ($route->getParam('post') ? ' - '.$route->getParam('post') : ucfirst($route->getParam('__CONTROLLER__')));

        $headTitleHelper = $viewHelper->get('headTitle');
        $headTitleHelper->append($title->__invoke('general', 'site_name').' '.$action);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }
}
