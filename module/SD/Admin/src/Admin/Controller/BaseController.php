<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Controller;

use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * @method object getTable($tableName)
 * @method string translate($message = '')
 * @method mixed getParam($paramName = null)
 * @method mixed UserData()
 */
class BaseController extends AbstractActionController
{
    /**f
     * @var ViewModel
     */
    private $view;

    /**
     * @var Container
     */
    private $translation;

    /**
     * @var array returns an array with links with the current user position on the website
     */
    private $breadcrumbs = [];

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
        if (APP_ENV !== 'development') {
            $this->isAdmin();
        }

        parent::onDispatch($event);
        $this->initMenus();

        $this->getView()->setVariable('breadcrumbs', $this->breadcrumbs);
    }

    /**
     * Initialize menus and their sub menus. 1 query to rule them all!
     *
     * @return ViewModel
     */
    private function initMenus()
    {
        $menu = $this->getTable('SD\\Admin\\Model\\AdminMenuTable')
                        ->getEntityRepository()
                        ->findAll();

        if (count($menu) > 0) {
            $menus = ['menus' => [], 'submenus' => []];
            /** @var \SD\Admin\Entity\AdminMenu $submenus */
            foreach ($menu as $submenus) {
                $menus['menus'][$submenus->getId()] = $submenus;
                $menus['submenus'][$submenus->getParent()][] = $submenus->getId();
            }

            $output = "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/admin'> ".$this->translate('DASHBOARD').'</a></li>';

            $this->getView()->setVariable('menuAdmin', $this->generateMenu(0, $menus, 'menubar', $output));
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
     * @param string $role
     * @param string $html   - add html menus that do not come from database
     *
     * @return string generated html code
     */
    private function generateMenu($parent = 0, array $menu = [], $role = 'menubar', $html = '')
    {
        $output = '';
        if (isset($menu['submenus'][$parent])) {
            $output .= "<ul role='".$role."'>";
            $output .= $html;

            foreach ($menu['submenus'][$parent] as $id) {
                $output .= "<li role='menuitem'><a hreflang='".$this->language('languageName')."' itemprop='url' href='/admin/".$menu['menus'][$id]->getController().'/'.$menu['menus'][$id]->getAction()."'><em class='fa ".$menu['menus'][$id]->getClass()."'></em> ".$menu['menus'][$id]->getCaption().'</a>';
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
     * @param array $breadcrumb
     */
    final protected function addBreadcrumb(array $breadcrumb = [])
    {
        $this->breadcrumbs[] = $breadcrumb;
    }

    /**
     * Is the user admin? Lets check that.
     * 1. Run this function and see if we are logged in as admin.
     *    If all went fine show the admin area.
     * 2. Else go to Login Controller and attempt to login as [u]real[/u] admin. Just in case log every access to login controller.
     * 3. On success run this function. If all went fine, access admin else clear identity and create log.
     *
     * @throws \SD\Admin\Exception\AuthorizationException If wrong credentials or not in administrator table
     *
     * @return mixed
     */
    private function isAdmin()
    {
        $auth = $this->UserData();
        if ($auth->hasIdentity($this->translate('ERROR_AUTHORIZATION'))) {
            $userId = $auth->getIdentity();
            $adminExist = $this->getTable('SD\\Admin\\Model\\AdministratorTable')
                                        ->queryBuilder()
                                        ->getEntityManager()
                                        ->createQuery("SELECT a.user, u.name FROM SD\Admin\Entity\Administrator AS a LEFT JOIN SD\Admin\Entity\User AS u WITH a.user=u.id WHERE u.id = '".$userId."' AND u.admin = 1")->getResult();

            if (isset($adminExist[0])) {
                unset($adminExist);

                return true;
            }
        }

        return $auth->clearUserData($this->translate('ERROR_AUTHORIZATION'));
    }

    /**
     * @return ViewModel
     */
    final protected function getView()
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

    /**
     * A common function for performing ajax search for user.
     *
     * @method ajaxUserSearch
     *
     * @param string        $search
     * @param null|callable $buttons - pass a function with the necessary button.
     *
     * @return JsonModel
     */
    protected function ajaxUserSearch($search, callable $buttons = null)
    {
        $json = [];
        $success = false;
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $this->getView()->setTerminal(true);

            /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
            $queryBuilder = $this->getTable('SD\Admin\\Model\\UserTable')->queryBuilder();

            $results = $queryBuilder->select(['u'])
                ->from('SD\Admin\Entity\User', 'u')
                ->where('u.name LIKE :name')
                ->orWhere('u.surname LIKE :surname')
                ->orWhere('u.email LIKE :email')
                ->setParameter(':name', (string) $search)
                ->setParameter(':surname', (string) $search)
                ->setParameter(':email', (string) $search)
                ->getQuery()
                ->getResult();

            if ($results) {
                /*
                 * @var \SD\Admin\Entity\User
                 */
                foreach ($results as $key => $result) {
                    $json[$key]['id'] = $result->getId();
                    $json[$key]['name'] = $result->getFullName();
                    $json[$key]['email'] = $result->getEmail();

                    if (is_callable($buttons)) {
                        $json[$key]['buttons'] = $buttons($result->getId(), $result->getFullName(), $result->isDisabled());
                    }
                }
                $success = true;
            }
        }

        return new JsonModel([
                'ajaxsearch' => Json::encode($json),
                'statusType' => $success,
        ]);
    }
}
