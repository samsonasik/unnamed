<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller;

use SD\Application\Exception\RuntimeException;
use SD\Application\Form\NewPasswordForm;
use Zend\Http\PhpEnvironment\RemoteAddress;

/**
 * @method object getTable($tableName)
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 * @method mixed UserData()
 * @method mixed getParam($paramName = null)
 * @method mixed getFunctions()
 */
final class NewPasswordController extends BaseController
{
    /*
     * @var NewPasswordForm
     */
    private $newPasswordForm;

    /**
     * @param NewPasswordForm $newPasswordForm
     */
    public function __construct(NewPasswordForm $newPasswordForm)
    {
        parent::__construct();

        $this->newPasswordForm = $newPasswordForm;
    }

    /**
     * The ResetpasswordController has generated a random token string.
     * In order to reset the account password, we need to take that token and validate it first.
     * If everything is fine, we let the user reset his password.
     *
     * @throws RuntimeException
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/newpassword/index');

        $token = (string) $this->getParam('token');

        if (!$token) {
            throw new RuntimeException($this->translate('TOKEN_MISMATCH'));
        }

        /*
         * See if token exist or has expired
         */
        $date = date('Y-m-d H:m:s', strtotime('last day'));
        $tokenExist = $this->getTable('SD\\Application\\Model\\ResetPasswordTable')->queryBuilder()->getEntityManager();
        $tokenExist = $tokenExist->createQuery('SELECT rp.user, rp.date, rp.token FROM SD\Application\Entity\ResetPassword rp WHERE rp.token = :token AND rp.date >= :dateInterval')
            ->setParameter(':token', (string) $token)
            ->setParameter(':dateInterval', (string) $date);

        $tokenExist = $tokenExist->getResult();

        if (empty($tokenExist)) {
            $this->setLayoutMessages($this->translate('LINK_EXPIRED'), 'error');

            return $this->redirect()->toUrl('/');
        }

        $tokenExist = $tokenExist[0];
        /*
         * @var NewPasswordForm
         */
        $form = $this->newPasswordForm;

        $this->getTranslation()->offsetSet('resetpwUserId', $tokenExist['user']);
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

            if (!$form->isValid()) {
                $this->setLayoutMessages($form->getMessages(), 'error');
            }

            $formData = $form->getData();
            $pw = $func::createPassword($formData['password']);

            if (!empty($pw)) {
                /** @var \SD\Admin\Entity\User $user */
                $user = $this->getTable('SD\\Admin\\Model\\UserTable')->getUser($this->getTranslation()->offsetGet('resetpwUserId'));
                $remote = new RemoteAddress();
                $user->setPassword($pw);
                $user->setIp($remote->getIpAddress());
                $this->getTable('SD\\Admin\\Model\\UserTable')->saveUser($user);
                $this->setLayoutMessages($this->translate('NEW_PW_SUCCESS'), 'success');
            } else {
                $this->setLayoutMessages($this->translate('PASSWORD_NOT_GENERATED'), 'error');
            }
        }

        return $this->redirect()->toUrl('/login');
    }
}
