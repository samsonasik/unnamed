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

final class ResetPasswordForm extends Form implements InputFilterProviderInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct()
    {
        parent::__construct('resetpassword');
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
        $this->setAttribute('action', '/reset-password/process');
        $this->setAttribute('role', 'form');

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Email',
            'name'       => 'email',
            'options'    => [
                'label'  => 'EMAIL',
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
                            'max'      => 255,
                        ],
                    ],
                    ['name' => 'NotEmpty'],
                ],
            ],
        ];
    }
}
