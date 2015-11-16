<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Captcha;
use Zend\Captcha\Image as CaptchaImage;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

final class RegistrationForm extends Form implements InputFilterProviderInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct()
    {
        parent::__construct('registration');
    }

    /**
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/registration/processregistration');
        $this->setAttribute('role', 'form');

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'name',
            'options'    => [
                'label'          => 'NAME',
                'object_manager' => $this->objectManager,
            ],
            'attributes' => [
                'required'    => true,
                'placeholder' => 'ENTER_NAME',
                'min'         => 3,
                'max'         => 20,
                'size'        => 30,
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Password',
            'name'       => 'password',
            'options'    => [
                'label'          => 'PASSWORD',
                'object_manager' => $this->objectManager,
            ],
            'attributes' => [
                'required'    => true,
                'placeholder' => 'PASSWORD',
                'min'         => 8,
                'size'        => 30,
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Password',
            'name'       => 'repeatpw',
            'options'    => [
                'label'          => 'REPEAT_PASSWORD',
                'object_manager' => $this->objectManager,
            ],
            'attributes' => [
                'required'    => true,
                'placeholder' => 'REPEAT_PASSWORD',
                'size'        => 30,
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Email',
            'name'       => 'email',
            'options'    => [
                'label'          => 'EMAIL',
                'object_manager' => $this->objectManager,
            ],
            'attributes' => [
                'required'    => true,
                'min'         => 3,
                'size'        => 30,
                'placeholder' => 'johnsmith@example.com',
            ],
            ]
        );

        $captchaImage = new CaptchaImage(
            [
            'font'           => 'public/layouts/default/front/fonts/arial.ttf',
            'width'          => 180,
            'height'         => 50,
            'size'           => 30,
            'fsize'          => 20,
            'dotNoiseLevel'  => 10,
            'lineNoiseLevel' => 2,
            ]
        );

        $captchaImage->setImgDir('./public/userfiles/captcha');
        $captchaImage->setImgUrl('/userfiles/captcha');

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Captcha',
            'name'       => 'captcha',
            'attributes' => [
                'class'       => 'captcha-input',
                'placeholder' => 'ENTER_CAPTCHA',
                'size'        => 30,
            ],
            'options' => [
                'label'   => 'CAPTCHA',
                'captcha' => $captchaImage,
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Csrf',
            'name'    => 's',
            'options' => [
                'csrf_options' => [
                    'timeout' => 320,
                ],
            ],
            ]
        );

        $this->add(
            [
            'name'       => 'register',
            'attributes' => [
                'type'  => 'submit',
                'id'    => 'submitbutton',
                'value' => 'SIGN_UP',
            ],
            ]
        );
    }

    public function getInputFilterSpecification()
    {
        return [
            [
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'EmailAddress',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'messages' => ['emailAddressInvalidFormat' => "Email address doesn't appear to be valid."],
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 5,
                        ],
                    ],
                    ['name' => 'NotEmpty'],
                ],
            ],
            [
                'name'     => 'name',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                        ],
                    ],
                    ['name' => 'NotEmpty'],
                ],
            ],
            [
                'name'     => 'password',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 8,
                        ],
                    ],
                    ['name' => 'NotEmpty'],
                ],
            ],
            [
                'name'     => 'repeatpw',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 8,
                        ],
                    ],
                    ['name' => 'NotEmpty'],
                    [
                        'name'    => 'Identical',
                        'options' => [
                            'token'   => 'password',
                            'message' => 'Passwords do not match',
                        ],
                    ],
                ],
            ],
        ];
    }
}
