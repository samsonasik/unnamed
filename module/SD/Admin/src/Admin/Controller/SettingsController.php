<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Controller;

use SD\Admin\Form\SettingsDiscussionForm;
use SD\Admin\Form\SettingsGeneralForm;
use SD\Admin\Form\SettingsMailForm;
use SD\Admin\Form\SettingsPostsForm;
use SD\Admin\Form\SettingsRegistrationForm;
use Zend\Mvc\MvcEvent;

/**
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 */
final class SettingsController extends BaseController
{
    /**
     * @var SettingsMailForm
     */
    private $mailForm;

    /**
     * @var SettingsPostsForm
     */
    private $postsForm;

    /**
     * @var SettingsGeneralForm
     */
    private $generalForm;

    /**
     * @var SettingsDiscussionForm
     */
    private $discussionForm;

    /**
     * @var SettingsRegistrationForm
     */
    private $registrationForm;

    /**
     * @method __construct
     *
     * @param SettingsMailForm         $mailForm
     * @param SettingsPostsForm        $postsForm
     * @param SettingsGeneralForm      $generalForm
     * @param SettingsDiscussionForm   $discussionForm
     * @param SettingsRegistrationForm $registrationForm
     */
    public function __construct(
        SettingsMailForm $mailForm,
        SettingsPostsForm $postsForm,
        SettingsGeneralForm $generalForm,
        SettingsDiscussionForm $discussionForm,
        SettingsRegistrationForm $registrationForm
    ) {
        parent::__construct();

        $this->mailForm = $mailForm;
        $this->postsForm = $postsForm;
        $this->generalForm = $generalForm;
        $this->discussionForm = $discussionForm;
        $this->registrationForm = $registrationForm;
    }

    /**
     * @param MvcEvent $event
     *
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $event)
    {
        parent::onDispatch($event);
        $this->addBreadcrumb(['reference' => '/admin/settings', 'name' => $this->translate('SETTINGS')]);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    protected function generalAction()
    {
        $this->getView()->setTemplate('admin/settings/general');

        $this->initForm($this->generalForm);

        return $this->getView();
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    protected function registrationAction()
    {
        $this->getView()->setTemplate('admin/settings/registration');

        $this->initForm($this->registrationForm, 'registration');

        return $this->getView();
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    protected function mailAction()
    {
        $this->getView()->setTemplate('admin/settings/mail');

        $this->initForm($this->mailForm, 'mail');

        return $this->getView();
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    protected function postsAction()
    {
        $this->getView()->setTemplate('admin/settings/posts');

        $this->initForm($this->postsForm, 'posts');

        return $this->getView();
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    protected function discussionAction()
    {
        $this->getView()->setTemplate('admin/settings/discussion');

        $this->initForm($this->discussionForm, 'discussion');

        return $this->getView();
    }

    /**
     * @param $form object
     * @param string $actionKey
     *
     * @internal param string $action
     */
    private function initForm($form, $actionKey = 'general')
    {
        $filename = 'config/autoload/system.local.php';
        if (!is_file($filename)) {
            chmod('config', 0755);
            copy('config/autoload/system_default.php', $filename);
        }
        $settings = include $filename;
        $this->getView()->setVariable('form', $form);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($form->getInputFilter());
            $form->setData($request->getPost());

            if (!$form->isValid()) {
                return $this->setLayoutMessages($form->getMessages(), 'error');
            }

            $formData = $form->getData();

            unset($formData['submit'], $formData['s']);
            $settings['system_config'][$actionKey] = array_merge($settings['system_config'][$actionKey], $formData);

            file_put_contents($filename, '<?php return '.var_export($settings, true).';');
            $this->setLayoutMessages($this->translate('SETTINGS').' '.$this->translate('SAVE_SUCCESS'), 'success');

            return $this->redirect()->toUrl('/admin/settings/'.$actionKey);
        }
    }
}
