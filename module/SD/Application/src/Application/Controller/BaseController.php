<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * @method object getTable($tableName)
 * @method string translate($message = '')
 * @method mixed UserData()
 * @method void initMetaTags()
 */
class BaseController extends AbstractActionController
{
    /**
     * @var ViewModel
     */
    private $view;

    /**
     * @var Container
     */
    private $translation;

    public function __construct()
    {
        $this->view = new ViewModel();
        $this->translation = new Container('translations');

        if (!$this->getTranslation()->offSetExists('language')) {
            $this->getTranslation()->offsetSet('language', 1);
            $this->getTranslation()->offsetSet('languageName', 'en');
        }
    }

    /**
     * @param MvcEvent $event
     *
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $event)
    {
        parent::onDispatch($event);

        $this->validateLoggedUserAccess();
        $this->initMenus();

        /*
         * Call this method only if we are not in Menu or News. Both of them calls the function by themselves
         */
        if (($this->getParam('action') !== 'post')) {
            $this->initMetaTags();
        }
    }

    /**
     * @return void
     */
    private function validateLoggedUserAccess()
    {
        /*
         * If user is logged and tries to access one of the given controllers,
         * he will be redirected to the root url of the website.
         *
         * These controllers should be only accesed if the user is NOT logged in.
         */
        if (APP_ENV !== 'development') {
            $controllersArray = [
                'SD\Application\Controller\Login',
                'SD\Application\Controller\ResetPassword',
                'SD\Application\Controller\NewPassword',
                'SD\Application\Controller\Registration',
            ];

            if ($this->UserData()->hasIdentity() && in_array($this->getParam('controller'), $controllersArray)) {
                $this->redirect()->toUrl('/');
            }
        }
    }

    /**
     * Initialize menus and their submenus. 1 query to rule them all!
     *
     * @return ViewModel
     */
    private function initMenus()
    {
        $menu = $this->getTable('SD\\Admin\\Model\\MenuTable')
                        ->getEntityRepository()
                        ->findBy(['active' => 1, 'language' => $this->language()], ['parent' => 'DESC']);

        if (count($menu) > 0) {
            $menus = ['menus' => [], 'submenus' => []];
            /** @var \SD\Admin\Entity\AdminMenu $submenus */
            foreach ($menu as $submenus) {
                $menus['menus'][$submenus->getId()] = $submenus;
                $menus['submenus'][$submenus->getParent()][] = $submenus->getId();
            }

            $staticHtml = "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/'>".$this->translate('HOME').'</a></li>';
            $staticHtml .= "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/news'>".$this->translate('NEWS').'</a></li>';
            if ($this->UserData()->hasIdentity()) {
                $staticHtml .= "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/login/logout'>".$this->translate('SIGN_OUT').'</a></li>';
                $staticHtml .= "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/profile'>".$this->translate('PROFILE').'</a></li>';
                $staticHtml .= "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/settings'>".$this->translate('SETTINGS').'</a></li>';
            } else {
                $staticHtml .= "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/login'>".$this->translate('SIGN_IN').'</a></li>';
                $staticHtml .= "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/registration'>".$this->translate('SIGN_UP').'</a></li>';
            }

            $this->getView()->setVariable('menu', $this->generateMenu(0, $menus, 'menubar', $staticHtml));
        }

        return $this->getView();
    }

    /**
     * Builds menu HTML.
     *
     * @method generateMenu
     *
     * @param int    $parent
     * @param array  $menu
     * @param string $ariaRole
     * @param string $staticHtml - add html menus that do not come from database
     *
     * @return string generated html code
     */
    private function generateMenu($parent = 0, array $menu = [], $ariaRole = 'menubar', $staticHtml = '')
    {
        $output = '';
        if (isset($menu['submenus'][$parent])) {
            $output .= "<ul role='".$ariaRole."'>";
            $output .= $staticHtml;

            foreach ($menu['submenus'][$parent] as $id) {
                $output .= "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/menu/post/".$menu['menus'][$id]->getMenuLink()."'><em class='fa ".$menu['menus'][$id]->getClass()."'></em> ".$menu['menus'][$id]->getCaption().'</a>';
                $output .= $this->generateMenu($id, $menu, 'menu');
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }

    /**
     * Get Language id or name. Defaults to language - id.
     * If none is found - 1 will be returned as the default language id where 1 == en.
     *
     * @param string $offset
     *
     * @return int|string
     */
    final protected function language($offset = 'language')
    {
        if ($this->getTranslation()->offSetExists($offset)) {
            return $this->getTranslation()->offSetGet($offset);
        }

        return 1;
    }

    /**
     * @method getView
     *
     * @return ViewModel
     */
    protected function getView()
    {
        return $this->view;
    }

    /**
     * Returns session holding translations id and name.
     *
     * @method getTranslation
     *
     * @return Container
     */
    protected function getTranslation()
    {
        return $this->translation;
    }
}
