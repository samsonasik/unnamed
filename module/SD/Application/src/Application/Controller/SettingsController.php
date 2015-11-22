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

use SD\Admin\Entity\User;
use SD\Application\Form\SettingsForm;
use Zend\File\Transfer\Adapter\Http;
use Zend\Mvc\MvcEvent;
use Zend\Validator\File\Extension;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\Size;

/**
 * @method object getTable($tableName)
 * @method mixed UserData()
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 */
final class SettingsController extends BaseController
{
    /**
     * @var SettingsForm
     */
    private $settingsForm;

    /**
     * @param SettingsForm $settingsForm
     */
    public function __construct(SettingsForm $settingsForm)
    {
        parent::__construct();
        $this->settingsForm = $settingsForm;
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
            if (!$this->UserData()->hasIdentity()) {
                $this->redirect()->toUrl('/');
            }
        }
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/settings/index');

        $form = $this->settingsForm;
        $user = $this->getTable('SD\\Admin\\Model\\UserTable')->getUser($this->UserData()->getIdentity());

        $this->initForm($user);

        $this->getView()->setVariable('form', $form);

        return $this->getView();
    }

    /**
     * This is common function used by add and edit actions (to avoid code duplication).
     *
     * @param User|null $user
     *
     * @return false|object
     */
    private function initForm(User $user = null)
    {
        if (!$user instanceof User) {
            throw new AuthorizationException($this->translate('ERROR_AUTHORIZATION'));
        }

        $form = $this->settingsForm;
        $form->bind($user);
        $oldImage = $user->getImage(); //hack
        $this->getView()->setVariable('form', $form);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($form->getInputFilter());
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);
            if ($form->isValid()) {
                $formData = $form->getData();

                // check for existing email
                $existingEmail = $this->getTable('SD\\Admin\\Model\\UserTable')->queryBuilder()
                    ->select(['u'])
                    ->from('SD\Admin\Entity\User', 'u')
                    ->where('u.email = :email')
                    ->setParameter(':email', (string) $formData->getEmail())->getQuery()->getResult();

                if (count($existingEmail) > 1) {
                    return $this->setLayoutMessages($this->translate('EMAIL_EXIST').' <b>'.$formData->getEmail().'</b> '.$this->translate('ALREADY_EXIST'), 'info');
                }

                if (!empty($formData->getImage()['name'])) {
                    $ext = pathinfo($formData->getImage()['name'], PATHINFO_EXTENSION);
                    $newName = $formData->getId().'.'.$ext;
                    $this->uploadImage($formData->getId());
                    $user->setImage($newName);
                } else {
                    $user->setImage($oldImage);
                }

                $this->getTable('SD\\Admin\\Model\\UserTable')->saveUser($user);

                return $this->setLayoutMessages($this->translate('SETTINGS').' '.$this->translate('SAVE_SUCCESS'), 'success');
            }

            return $this->setLayoutMessages($form->getMessages(), 'error');
        }

        return false;
    }

    /**
     * @param int $userId - used to create a folder for the current user and rename the image
     *
     * @return void
     */
    private function uploadImage($userId = 0)
    {
        $userId = (int) $userId;
        $messages = [];
        $dir = 'public/userfiles/images/user-'.$userId.'/';
        $adapter = new Http();
        $size = new Size(['min' => '10kB', 'max' => '5MB', 'useByteString' => true]);
        $extension = new Extension(['jpg', 'gif', 'png', 'jpeg', 'bmp', 'webp', 'svg'], true);

        if (!is_dir($dir)) {
            mkdir($dir, 0750, true);
        }

        foreach ($adapter->getFileInfo() as $file) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = $userId.'.'.$ext;
            $adapter->setValidators([$size, new IsImage(), $extension]);
            $adapter->addFilter('File\Rename', [
                'target'    => $dir.$newName,
                'overwrite' => true,
            ]);
            $adapter->receive($file['name']);

            if (!$adapter->isReceived($file['name']) && $adapter->isUploaded($file['name'])) {
                $messages[] = $file['name'].' was not uploaded';
            }
        }

        $this->setLayoutMessages($messages, 'info');
    }
}
