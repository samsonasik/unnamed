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

use SD\Application\Form\ContactForm;

/**
 * @method object getTable($tableName)
 * @method object setLayoutMessages($message = [], $namespace = 'default')
 * @method string translate($message = '')
 * @method mixed getParam($paramName = null, $default = null)
 * @method mixed Mailing()
 * @method string|null systemSettings($option = 'general', $value = 'site_name')
 */
final class ContactController extends BaseController
{
    /*
     * @var ContactForm
     */
    private $contactForm;

    /**
     * @param ContactForm $contactForm
     */
    public function __construct(ContactForm $contactForm)
    {
        parent::__construct();
        $this->contactForm = $contactForm;
    }

    /**
     * Simple contact form.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/contact/index');

        /*
         * @var ContactForm
         */
        $form = $this->contactForm;

        $form->get('email')->setLabel($this->translate('EMAIL'));
        $form->get('name')->setLabel($this->translate('NAME'))->setAttribute('placeholder', $this->translate('ENTER_NAME'));
        $form->get('subject')->setLabel($this->translate('SUBJECT'))->setAttribute('placeholder', $this->translate('ENTER_SUBJECT'));
        $form->get('captcha')->setLabel($this->translate('CAPTCHA'))->setAttribute('placeholder', $this->translate('ENTER_CAPTCHA'));
        $form->get('message')->setLabel($this->translate('MESSAGE'))->setAttribute('placeholder', $this->translate('ENTER_MESSAGE'));

        $this->getView()->setVariable('form', $form);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($form->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                try {
                    $this->Mailing()->sendMail($this->systemSettings('general', 'system_email'), '', $formData['subject'], $formData['message'], $formData['email'], $formData['name']);
                    $this->setLayoutMessages($this->translate('CONTACT_SUCCESS'), 'success');
                } catch (\Exception $exception) {
                    $this->setLayoutMessages($this->translate('CONTACT_ERROR'), 'error');
                }
            } else {
                $this->setLayoutMessages($form->getMessages(), 'error');
            }
        }

        return $this->getView();
    }
}
