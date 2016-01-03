<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller;

use SD\Admin\Entity\User;
use SD\Application\Form\RegistrationForm;
use Zend\Http\PhpEnvironment\RemoteAddress;

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

        if (!$form->isValid()) {
            return $this->setLayoutMessages($form->getMessages(), 'error');
        }

        $formData = $form->getData();

        /*
         * See if there is already registered user with this email
         */
        $existingEmail = $this->getTable('SD\Admin\\Model\\UserTable')
                                ->getEntityRepository()
                                ->findBy(['email' => $formData['email']]);

        if (count($existingEmail) > 0) {
            return $this->setLayoutMessages($this->translate('EMAIL_EXIST').' <b>'.$formData['email'].'</b> '.$this->translate('ALREADY_EXIST'), 'info');
        }

        $func = $this->getFunctions();
        $remote = new RemoteAddress();
        $registerUser = new User();
        $registerUser->setName($formData['name']);
        $registerUser->setPassword($func::createPassword($formData['password']));
        $registerUser->setRegistered(date('Y-m-d H:i:s', time()));
        $registerUser->setIp($remote->getIpAddress());
        $registerUser->setEmail($formData['email']);
        $registerUser->setLanguage($this->language());
        $this->getTable('SD\Admin\\Model\\UserTable')->saveUser($registerUser);

        $this->setLayoutMessages($this->translate('REGISTRATION_SUCCESS'), 'success');

        return $this->redirect()->toUrl('/login');
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/registration/index');

        $this->getView()->setVariable('form', $this->registrationForm);

        if ($this->systemSettings('registration', 'allow_registrations') !== 1) {
            $this->getView()->setVariable('form', $this->translate('REGISTRATION_CLOSED'));
        }

        return $this->getView();
    }
}
