<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller;

use SD\Application\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

/**
 * @method object getTable($tableName)
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 * @method mixed UserData()
 */
final class LoginController extends BaseController
{
    /*
     * @var AuthenticationService
     */
    private $authService;

    /*
     * @var LoginForm
     */
    private $loginForm;

    /**
     * @param LoginForm             $loginForm
     * @param AuthenticationService $authService
     */
    public function __construct(LoginForm $loginForm,
        AuthenticationService $authService)
    {
        parent::__construct();

        $this->loginForm = $loginForm;
        $this->authService = $authService;
    }

    /**
     * Get database and check if given email and password matches.
     *
     * @param array $options
     *
     * @return \DoctrineModule\Authentication\Adapter\ObjectRepository
     */
    private function getAuthAdapter(array $options = [])
    {
        /** @var \DoctrineModule\Authentication\Adapter\ObjectRepository $authAdapter */
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setIdentity((string) $options['email']);
        $authAdapter->setCredential((string) $options['password']);

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
        $this->getTable('SD\Admin\Model\UserTable')->saveUser($result->getIdentity());
        Container::getDefaultManager()->regenerateId();

        $this->authService->getStorage()->write($result->getIdentity()); // puts only id in session!
        return $this->redirect()->toUrl('/');
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
