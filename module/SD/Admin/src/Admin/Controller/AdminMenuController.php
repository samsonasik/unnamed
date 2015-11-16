<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Controller;

use SD\Admin\Entity\AdminMenu;
use SD\Admin\Form\AdminMenuForm;
use Zend\Mvc\MvcEvent;

/**
 * @method object getTable($tableName)
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 * @method mixed getParam($paramName = null)
 */
final class AdminMenuController extends BaseController
{
    /*
     * @var AdminMenuForm
     */
    private $adminMenuForm;

    /**
     * @var \SD\Admin\Model\AdminMenuTable
     */
    private $adminMenuTable;

    /**
     * @param AdminMenuForm $adminMenuForm
     */
    public function __construct(AdminMenuForm $adminMenuForm)
    {
        parent::__construct();

        $this->adminMenuForm = $adminMenuForm;
    }

    /**
     * @param MvcEvent $event
     *
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $event)
    {
        $this->addBreadcrumb(['reference' => '/admin/admin-menu', 'name' => $this->translate('ADMIN_MENUS')]);
        $this->adminMenuTable = $this->getTable('SD\Admin\\Model\\AdminMenuTable');

        parent::onDispatch($event);
    }

    /**
     * This action shows the list of all admin menus.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('admin/admin-menu/index');
        $menu = $this->adminMenuTable
                        ->getEntityRepository()
                        ->findAll();

        if (count($menu) > 0) {
            $menus = ['menus' => [], 'submenus' => []];
            /** @var AdminMenu $submenus */
            foreach ($menu as $submenus) {
                if ($submenus->getParent() > 0) {
                    $menus['submenus'][$submenus->getParent()][] = $submenus;
                } else {
                    $menus['menus'][$submenus->getId()] = $submenus;
                }
            }

            $this->getView()->setVariable('menus', $menus['menus']);
            $this->getView()->setVariable('submenus', $menus['submenus']);
        }

        return $this->getView();
    }

    /**
     * This action serves for adding a new admin menus.
     *
     * @return \Zend\View\Model\ViewModel
     */
    protected function addAction()
    {
        $this->getView()->setTemplate('admin/admin-menu/add');
        $this->initForm(null);
        $this->addBreadcrumb(['reference' => '/admin/admin-menu/add', 'name' => $this->translate('ADD_ADMINMENU')]);

        return $this->getView();
    }

    /**
     * This action presents a edit form for AdminMenu with a given id.
     * Upon POST the form is processed and saved.
     *
     * @return \Zend\View\Model\ViewModel
     */
    protected function editAction()
    {
        $this->getView()->setTemplate('admin/admin-menu/edit');
        $adminMenu = $this->adminMenuTable->getAdminMenu((int) $this->getParam('id'));
        $this->getView()->setVariable('adminMenu', $adminMenu);
        $this->addBreadcrumb(['reference' => '/admin/admin-menu/edit/'.$adminMenu->getId().'', 'name' => $this->translate('EDIT_ADMINMENU').' &laquo;'.$adminMenu->getCaption().'&raquo;']);
        $this->initForm($adminMenu);

        return $this->getView();
    }

    /**
     * This action deletes a admin menu with a provided id.
     */
    protected function deleteAction()
    {
        $this->adminMenuTable->deleteAdminMenu((int) $this->getParam('id'));
        $this->setLayoutMessages($this->translate('DELETE_ADMINMENU_SUCCESS'), 'success');
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    protected function detailAction()
    {
        $this->getView()->setTemplate('admin/admin-menu/detail');
        $adminMenu = $this->adminMenuTable->getAdminMenu((int) $this->getParam('id'));
        $this->getView()->setVariable('adminMenu', $adminMenu);
        $this->addBreadcrumb(['reference' => '/admin/admin-menu/detail/'.$adminMenu->getId().'', 'name' => '&laquo;'.$adminMenu->getCaption().'&raquo; '.$this->translate('DETAILS')]);

        return $this->getView();
    }

    /**
     * This is common function used by add and edit actions (to avoid code duplication).
     *
     * @param AdminMenu|null $adminMenu
     *
     * @return object|false
     */
    private function initForm(AdminMenu $adminMenu = null)
    {
        if (!$adminMenu instanceof AdminMenu) {
            $adminMenu = new AdminMenu([]);
        }

        $form = $this->adminMenuForm;
        $form->bind($adminMenu);
        $this->getView()->setVariable('form', $form);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($form->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->adminMenuTable->saveAdminMenu($adminMenu);

                return $this->setLayoutMessages('&laquo;'.$adminMenu->getCaption().'&raquo; '.$this->translate('SAVE_SUCCESS'), 'success');
            }

            return $this->setLayoutMessages($form->getMessages(), 'error');
        }

        return false;
    }
}
