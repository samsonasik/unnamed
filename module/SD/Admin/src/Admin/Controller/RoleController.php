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

use Zend\Form\FormInterface;
use Zend\Mvc\MvcEvent;

final class RoleController extends BaseController
{
    /*
     * @var FormInterface
     */
    private $roleForm;

    /**
     * @var \SD\Admin\Model\RoleTable
     */
    private $roleTable;

    /**
     * @param FormInterface $roleForm
     */
    public function __construct(FormInterface $roleForm)
    {
        parent::__construct();

        $this->roleForm = $roleForm;
    }

    /**
     * @param MvcEvent $event
     *
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $event)
    {
        $this->addBreadcrumb(['reference' => '/admin/content', 'name' => $this->translate('ROLES')]);
        $this->roleTable = $this->getTable('SD\\Admin\\Model\\RoleTable');

        parent::onDispatch($event);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('admin/role/index');
        $paginator = [];
        $this->getView()->setVariable('paginator', $paginator);

        return $this->getView();
    }
}
