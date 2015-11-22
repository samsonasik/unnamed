<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       TBA
 */
namespace SD\Application\Controller;

use SD\Application\Entity\ResetPassword;
use SD\Application\Form\ResetPasswordForm;
use Zend\Http\PhpEnvironment\RemoteAddress;

/**
 * @method object getTable($tableName)
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 * @method mixed UserData()
 * @method mixed getFunctions()
 * @method object Mailing()
 * @method string|null systemSettings($option = 'general', $value = 'site_name')
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
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/resetpassword/index');

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
            return $this->redirect()->toUrl('/reset-password');
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
        /** @var \SD\Admin\Entity\User $existingEmail */
        $existingEmail = $this->getTable('SD\\Admin\\Model\\UserTable')
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
        $this->getTable('SD\\Application\\Model\\ResetPasswordTable')->saveResetPassword($resetpw);
        $message = $this->translate('NEW_PW_TEXT').' '.$_SERVER['SERVER_NAME'].'/newpassword/token/'.$token;

        $result = $this->Mailing()->sendMail($formData['email'], $existingEmail[0]->getFullName(), $this->translate('NEW_PW_TITLE'), $message, $this->systemSettings('general', 'system_email'), $this->systemSettings('general', 'site_name'));

        if (!$result) {
            return $this->setLayoutMessages($this->translate('EMAIL_NOT_SENT'), 'error');
        }

        $this->setLayoutMessages($this->translate('PW_SENT').' <b>'.$formData['email'].'</b>', 'success');

        return $this->redirect()->toUrl('/login');
    }
}
