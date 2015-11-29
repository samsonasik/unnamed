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

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SD\Admin\Entity\ContentInterface;
use SD\Admin\Entity\Content;
use Zend\Form\FormInterface;
use Zend\File\Transfer\Adapter\Http;
use Zend\Mvc\MvcEvent;
use Zend\Validator\File\Extension;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\Size;
use Zend\View\Model\JsonModel;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Stdlib\RequestInterface;

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
     * @param FormInterface $contentForm
     */
    public function __construct(FormInterface $contentForm)
    {
        parent::__construct();

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
            $paginator = $this->contentTable->preparePagination($this->getNewsContent(), false);
        } else {
            $paginator = $this->contentTable->preparePagination($this->getMenuContent(), true);
        }

        $paginator->setCurrentPageNumber((int) $this->getParam('page'));
        $paginator->setItemCountPerPage($this->systemSettings('posts', 'language'));
        $this->getView()->setVariable('paginator', $paginator);

        return $this->getView();
    }

    /**
     * @return object
     */
    private function getNewsContent()
    {
        $query = $this->contentTable->queryBuilder()->select(['c'])
                    ->from('SD\Admin\Entity\Content', 'c')
                    ->where('c.type = 1 AND c.language = :language')
                    ->setParameter(':language', (int) $this->language())
                    ->orderBy('c.date DESC');

        return $query;
    }

    /**
     * @return object
     */
    public function getMenuContent()
    {
        return $this->contentTable->queryBuilder()->getEntityManager()->createQuery('SELECT c FROM SD\Admin\Entity\Content AS c LEFT JOIN SD\Admin\Entity\Menu AS m WITH c.menu=m.id WHERE c.type = 0 AND c.language = :language ORDER BY m.parent ASC, m.menuOrder ASC, c.date DESC')->setParameter(':language', $this->language());
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
     * @param null|ContentInterface $content
     *
     * @return object|null
     */
    private function initForm(ContentInterface $content = null)
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

        /* @var RequestInterface */
        $this->processFormRequest($this->getRequest(), $form, $content);
    }

    /**
     * @param RequestInterface $request
     * @param FormInterface    $form
     * @param ContentInterface $content
     */
    private function processFormRequest(RequestInterface $request, FormInterface $form, ContentInterface $content)
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
     * @param ContentInterface     $content
     * @param array       $data
     *
     * @return void
     */
    private function processFormData(FormInterface $form, ContentInterface $content, array $data)
    {
        $form->setInputFilter($form->getInputFilter());
        $form->setData($data);

        $this->saveFormData($form, $content);
    }

    /**
     * @param FormInterface $form
     * @param ContentInterface     $content
     *
     * @return object|null
     */
    private function saveFormData(FormInterface $form, ContentInterface $content)
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
        /** @var RequestInterface $request */
        $request = $this->getRequest();
        $data = [];

        if ($request->isXmlHttpRequest()) {
            $data = $this->prepareImages();
        }

        return new JsonModel($data);
    }

    /**
     * Deleted image with from a given src.
     *
     * @return bool
     */
    protected function deleteImageAction()
    {
        /** @var RequestInterface $request */
        $request = $this->getRequest();
        $status = false;

        if ($request->isPost()) {
            $data = $request->getPost()->toArray();

            if ($request->isXmlHttpRequest()) {
                if (is_file('public'.$data['img'])) {
                    unlink('public'.$data['img']);
                    $status = true;
                }
            }
        }

        return $status;
    }

    /**
     * Get all files from all folders and list them in the gallery
     * getcwd() is there to make the work with images path easier.
     *
     * @return JsonModel
     */
    protected function filesAction()
    {
        chdir(getcwd().'/public/');
        $this->makeDir();
        $this->getView()->setTerminal(true);
        $dir = new RecursiveDirectoryIterator('userfiles/', FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);
        $iterator->setMaxDepth(50);
        $files = $this->extractImages($iterator);

        chdir(dirname(getcwd()));
        $model = new JsonModel();
        $model->setVariables(['files' => $files]);

        return $model;
    }

    /**
     * @param RecursiveIteratorIterator $iterator
     *
     * @return array
     */
    private function extractImages($iterator)
    {
        $files = [];
        $index = 0;

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $files[$index]['filelink'] = DIRECTORY_SEPARATOR.$file->getPath().DIRECTORY_SEPARATOR.$file->getFilename();
                $files[$index]['filename'] = $file->getFilename();
                $index = $index + 1;
            }
        }

        return $files;
    }

    /**
     * @param string $publicFolder
     *
     * @return void
     */
    private function makeDir($publicFolder = 'public/')
    {
        if (!is_dir($publicFolder.'userfiles/'.date('Y_M').'/images/')) {
            mkdir($publicFolder.'userfiles/'.date('Y_M').'/images/', 0750, true);
        }
    }

    /**
     * Upload all images async.
     *
     * @return array
     */
    private function prepareImages()
    {
        $adapter = new Http();
        $size = new Size(['min' => '10kB', 'max' => '5MB', 'useByteString' => true]);
        $extension = new Extension(['jpg', 'gif', 'png', 'jpeg', 'bmp', 'webp', 'svg'], true);

        $adapter->setValidators([$size, new IsImage(), $extension]);

        $this->makeDir('public/');

        $adapter->setDestination('public/userfiles/'.date('Y_M').'/images/');

        return $this->uploadFiles($adapter);
    }

    /**
     * @param AdapterInterface $adapter
     *
     * @return array
     */
    private function uploadFiles(AdapterInterface $adapter)
    {
        $uploadStatus = [];

        foreach ($adapter->getFileInfo() as $key => $file) {
            if ($key !== 'preview') {
                // @codeCoverageIgnoreStart
                $arr1 = $this->validateUploadedFileName($adapter, $file['name']);

                $arr2 = $this->validateUploadedFile($adapter, $file['name']);
                $uploadStatus = array_merge_recursive($arr1, $arr2);
                // @codeCoverageIgnoreEnd
            }
        }

        return $uploadStatus;
    }

    /**
     * See if file has been received and uploaded.
     *
     * @param AdapterInterface   $adapter
     * @param string $fileName
     *
     * @return array
     */
    private function validateUploadedFile(AdapterInterface $adapter, $fileName)
    {
        $uploadStatus = [];
        $adapter->receive($fileName);

        if (!$adapter->isReceived($fileName) && $adapter->isUploaded($fileName)) {
            $uploadStatus['errorFiles'][] = $fileName.' was not uploaded';
        } else {
            $uploadStatus['successFiles'][] = $fileName.' was successfully uploaded';
        }

        return $uploadStatus;
    }

    /**
     * See if file name is valid and it not return alll messages.
     *
     * @param AdapterInterface   $adapter
     * @param string             $fileName
     *
     * @return array
     */
    private function validateUploadedFileName(AdapterInterface $adapter, $fileName)
    {
        $uploadStatus = [];

        if (!$adapter->isValid($fileName)) {
            foreach ($adapter->getMessages() as $msg) {
                $uploadStatus['errorFiles'][] = $fileName.' '.strtolower($msg);
            }
        }

        return $uploadStatus;
    }
}
