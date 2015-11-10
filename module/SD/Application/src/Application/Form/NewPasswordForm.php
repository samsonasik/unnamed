<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

final class NewPasswordForm extends Form implements InputFilterProviderInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct()
    {
        parent::__construct('newpassword');
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
        $this->setAttribute('action', '/newpassword/newpasswordprocess');
        $this->setAttribute('role', 'form');

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Password',
            'name'       => 'password',
            'options'    => [
                'label'  => 'PASSWORD',
                'object_manager' => $this->objectManager,
            ],
            'attributes' => [
                'required'    => true,
                'min'         => 8,
                'size'        => 30,
                'placeholder' => 'PASSWORD',
            ],
            'options' => [
                'label' => 'Password',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Password',
            'name'       => 'repeatpw',
            'options'    => [
                'label'  => 'REPEAT_PASSWORD',
                'object_manager' => $this->objectManager,
            ],
            'attributes' => [
                'required'    => true,
                'size'        => 30,
                'placeholder' => 'REPEAT_PASSWORD',
            ],
            'options' => [
                'label' => 'Repeat password',
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
            'name'       => 'resetpw',
            'attributes' => [
                'type' => 'submit',
                'id'   => 'submitbutton',
                'value' => 'RESET_PW'
            ],
            ]
        );
    }

    public function getInputFilterSpecification()
    {
        return [
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
