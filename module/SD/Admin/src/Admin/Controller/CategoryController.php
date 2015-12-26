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

use SD\Admin\Entity\Category;
use SD\Admin\Form\CategoryForm;
use Zend\Mvc\MvcEvent;

/**
 * @method object getTable($tableName)
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 * @method mixed getParam($paramName = null)
 */
final class CategoryController extends BaseController
{
    /**
     * @var CategoryForm
     */
    private $categoryForm;

    /**
     * @var \SD\Admin\Model\CategoryTable
     */
    private $categoryTable;

    /**
     * @param CategoryForm $categoryForm
     */
    public function __construct(CategoryForm $categoryForm)
    {
        parent::__construct();

        $this->categoryForm = $categoryForm;
    }

    /**
     * @param MvcEvent $event
     *
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $event)
    {
        $this->addBreadcrumb(['reference' => '/admin/category', 'name' => $this->translate('CATEGORIES')]);
        $this->categoryTable = $this->getTable('SD\\Admin\\Model\\CategoryTable');

        parent::onDispatch($event);
    }

    /**
     * This action shows the list of all Categorys.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('admin/category/index');

        $this->getView()->setVariable('paginator', $this->categoryTable->getEntityRepository()->findAll());

        return $this->getView();
    }

    /**
     * This action serves for adding a new users as category.
     *
     * @return \Zend\View\Model\ViewModel
     */
    protected function addAction()
    {
        $this->getView()->setTemplate('admin/category/add');
        $this->initForm(null);
        $this->addBreadcrumb(['reference' => '/admin/category/add', 'name' => $this->translate('ADD_CATEGORY')]);

        return $this->getView();
    }

    /**
     * This action presents a edit form for Category object with a given id.
     * Upon POST the form is processed and saved.
     *
     * @return \Zend\View\Model\ViewModel
     */
    protected function editAction()
    {
        $this->getView()->setTemplate('admin/category/edit');
        $category = $this->categoryTable->getCategory((int) $this->getParam('id'));
        $this->addBreadcrumb(['reference' => '/admin/category/edit/'.$category->getTitle().'', 'name' => $this->translate('EDIT_CATEGORY')]);
        $this->initForm($category);

        return $this->getView();
    }

    /**
     * this action deletes a category.
     */
    protected function deleteAction()
    {
        $id = (int) $this->getParam('id');
        $this->categoryTable->deleteCategory($id);
        $this->setLayoutMessages($this->translate('DELETE_CATEGORY_SUCCESS'), 'success');
        $this->redirect()->toUrl('/admin/category');
    }

    /**
     * This is common function used by add and edit actions (to avoid code duplication).
     *
     * @param Category|null $category
     *
     * @return null|object
     */
    private function initForm(Category $category = null)
    {
        if (!$category instanceof Category) {
            $category = new Category([]);
        }

        /*
         * @var CategoryForm
         */
        $form = $this->categoryForm;
        $form->bind($category);
        $this->getView()->setVariable('form', $form);
        $this->getView()->setVariable('categoryEdit', $category);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($form->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category->setTitle($request->getPost()['title']); // workaround
                $category->setSlug($this->slugify($request->getPost()['title'])); // workaround
                $this->categoryTable->saveCategory($category);
                $this->setLayoutMessages('&laquo;'.$category->getTitle().'&raquo; '.$this->translate('SAVE_SUCCESS'), 'success');

                return $this->redirect()->toUrl('/admin/category');
            }

            return $this->setLayoutMessages($form->getMessages(), 'error');
        }

        return;
    }

    // http://stackoverflow.com/a/2955878/2855530

    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'ASCII//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            $text = null;
        }

        return $text;
    }
}
