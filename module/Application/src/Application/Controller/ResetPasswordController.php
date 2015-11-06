<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       TBA
 */
namespace Application\Controller;

use Application\Entity\ResetPassword;
use Application\Exception\RuntimeException;
use Application\Form\ResetPasswordForm;
use Zend\Authentication\AuthenticationService;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

/**
 * @method object getTable($tableName)
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 * @method mixed UserData()
 * @method mixed getFunctions()
 * @method object Mailing()
 */
final class ResetPasswordController extends BaseController
{
    /*
     * @var ResetPasswordForm
     */
    private $resetPasswordForm;

    /**
     * @param ResetPasswordForm $resetPasswordForm
     */
    public function __construct(ResetPasswordForm $resetPasswordForm)
    {
        parent::__construct();

        $this->resetPasswordForm = $resetPasswordForm;
    }

    /**
     * @param MvcEvent $event
     *
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $event)
    {
        parent::onDispatch($event);
        /*
         * If user is logged and tries to access one of the given actions
         * he will be redirected to the root url of the website.
         */
        if (APP_ENV !== 'development') {
            if ($this->UserData()->checkIdentity()) {
                $this->redirect()->toUrl('/');
            }
        }
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/resetpassword/index');

        $this->resetPasswordForm->get('resetpw')->setValue($this->translate('RESET_PW'));
        $this->resetPasswordForm->get('email')->setLabel($this->translate('EMAIL'));
        $this->getView()->setVariable('form', $this->resetPasswordForm);

        return $this->getView();
    }

    /**
     * @return object
     */
    public function processAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->redirect()->toUrl('/resetpassword');
        }

        /*
         * @var ResetPasswordForm
         */
        $form = $this->resetPasswordForm;
        $form->setInputFilter($form->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $this->setLayoutMessages($form->getMessages(), 'error');
        }

        $formData = $form->getData();
        /** @var \Admin\Entity\User $existingEmail */
        $existingEmail = $this->getTable('Admin\\Model\\UserTable')
                                ->getEntityRepository()
                                ->findBy(['email' => $formData['email']]);

        if (count($existingEmail) !== 1) {
            return $this->setLayoutMessages($this->translate('EMAIL').' <b>'.$formData['email'].'</b> '.$this->translate('NOT_FOUND'), 'warning');
        }

        $func = $this->getFunctions();
        $token = $func::generateToken();
        $resetpw = new ResetPassword();
        $remote = new RemoteAddress();
        $resetpw->setToken($token);
        $resetpw->setUser($existingEmail[0]->getId());
        $resetpw->setDate(date('Y-m-d H:i:s', time()));
        $resetpw->setIp($remote->getIpAddress());
        $this->getTable('Application\\Model\\ResetPasswordTable')->saveResetPassword($resetpw);
        $message = $this->translate('NEW_PW_TEXT').' '.$_SERVER['SERVER_NAME'].'/login/newpassword/token/'.$token;

        $result = $this->Mailing()->sendMail($formData['email'], $existingEmail[0]->getFullName(), $this->translate('NEW_PW_TITLE'), $message, $this->systemSettings('general', 'system_email'), $this->systemSettings('general', 'site_name'));

        if (!$result) {
            return $this->setLayoutMessages($this->translate('EMAIL_NOT_SENT'), 'error');
        }

        return $this->setLayoutMessages($this->translate('PW_SENT').' <b>'.$formData['email'].'</b>', 'success');
    }
}
