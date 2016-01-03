<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Themes\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 */
final class IndexController extends AbstractActionController
{
    /**
     * @var array|object
     */
    private $themesConfig = [];

    /**
     * @var mixed
     */
    private $reloadService;

    /**
     * @param array|object $themesConfig
     * @param mixed        $reloadService
     */
    public function __construct($themesConfig, $reloadService)
    {
        $this->themesConfig = $themesConfig;
        $this->reloadService = $reloadService;
    }

    /**
     * This action shows the list of all themes.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $view = new ViewModel();
        $view->setTemplate('themes/index/index');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $filename = __DIR__.'/../../../config/module.config.php';
            $settings = include $filename;
            $themeName = $request->getPost()['themeName'];
            $settings['theme']['name'] = $themeName;
            file_put_contents($filename, '<?php return '.var_export($settings, true).';');
            $this->setLayoutMessages($this->translate('THEME_ACTIVATED'), 'success');
            $this->reloadService->reload();
        }

        $view->setVariable('themes', $this->themesConfig);

        return $view;
    }
}
