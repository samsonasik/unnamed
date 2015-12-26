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

use SD\Admin\Entity\Content;
use Zend\Form\FormInterface;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use SD\Admin\Controller\AjaxGalleryController;

/**
 * @method object getTable($tableName)
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 * @method mixed getParam($paramName = null)
 * @method string|null systemSettings($option = 'general', $value = 'site_name')
 */
final class ContentController extends BaseController
{
    /*
     * @var array
     */
    protected $acceptCriteria = [
        'Zend\View\Model\JsonModel' => ['application/json'],
        'Zend\View\Model\ViewModel' => ['text/html'],
    ];

    /*
     * @var FormInterface
     */
    private $contentForm;

    /**
     * @var \SD\Admin\Model\ContentTable
     */
    private $contentTable;

    /**
     * @var AjaxGalleryController
     */
    private $ajaxGallery;

    /**
     * @param FormInterface $contentForm
     */
    public function __construct(FormInterface $contentForm)
    {
        parent::__construct();

        $this->ajaxGallery = new AjaxGalleryController();
        $this->contentForm = $contentForm;
    }

    /**
     * @param MvcEvent $event
     *
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $event)
    {
        $this->addBreadcrumb(['reference' => '/admin/content', 'name' => $this->translate('CONTENTS')]);
        $this->contentTable = $this->getTable('SD\\Admin\\Model\\ContentTable');

        parent::onDispatch($event);
    }

    /**
     * This action shows the list of all contents.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('admin/content/index');

        if ((int) $this->getParam('id') === 1) {
            $paginator = $this->contentTable->preparePagination($this->contentTable->getNewsContent($this->language()), false);
        } else {
            $paginator = $this->contentTable->preparePagination($this->contentTable->getMenuContent($this->language()), true);
        }

        $paginator->setCurrentPageNumber((int) $this->getParam('page'));
        $paginator->setItemCountPerPage($this->systemSettings('posts', 'language'));
        $this->getView()->setVariable('paginator', $paginator);

        return $this->getView();
    }

    /**
     * This action serves for adding a new object of type Content.
     *
     * @return \Zend\View\Model\ViewModel
     */
    protected function addAction()
    {
        $this->getView()->setTemplate('admin/content/add');
        $this->initForm();
        $this->addBreadcrumb(['reference' => '/admin/content/add', 'name' => $this->translate('ADD_NEW_CONTENT')]);

        return $this->getView();
    }

    /**
     * This action presents a edit form for Content object with a given id and session language.
     * Upon POST the form is processed and saved.
     *
     * @return \Zend\View\Model\ViewModel
     */
    protected function editAction()
    {
        $this->acceptableviewmodelselector($this->acceptCriteria);

        $this->getView()->setTemplate('admin/content/edit');
        $content = $this->contentTable->getContent((int) $this->getParam('id'), $this->language());
        $this->getView()->setVariable('content', $content);
        $this->addBreadcrumb(['reference' => '/admin/content/edit/'.$content->getId().'', 'name' => $this->translate('EDIT_CONTENT').' &laquo;'.$content->getTitle().'&raquo;']);
        $this->initForm($content);

        return $this->getView();
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    protected function detailAction()
    {
        $this->getView()->setTemplate('admin/content/detail');
        $content = $this->contentTable->getContent((int) $this->getParam('id'), $this->language());
        $this->getView()->setVariable('content', $content);
        $this->addBreadcrumb(['reference' => '/admin/content/detail/'.$content->getId().'', 'name' => '&laquo;'.$content->getTitle().'&raquo; '.$this->translate('DETAILS')]);

        return $this->getView();
    }

    protected function deleteAction()
    {
        $this->contentTable->deleteContent((int) $this->getParam('id'), $this->language());
        $this->setLayoutMessages($this->translate('DELETE_CONTENT_SUCCESS'), 'success');

        return $this->redirect()->toUrl('/admin/content');
    }

    protected function deactivateAction()
    {
        $this->contentTable->toggleActiveContent((int) $this->getParam('id'), $this->language(), 0);
        $this->setLayoutMessages($this->translate('CONTENT_DISABLE_SUCCESS'), 'success');

        return $this->redirect()->toUrl('/admin/content');
    }

    protected function activateAction()
    {
        $this->contentTable->toggleActiveContent((int) $this->getParam('id'), $this->language(), 1);
        $this->setLayoutMessages($this->translate('CONTENT_ENABLE_SUCCESS'), 'success');

        return $this->redirect()->toUrl('/admin/content');
    }

    /**
     * This is common function used by add and edit actions.
     *
     * @param null|Content $content
     *
     * @return object|null
     */
    private function initForm(Content $content = null)
    {
        if (!$content instanceof Content) {
            $content = new Content([]);
        }

        /*
         * @var FormInterface
         */
        $form = $this->contentForm;
        $form->bind($content);
        $this->getView()->setVariable('form', $form);

        $this->processFormRequest($this->getRequest(), $form, $content);
    }

    /**
     * @param Request       $request
     * @param FormInterface $form
     * @param Content       $content
     */
    private function processFormRequest(Request $request, FormInterface $form, Content $content)
    {
        if ($request->isPost()) {
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $this->processFormData($form, $content, $data);
        }
    }

    /**
     * @param FormInterface $form
     * @param Content       $content
     * @param array         $data
     *
     * @return void
     */
    private function processFormData(FormInterface $form, Content $content, array $data)
    {
        $form->setInputFilter($form->getInputFilter());
        $form->setData($data);

        $this->saveFormData($form, $content);
    }

    /**
     * @param FormInterface $form
     * @param Content       $content
     *
     * @return object|null
     */
    private function saveFormData(FormInterface $form, Content $content)
    {
        if (!$form->isValid()) {
            return $this->setLayoutMessages($form->getMessages(), 'error');
        }

        $content->setAuthor($this->UserData()->getIdentity());

        /*
         * We only need the name. All images ar stored in the same folder, based on the month and year
         */
        $content->setPreview($form->getData()->getPreview()['name']);
        $this->contentTable->saveContent($content);

        $this->setLayoutMessages('&laquo;'.$content->getTitle().'&raquo; '.$this->translate('SAVE_SUCCESS'), 'success');

        return $this->redirect()->toUrl('/admin/content');
    }

    /**
     * @return JsonModel
     */
    protected function uploadAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            return $this->ajaxGallery->prepareImages();
        }
    }
}
