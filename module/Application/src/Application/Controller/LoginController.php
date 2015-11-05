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
use Application\Form\LoginForm;
use Application\Form\NewPasswordForm;
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
 * @method mixed getParam($paramName = null, $default = null)
 * @method mixed getFunctions()
 * @method string|null systemSettings($option = 'general', $value = 'site_name')
 * @method object Mailing()
 */
final class LoginController extends BaseController
{
    /*
     * @var AuthenticationService
     */
    private $authService;

    /*
     * @var ResetPasswordForm
     */
    private $resetPasswordForm;

    /*
     * @var NewPasswordForm
     */
    private $newPasswordForm;

    /*
     * @var LoginForm
     */
    private $loginForm;

    /**
     * @param LoginForm             $loginForm
     * @param AuthenticationService $authService
     * @param ResetPasswordForm     $resetPasswordForm
     * @param NewPasswordForm       $newPasswordForm
     */
    public function __construct(LoginForm $loginForm,
        AuthenticationService $authService,
        ResetPasswordForm $resetPasswordForm,
        NewPasswordForm $newPasswordForm)
    {
        parent::__construct();

        $this->loginForm = $loginForm;
        $this->authService = $authService;
        $this->resetPasswordForm = $resetPasswordForm;
        $this->newPasswordForm = $newPasswordForm;
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
         * For reset password and new password actions we assume that the user is not logged in.
         */
        if (APP_ENV !== 'development') {
            $this->UserData()->checkIdentity();
        }
    }

    /**
     * Get database and check if given email and password matches.
     *
     * @param array $options
     *
     * @return AuthenticationService
     */
    private function getAuthAdapter(array $options = [])
    {
        /** @var AuthenticationService $authAdapter */
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setIdentityValue((string) $options['email']);
        $authAdapter->setCredentialValue((string) $options['password']);

        return $authAdapter;
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/login/index');
        /*
         * @var LoginForm
         */
        $form = $this->loginForm;

        $form->get('login')->setValue($this->translate('SIGN_IN'));
        $form->get('email')->setLabel($this->translate('EMAIL'));
        $form->get('password')->setLabel($this->translate('PASSWORD'));
        $this->getView()->setVariable('form', $form);

        return $this->getView();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function processloginAction()
    {
        $this->getView()->setTemplate('application/login/index');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->logoutAction();
        }

        /*
         * @var LoginForm
         */
        $form = $this->loginForm;
        $form->setInputFilter($form->getInputFilter());
        $form->setData($request->getPost());

        /*
         * See if form is valid
         */
        if (!$form->isValid()) {
            $this->setLayoutMessages($form->getMessages(), 'error');

            return $this->logoutAction();
        }

        $adapter = $this->getAuthAdapter($form->getData());
        $auth = new AuthenticationService();
        $result = $auth->authenticate($adapter);

        /*
         * See if authentication is valid
         */
        if (!$result->isValid()) {
            return $this->setLayoutMessages($result->getMessages(), 'error');
        }

        /*
         * If account is disabled/banned (call it w/e you like) clear user data and redirect
         */
        if ((int) $result->getIdentity()->isDisabled() === 1) {
            $this->setLayoutMessages($this->translate('LOGIN_ERROR'), 'error');

            return $this->logoutAction();
        }

        $result->getIdentity()->setLastLogin(date('Y-m-d H:i:s', time()));
        $remote = new RemoteAddress();
        $result->getIdentity()->setIp($remote->getIpAddress());
        $this->getTable('Admin\Model\UserTable')->saveUser($result->getIdentity());
        Container::getDefaultManager()->regenerateId();

        $this->authService->getStorage()->write($result->getIdentity()); // puts only id in session!
        return $this->redirect()->toUrl('/');
    }

    /**
     * The resetpasswordAction has generated a random token string.
     * In order to reset the account password, we need to take that token and validate it first.
     * If everything is fine, we let the user to reset his password.
     *
     * @throws RuntimeException
     */
    protected function newpasswordAction()
    {
        $this->getView()->setTemplate('application/login/newpassword');

        $token = (string) $this->getParam('token', null);
        $func = $this->getFunctions();

        /*
         * Check string bytes length
         */
        if ($func::strLength($token) !== 64) {
            throw new RuntimeException($this->translate('TOKEN_MISMATCH'));
        }

        /*
         * See if token exist or has expired
         */
        $tokenExist = $this->getTable('Application\\Model\\ResetPasswordTable');
        $tokenExist->columns(['user', 'token', 'date']);
        $tokenExist->where("token = '{$token}' AND date >= DATE_SUB(NOW(), INTERVAL 24 HOUR)", 'AND');
        $tokenExist = $tokenExist->fetch()->current();

        if (!$tokenExist) {
            return $this->setLayoutMessages($this->translate('LINK_EXPIRED'), 'error');
        }

        /*
         * @var NewPasswordForm
         */
        $form = $this->newPasswordForm;
        $form->get('password')->setLabel($this->translate('PASSWORD'))->setAttribute('placeholder', $this->translate('PASSWORD'));
        $form->get('repeatpw')->setLabel($this->translate('REPEAT_PASSWORD'))->setAttribute('placeholder', $this->translate('REPEAT_PASSWORD'));
        $form->get('resetpw')->setValue($this->translate('RESET_PW'));

        $this->getView()->setVariable('resetpwUserId', $tokenExist['user']);
        $this->getView()->setVariable('form', $form);

        return $this->getView();
    }

    /**
     * @return \Zend\Http\Response
     */
    public function newpasswordprocessAction()
    {
        $func = $this->getFunctions();

        /*
         * @var NewPasswordForm
         */
        $form = $this->newPasswordForm;

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($form->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $formData = $form->getData();
                $pw = $func::createPassword($formData->password);

                if (!empty($pw)) {
                    /** @var \Admin\Entity\User $user */
                    $user = $this->getTable('Admin\\Model\\UserTable')->getUser($this->getView()->getVariable('resetpwUserId'));
                    $remote = new RemoteAddress();
                    $user->setPassword($pw);
                    $user->setIp($remote->getIpAddress());
                    $this->getTable('Admin\\Model\\UserTable')->saveUser($user);
                    $this->setLayoutMessages($this->translate('NEW_PW_SUCCESS'), 'success');
                } else {
                    $this->setLayoutMessages($this->translate('PASSWORD_NOT_GENERATED'), 'error');
                }
            } else {
                $this->setLayoutMessages($form->getMessages(), 'error');
            }
        }

        return $this->redirect()->toUrl('/login');
    }

    /**
     * Show the reset password form. After that see if there is a user with the entered email
     * if there is one, send him an email with a new password reset link and a token, else show error messages.
     */
    protected function resetpasswordAction()
    {
        $this->getView()->setTemplate('application/login/resetpassword');

        /*
         * @var ResetPasswordForm
         */
        $form = $this->resetPasswordForm;
        $form->get('resetpw')->setValue($this->translate('RESET_PW'));
        $form->get('email')->setLabel($this->translate('EMAIL'));
        $this->getView()->setVariable('form', $form);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($form->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $formData = $form->getData();
                /** @var \Admin\Entity\User $existingEmail */
                $existingEmail = $this->getTable('Admin\\Model\\UserTable')
                                        ->getEntityRepository()
                                        ->findBy(['email' => $formData['email']]);

                if (count($existingEmail) === 1) {
                    $func = $this->getFunctions();
                    $token = $func::generateToken();
                    $resetpw = new ResetPassword();
                    $remote = new RemoteAddress();
                    $resetpw->setToken($token);
                    $resetpw->setUser($existingEmail->getId());
                    $resetpw->setDate(date('Y-m-d H:i:s', time()));
                    $resetpw->setIp($remote->getIpAddress());
                    $this->getTable('Application\\Model\\ResetPasswordTable')->saveResetPassword($resetpw);
                    $message = $this->translate('NEW_PW_TEXT').' '.$_SERVER['SERVER_NAME']."/login/newpassword/token/{$token}";

                    $result = $this->Mailing()->sendMail($formData['email'], $existingEmail->getFullName(), $this->translate('NEW_PW_TITLE'), $message, $this->systemSettings('general', 'system_email'), $this->systemSettings('general', 'site_name'));

                    if (!$result) {
                        $this->setLayoutMessages($this->translate('EMAIL_NOT_SENT'), 'error');
                    } else {
                        $this->setLayoutMessages($this->translate('PW_SENT').' <b>'.$formData['email'].'</b>', 'success');
                    }
                } else {
                    $this->setLayoutMessages($this->translate('EMAIL').' <b>'.$formData['email'].'</b> '.$this->translate('NOT_FOUND'), 'warning');
                }
            } else {
                $this->setLayoutMessages($form->getMessages(), 'error');
            }
        }

        return $this->getView();
    }

    /**
     * Clear all sessions.
     */
    protected function logoutAction()
    {
        $this->getTranslation()->getManager()->getStorage()->clear();
        $this->authService->clearIdentity();

        return $this->redirect()->toUrl('/');
    }
}
