<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Themes\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;

final class ThemesFactory
{
    /**
     * @var string
     */
    private $publicDir;

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $themesConfig = $serviceLocator->get('getThemesFromDir');
        $config = $serviceLocator->get('Config');
        $headScript = $serviceLocator->get('ViewHelperManager')->get('headScript');
        $headLink = $serviceLocator->get('ViewHelperManager')->get('headLink');
        $this->publicDir = '/layouts/'.$config['theme']['name'].'/';

        /*
         * Get theme name from config and load it.
         *
         * At this point the user has already been selected the new theme he wants to use
         * from indexAction.
         */
        $viewTemplate = $serviceLocator->get('ViewTemplatePathStack');
        $themes = $themesConfig['themes'][$config['theme']['name']];

        $this->loadTemplateFiles($themes, $viewTemplate, $serviceLocator);
        $this->loadCss($themes, $headLink);
        $this->loadJs($themes, $headScript);

        return $viewTemplate;
    }

    /**
     * @method loadTemplateFiles
     *
     * @param array $themes
     * @param object $viewTemplate
     * @param ServiceLocatorInterface $serviceLocator
     */
    private function loadTemplateFiles($themes, $viewTemplate, ServiceLocatorInterface $serviceLocator) {
        if (isset($themes['template_path_stack'])) {
            $viewTemplate->addPaths($themes['template_path_stack']);
        }

        if (isset($themes['template_map'])) {
            $viewTemplate = $serviceLocator->get('ViewTemplateMapResolver');
            $viewTemplate->merge($themes['template_map']);
        }
    }

    /**
     * @method loadCss
     *
     * @param array $themes
     * @param object $headLink
     */
    private function loadCss($themes, $headLink)
    {
        foreach ($themes['css'] as $key => $file) {
            $headLink->prependStylesheet($this->publicDir.$file);
        }
    }

    /**
     * @method loadCss
     *
     * @param array $themes
     * @param object $headLink
     */
    private function loadJs($themes, $headScript)
    {
        foreach ($themes['js'] as $key => $file) {
            $headScript->prependFile($this->publicDir.$file);
        }
    }
}
