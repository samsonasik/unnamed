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

use SD\Admin\Entity\User;
use SD\Application\Form\RegistrationForm;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Mvc\MvcEvent;

/**
 * @method object getTable($tableName)
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 * @method mixed UserData()
 * @method mixed getFunctions()
 * @method string|null systemSettings($option = 'general', $value = 'site_name')
 */
final class RegistrationController extends BaseController
{
    /*
     * @var RegistrationForm
     */
    private $registrationForm;

    /**
     * @param RegistrationForm $registrationForm
     */
    public function __construct(RegistrationForm $registrationForm)
    {
        parent::__construct();
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

        /*
         * If user is logged and tries to access one of the given actions
         * he will be redirected to the root url of the website.
         * For reset password and new password actions we assume that the user is not logged in.
         */
        if (APP_ENV === 'development') {
            $this->UserData()->checkIdentity();
        }
    }

    /**
     * @return \Zend\Http\Response
     */
    public function processregistrationAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()->toUrl('/registration');
        }

        /*
         * @var RegistrationForm
         */
        $form = $this->registrationForm;

        $form->setInputFilter($form->getInputFilter());
        $form->setData($request->getPost());

        if ($form->isValid()) {
            $formData = $form->getData();
            $remote = new RemoteAddress();

            /*
             * See if there is already registered user with this email
             */
            $existingEmail = $this->getTable('SD\Admin\\Model\\UserTable')
                                    ->getEntityRepository()
                                    ->findBy(['email' => $formData['email']]);

            if (count($existingEmail) > 0) {
                return $this->setLayoutMessages($this->translate('EMAIL_EXIST').' <b>'.$formData['email'].'</b> '.$this->translate('ALREADY_EXIST'), 'info');
            } else {
                $func = $this->getFunctions();
                $registerUser = new User();
                $registerUser->setName($formData['name']);
                $registerUser->setPassword($func::createPassword($formData['password']));
                $registerUser->setRegistered(date('Y-m-d H:i:s', time()));
                $registerUser->setIp($remote->getIpAddress());
                $registerUser->setEmail($formData['email']);
                $registerUser->setLanguage($this->language());
                $this->getTable('SD\Admin\\Model\\UserTable')->saveUser($registerUser);

                return $this->setLayoutMessages($this->translate('REGISTRATION_SUCCESS'), 'success');
            }
        } else {
            return $this->setLayoutMessages($form->getMessages(), 'error');
        }
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/registration/index');

        if ($this->systemSettings('registration', 'allow_registrations') !== 1) {
            $this->getView()->setVariable('form', $this->translate('REGISTRATION_CLOSED'));

            return $this->getView();
        }

        /*
         * @var RegistrationForm
         */
        $form = $this->registrationForm;

        $this->getView()->setVariable('form', $form);

        return $this->getView();
    }
}
